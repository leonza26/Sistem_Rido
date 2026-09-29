<?php

namespace App\Http\Controllers\kasir;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\ModalKasir;
use App\Services\QrisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class KasirMainController extends Controller
{
    //
    public function index()
    {
        $today = Carbon::today();

        $todayRevenue = DetailTransaksi::whereDate('created_at', $today)
            ->sum('subtotal');

        $todayCash = Transaksi::whereDate('transaction_date', $today)
            ->whereRaw('LOWER(payment_method) = ?', ['tunai'])
            ->sum('total_amount');

        $todayNonCash = Transaksi::whereDate('transaction_date', $today)
            ->whereRaw('LOWER(payment_method) != ?', ['tunai'])
            ->sum('total_amount');

        $todayModalKasir = (float) (ModalKasir::whereDate('tanggal', $today)->value('modal_awal') ?? 0);
        $todayKasKasir = $todayModalKasir + $todayCash;

        $totalItemsSold = DetailTransaksi::whereDate('created_at', $today)
            ->sum('qty');

        $latestTransactions = Transaksi::latest()
            ->take(3)
            ->get();

        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $revenue = Transaksi::whereDate('created_at', $date)
                ->sum('total_amount');

            $salesData[] = [
                'label'     => $date->isoFormat('ddd'), // Format: Sen, Sel, Rab...
                'revenue'   => $revenue,
                'formatted' => 'Rp ' . number_format($revenue, 0, ',', '.')
            ];
        }

        // 4. Skala Grafik (Y-Axis)
        $maxRevenue = collect($salesData)->max('revenue');
        $yAxisMax = $maxRevenue > 0 ? $maxRevenue : 1000000; // Default 1jt jika kosong

        return view('kasir.dashboard', compact(
            'todayRevenue',
            'todayCash',
            'todayNonCash',
            'todayModalKasir',
            'todayKasKasir',
            'totalItemsSold',
            'latestTransactions',
            'salesData',
            'yAxisMax'
        ));
    }

    public function transaksi()
    {
        $categories = Kategori::with('produks');
        return view('kasir.transaksi', compact('categories'));
    }

    public function stok_barang()
    {
        $categories = Kategori::all();

        return view('kasir.stok_barang', compact('categories'));
    }

    /**
     * Langkah 1 pembayaran QRIS: membuat QR berisi total belanja.
     * Keranjang disimpan sementara di session sampai kasir mengonfirmasi pembayaran.
     */
    public function buatQris(Request $request)
    {
        $cart = $this->normalizeCart($request->input('cart', []));

        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong'], 400);
        }

        // cek stok sebelum pembeli membayar
        $produk = Produk::whereIn('id', array_column($cart, 'id'))->get()->keyBy('id');

        foreach ($cart as $item) {
            if ($produk[$item['id']]->stok_awal < $item['qty']) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Stok produk {$item['name']} tidak mencukupi.",
                ], 422);
            }
        }

        $qris = QrisService::fromConfig();

        if (! $qris->isConfigured()) {
            return response()->json([
                'status' => 'error',
                'message' => 'QRIS toko belum diatur. Isi QRIS_PAYLOAD di file .env.',
            ], 422);
        }

        // total dihitung dari harga di database, bukan dari data browser
        $total = (int) round(array_sum(array_map(fn ($item) => $item['price'] * $item['qty'], $cart)));

        try {
            $payload = $qris->payloadFor($total);
        } catch (InvalidArgumentException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }

        $orderId = 'QRIS-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        session()->put("qris_pending.$orderId", [
            'cart' => $cart,
            'total' => $total,
        ]);

        return response()->json([
            'status' => 'success',
            'order_id' => $orderId,
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
            'qris_payload' => $payload,
            'mode' => $qris->mode(),
            'merchant_name' => $qris->merchantName(),
            'nmid' => $qris->nmid(),
        ]);
    }

    /**
     * Langkah 2 pembayaran QRIS: kasir menekan "Pembayaran Diterima" setelah
     * notifikasi pembayaran muncul di aplikasi Livin' Merchant.
     */
    public function konfirmasiQris(string $orderId)
    {
        // sudah pernah dikonfirmasi (misalnya tombol ditekan dua kali)
        $sudahTersimpan = Transaksi::where('transaction_id', $orderId)->first();

        if ($sudahTersimpan) {
            session()->forget("qris_pending.$orderId");

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi QRIS sudah tersimpan.',
                'transaction_id' => $sudahTersimpan->transaction_id,
            ]);
        }

        $pending = session("qris_pending.$orderId");

        if (! $pending) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi QRIS tidak ditemukan atau sudah dibatalkan.',
            ], 404);
        }

        try {
            $transaksi = $this->persistTransaction($orderId, 'qris', $pending['cart']);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'Gagal menyimpan transaksi QRIS.',
            ], 422);
        }

        session()->forget("qris_pending.$orderId");

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran QRIS diterima dan transaksi tersimpan.',
            'transaction_id' => $transaksi->transaction_id,
            'total' => (int) $transaksi->total_amount,
        ]);
    }

    /**
     * Pembeli batal membayar: QR dibuang, tidak ada transaksi yang disimpan.
     */
    public function batalQris(string $orderId)
    {
        session()->forget("qris_pending.$orderId");

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran QRIS dibatalkan.',
        ]);
    }

    public function simpanTransaksi(Request $request)
    {
        try {
            // dipakai untuk transaksi tunai; pembayaran QRIS disimpan lewat konfirmasiQris()
            $request->validate([
                'order_id' => ['required', 'string'],
                'payment_type' => ['required', 'string', 'in:tunai'],
                'cart' => ['required', 'array', 'min:1'],
            ]);

            $cart = $this->normalizeCart($request->input('cart', []));
            $transaksi = $this->persistTransaction(
                $request->order_id,
                $request->payment_type,
                $cart
            );

            return response()->json([
                'message' => 'Transaksi berhasil disimpan',
                'transaction_id' => $transaksi->transaction_id,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => $e->getMessage() ?: 'Gagal menyimpan transaksi',
            ], 422);
        }
    }

    private function normalizeCart(array $cart): array
    {
        if (empty($cart)) {
            return [];
        }

        $productIds = collect($cart)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $products = Produk::whereIn('id', $productIds)->get()->keyBy('id');

        $normalizedCart = [];

        foreach ($cart as $item) {
            $productId = (int) ($item['id'] ?? 0);
            $qty = (int) ($item['qty'] ?? 0);
            $product = $products->get($productId);

            if (! $product || $qty < 1) {
                continue;
            }

            $normalizedCart[] = [
                'id' => $product->id,
                'name' => $product->nama_produk,
                'price' => (float) $product->harga,
                'qty' => $qty,
            ];
        }

        return $normalizedCart;
    }

    private function persistTransaction(string $orderId, string $paymentType, array $cart): Transaksi
    {
        $existingTransaction = Transaksi::where('transaction_id', $orderId)->first();

        if ($existingTransaction) {
            return $existingTransaction;
        }

        return DB::transaction(function () use ($orderId, $paymentType, $cart) {
            $total = 0;

            $transaksi = Transaksi::create([
                'transaction_id' => $orderId,
                'transaction_date' => now(),
                'user_id' => Auth::id(),
                'cashier_name' => Auth::user()->name ?? 'Kasir',
                'total_amount' => 0,
                'payment_method' => $paymentType,
            ]);

            foreach ($cart as $item) {
                $produk = Produk::whereKey($item['id'])->lockForUpdate()->firstOrFail();

                if ($produk->stok_awal < $item['qty']) {
                    throw new \RuntimeException("Stok produk {$produk->nama_produk} tidak mencukupi.");
                }

                // harga diambil saat keranjang diproses (untuk QRIS: saat QR dibuat),
                // sehingga total yang tersimpan sama dengan nominal di QR
                $harga = $item['price'] ?? $produk->harga;
                $subtotal = $harga * $item['qty'];
                $total += $subtotal;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'qty' => $item['qty'],
                    'harga' => $harga,
                    'harga_modal' => $produk->harga_modal ?? 0,
                    'subtotal' => $subtotal,
                ]);

                $produk->decrement('stok_awal', $item['qty']);
            }

            $transaksi->update(['total_amount' => $total]);

            return $transaksi;
        });
    }
}
