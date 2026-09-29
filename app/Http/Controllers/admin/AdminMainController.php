<?php

namespace App\Http\Controllers\admin;

use App\Exports\TransactionsExport;
use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\ModalKasir;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AdminMainController extends Controller
{
    // dashboard view
    public function admin()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayRevenue = DetailTransaksi::whereDate('created_at', $today)->sum('subtotal');
        $todayQty = DetailTransaksi::whereDate('created_at', $today)->sum('qty');

        $todayCash = Transaksi::whereDate('transaction_date', $today)
            ->whereRaw('LOWER(payment_method) = ?', ['tunai'])
            ->sum('total_amount');

        $todayNonCash = Transaksi::whereDate('transaction_date', $today)
            ->whereRaw('LOWER(payment_method) != ?', ['tunai'])
            ->sum('total_amount');

        $todayModalKasir = (float) (ModalKasir::whereDate('tanggal', $today)->value('modal_awal') ?? 0);
        $todayKasKasir = $todayModalKasir + $todayCash;

        $yesterdayRevenue = DetailTransaksi::whereDate('created_at', $yesterday)->sum('subtotal');
        $yesterdayQty = DetailTransaksi::whereDate('created_at', $yesterday)->sum('qty');

        $revenueChange = $this->calculatePercentage($yesterdayRevenue, $todayRevenue);
        $qtyChange = $this->calculatePercentage($yesterdayQty, $todayQty);

        $mostSoldProduct = DetailTransaksi::whereDate('created_at', $today)
            ->select('produk_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('produk_id')
            ->orderByDesc('total_qty')
            ->with('produk:id,nama_produk')
            ->first();

        $lowStockProducts = Produk::where('stok_awal', '<', 10)->count();

        $salesData = [];
        $maxRevenue = 0;

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $revenue = Transaksi::whereDate('transaction_date', $date)->sum('total_amount') ?? 0;

            $salesData[] = [
                'label' => $date->isoFormat('ddd'),
                'revenue' => (float) $revenue,
                'formatted' => 'Rp ' . number_format($revenue, 0, ',', '.')
            ];

            if ($revenue > $maxRevenue) $maxRevenue = $revenue;
        }

        if ($maxRevenue > 0) {
            if ($maxRevenue < 1000) {
                // If sales are small (like 450), set max to 500 or 1000
                $yAxisMax = ceil($maxRevenue / 100) * 100;
            } else {
                // If sales are big (like 2jt), set max to nearest 1jt
                $yAxisMax = ceil($maxRevenue / 1000000) * 1000000;
            }
        } else {
            $yAxisMax = 1000; // Fallback
        }

        return view('admin.dashboard', compact(
            'lowStockProducts',
            'todayRevenue',
            'todayCash',
            'todayNonCash',
            'todayModalKasir',
            'todayKasKasir',
            'revenueChange',
            'todayQty',
            'qtyChange',
            'mostSoldProduct',
            'salesData',
            'yAxisMax'
        ));
    }

    // manage products
    public function manageProducts()
    {
        $categories = Kategori::all();
        return view('admin.manage_produk', compact('categories'));
    }

    // manage laporan
    public function manageLaporan(Request $request)
    {
        $start_date = $request->input('tanggal_awal');
        $end_date = $request->input('tanggal_akhir');
        // filter metode pembayaran (dipakai pemilik untuk rekonsiliasi QRIS dengan Livin' Merchant)
        $metode = $request->input('metode');

        $baseQuery = Transaksi::query()->metode($metode);

        if ($start_date && $end_date) {
            $baseQuery->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        }

        // Summary metrics
        $totalPendapatan = (clone $baseQuery)->sum('total_amount');
        $totalTunai = (clone $baseQuery)->whereRaw('LOWER(payment_method) = ?', ['tunai'])->sum('total_amount');
        $totalNonTunai = (clone $baseQuery)->whereRaw('LOWER(payment_method) != ?', ['tunai'])->sum('total_amount');

        // Total modal (HPP) & Laba Kotor dari semua data yang terfilter
        $transaksiIds = (clone $baseQuery)->pluck('id');
        $allDetails = DetailTransaksi::whereIn('transaksi_id', $transaksiIds)->with('produk')->get();

        $totalModal = $allDetails->sum(function ($detail) {
            $modal = ($detail->harga_modal > 0)
                ? $detail->harga_modal
                : ($detail->produk->harga_modal ?? 0);
            return $modal * $detail->qty;
        });

        $totalLabaKotor = $totalPendapatan - $totalModal;
        $marginLaba = $totalPendapatan > 0 ? round(($totalLabaKotor / $totalPendapatan) * 100, 1) : 0;

        // Rekap Harian (Grouping per tanggal)
        $allTransactionsForRekap = (clone $baseQuery)
            ->with(['details.produk'])
            ->orderByDesc('transaction_date')
            ->get();

        $rekapHarian = $allTransactionsForRekap->groupBy(function ($item) {
            return Carbon::parse($item->transaction_date)->format('Y-m-d');
        })->map(function ($dayTransactions, $date) {
            $omzet = $dayTransactions->sum('total_amount');
            $tunai = $dayTransactions->filter(fn($t) => strtolower($t->payment_method) === 'tunai')->sum('total_amount');
            $nonTunai = $dayTransactions->filter(fn($t) => strtolower($t->payment_method) !== 'tunai')->sum('total_amount');

            $modal = 0;
            foreach ($dayTransactions as $trx) {
                foreach ($trx->details as $d) {
                    $modalUnit = ($d->harga_modal > 0) ? $d->harga_modal : ($d->produk->harga_modal ?? 0);
                    $modal += ($modalUnit * $d->qty);
                }
            }

            return (object) [
                'tanggal' => Carbon::parse($date),
                'jumlah_transaksi' => $dayTransactions->count(),
                'total_omzet' => $omzet,
                'total_tunai' => $tunai,
                'total_non_tunai' => $nonTunai,
                'total_modal' => $modal,
                'laba_kotor' => $omzet - $modal,
            ];
        })->values();

        // Paginasi detail transaksi
        $transaksis = (clone $baseQuery)->with(['details.produk.kategori'])->latest('transaction_date')->paginate(10);

        return view('admin.laporan', compact(
            'transaksis',
            'start_date',
            'end_date',
            'metode',
            'totalPendapatan',
            'totalTunai',
            'totalNonTunai',
            'totalModal',
            'totalLabaKotor',
            'marginLaba',
            'rekapHarian'
        ));
    }

    // manage pengguna
    public function managePengguna()
    {
        $pengguna = User::orderBy('id', 'asc')->get();

        // pilihan role pada form sesuai hak pengguna yang login
        // (Pemilik: Pemilik/Admin/Kasir, Admin: Admin/Kasir)
        $roleOptions = [];
        foreach (auth()->user()->assignableRoles() as $role) {
            $roleOptions[$role] = User::ROLE_LABELS[$role];
        }

        return view('admin.user.manage_pengguna', compact('pengguna', 'roleOptions'));
    }

    //  Tambah user
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in($request->user()->assignableRoles())],
            'password' => 'required|min:6|same:konfirmasi_password'
        ], [
            'role.in' => 'Anda tidak berhak memberikan role tersebut.',
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'role' => (int) $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $actor = $request->user();

        // Admin hanya boleh mereset password akun Pemilik (pemulihan akses),
        // tidak boleh mengubah nama, email, maupun role-nya.
        if ($user->isPemilik() && ! $actor->isPemilik()) {
            $request->validate([
                'password' => 'required|min:6|same:konfirmasi_password'
            ], [
                'password.required' => 'Admin hanya dapat mereset password akun Pemilik. Isi password baru.',
            ]);

            $user->update(['password' => Hash::make($request->password)]);

            return redirect()->back()->with('success', 'Password akun Pemilik berhasil direset');
        }

        // role akun sendiri tidak dapat diubah agar tidak terkunci dari menunya sendiri
        $bolehUbahRole = $user->id !== $actor->id;

        $rules = [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        if ($bolehUbahRole) {
            $rules['role'] = ['required', Rule::in($actor->assignableRoles())];
        }

        $request->validate($rules, [
            'role.in' => 'Anda tidak berhak memberikan role tersebut.',
        ]);

        $data = [
            'name' => $request->nama,
            'email' => $request->email,
        ];

        if ($bolehUbahRole) {
            $data['role'] = (int) $request->role;
        }

        // kalau password diisi → update
        if ($request->password) {
            $request->validate([
                'password' => 'min:6|same:konfirmasi_password'
            ]);

            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    // Hapus user
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $actor = $request->user();

        if ($user->id === $actor->id) {
            return redirect()->back()->withErrors(['Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if ($user->isPemilik() && ! $actor->isPemilik()) {
            return redirect()->back()->withErrors(['Admin tidak dapat menghapus akun Pemilik.']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus');
    }

    public function storeProduk(Request $request)
    {
        // Validasi data
        // harga modal hanya diisi oleh Pemilik; produk yang ditambahkan Admin
        // tersimpan dengan harga modal 0 sampai dilengkapi oleh Pemilik
        $rules = [
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|string|max:255',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'harga' => 'required|numeric|min:0',
            'stok_awal' => 'required|integer|min:0'
        ];

        if ($request->user()->isPemilik()) {
            $rules['harga_modal'] = 'required|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        // dd($validatedData);

        if ($request->hasFile('foto_produk')) {
            $path = $request->file('foto_produk')->store('products', 'public');
            $validatedData['foto_produk'] = $path;
        }

        Produk::create($validatedData);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil Ditambahkan!');
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // harga modal hanya dapat diubah oleh Pemilik;
        // saat Admin mengubah produk, harga modal lama tetap dipertahankan
        $rules = [
            'nama_produk' => 'required|string|max:255',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga'       => 'required|numeric|min:0',
            'stok_awal'   => 'required|integer|min:0'
        ];

        if ($request->user()->isPemilik()) {
            $rules['harga_modal'] = 'required|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        if ($request->hasFile('foto_produk')) {
            // Delete old photo if it exists
            if ($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            $path = $request->file('foto_produk')->store('products', 'public');
            $validatedData['foto_produk'] = $path;
        }

        $produk->update($validatedData);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduk($id)
    {
        $produk = Produk::findOrFail($id);

        // Delete photo from storage
        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function exportPDF(Request $request)
    {
        $start = $request->tanggal_awal;
        $end = $request->tanggal_akhir;
        $metode = $request->metode;

        $query = Transaksi::with(['details.produk'])->metode($metode);
        if ($start && $end) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ]);
        }

        $transaksis = $query->latest('transaction_date')->get();

        $totalPendapatan = $transaksis->sum('total_amount');
        $totalModal = $transaksis->sum('total_modal');
        $totalLabaKotor = $totalPendapatan - $totalModal;

        $pdf = Pdf::loadView('admin.pdf_template', compact('transaksis', 'start', 'end', 'metode', 'totalPendapatan', 'totalModal', 'totalLabaKotor'));
        return $pdf->download('Laporan_Transaksi_' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $start = $request->tanggal_awal;
        $end = $request->tanggal_akhir;
        $metode = $request->metode;

        return Excel::download(new TransactionsExport($start, $end, $metode), 'Laporan_Transaksi.xlsx');
    }

    private function calculatePercentage($old, $new)
    {
        $old = (float) $old;
        $new = (float) $new;

        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        $diff = (($new - $old) / $old) * 100;

        return round($diff, 1);
    }

    public function downloadTemplateProduk(Request $request)
    {
        // template untuk Admin tidak memuat kolom Harga Modal
        $denganHargaModal = $request->user()->isPemilik();

        return Excel::download(new \App\Exports\ProdukTemplateExport($denganHargaModal), 'Template_Produk.xlsx');
    }

    public function importProduk(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx'
        ], [
            'file_excel.required' => 'File Excel wajib diunggah',
            'file_excel.mimes' => 'Format file harus berupa .xlsx'
        ]);

        try {
            // kolom Harga Modal pada file hanya diproses jika yang mengimpor adalah Pemilik
            $bolehIsiHargaModal = $request->user()->isPemilik();

            Excel::import(new \App\Imports\ProdukImport($bolehIsiHargaModal), $request->file('file_excel'));
            return redirect()->back()->with('success', 'Produk berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }

    public function manageModalKasir(Request $request)
    {
        $today = Carbon::today();
        $todayModal = ModalKasir::whereDate('tanggal', $today)->first();

        $historyModal = ModalKasir::with('user')
            ->orderByDesc('tanggal')
            ->paginate(15);

        // Hitung transaksi tunai hari ini
        $todayCashSales = Transaksi::whereDate('transaction_date', $today)
            ->whereRaw('LOWER(payment_method) = ?', ['tunai'])
            ->sum('total_amount');

        $modalAwalHariIni = $todayModal->modal_awal ?? 0;
        $totalKasFisikHariIni = $modalAwalHariIni + $todayCashSales;

        return view('admin.modal_kasir', compact(
            'todayModal',
            'historyModal',
            'todayCashSales',
            'modalAwalHariIni',
            'totalKasFisikHariIni'
        ));
    }

    public function storeModalKasir(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'modal_awal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        ModalKasir::updateOrCreate(
            ['tanggal' => Carbon::parse($request->tanggal)->toDateString()],
            [
                'user_id' => auth()->id(),
                'modal_awal' => $request->modal_awal,
                'keterangan' => $request->keterangan,
            ]
        );

        return redirect()->back()->with('success', 'Modal awal kasir berhasil disimpan!');
    }
}
