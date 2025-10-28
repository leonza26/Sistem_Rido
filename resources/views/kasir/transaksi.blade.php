@extends('kasir.layouts.layout')

@section('title', 'Transaksi Baru - Toserba Hasan')
@section('page-title', 'Transaksi Baru')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Pencarian & Keranjang (2/3 Lebar) -->
        <div class="lg:col-span-2">

            <!-- Card Pencarian Produk [cite: 62] -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
                <form id="form-tambah-item" class="flex flex-col sm:flex-row items-end gap-3">
                    <div class="flex-grow w-full">
                        <label for="search_produk" class="block text-sm font-medium text-slate-700">Cari Produk (Kode / Nama)</label>
                        <!-- Input pencarian -->
                        <input type="text" id="search_produk" name="search_produk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik kode atau nama produk...">
                        <!-- Di sini nanti akan muncul hasil pencarian AJAX -->
                    </div>
                    <div class="w-full sm:w-24">
                        <label for="qty" class="block text-sm font-medium text-slate-700">Qty</label>
                        <input type="number" id="qty" name="qty" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="1" min="1">
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 inline-flex items-center justify-center space-x-2">
                        <!-- Icon Plus -->
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                </form>
            </div>

            <!-- Card Keranjang Belanja (Barang Terpilih) [cite: 64-66] -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Barang Terpilih</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="keranjang-items" class="bg-white divide-y divide-gray-200">
                            <!-- Data Dummy 1 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Sabun Lifebuoy</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 5.000</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">Rp 10.000</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="btn-hapus-item text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <!-- Data Dummy 2 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Indomie Goreng</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 3.500</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">Rp 17.500</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="btn-hapus-item text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <!-- Items akan ditambahkan oleh JS di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pembayaran (1/3 Lebar) -->
        <div class="lg:col-span-1">
            <!-- Dibuat sticky agar tetap terlihat saat di-scroll -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm sticky top-6">

                <h2 class="text-lg font-semibold text-slate-700 mb-4">Detail Pembayaran</h2>

                <!-- Total Harga [cite: 67] -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-600">Total Harga</label>
                    <p id="total-harga" class="text-3xl font-bold text-slate-800">Rp 27.500</p>
                    <!-- Data dummy, nanti akan dihitung JS -->
                </div>

                <!-- Metode Pembayaran [cite: 69] -->
                <div class="mb-6">
                    <label for="metode_pembayaran" class="block text-sm font-medium text-slate-700">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="tunai">Tunai</option>
                        <option value="qris">QRIS (Non-Tunai)</option>
                        <option value="transfer">Transfer (Non-Tunai)</option>
                    </select>
                </div>

                <!-- Input Uang Tunai (Tampil jika metode 'tunai') -->
                <div id="input-uang-tunai" class="mb-4">
                    <label for="uang_tunai" class="block text-sm font-medium text-slate-700">Uang Tunai</label>
                    <input type="number" id="uang_tunai" name="uang_tunai" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Rp 0">
                </div>

                <!-- Info Kembalian (Tampil jika metode 'tunai') -->
                <div id="info-kembalian" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-slate-600">Kembalian</label>
                    <p id="uang-kembalian" class="text-xl font-medium text-green-600">Rp 0</p>
                </div>


                <!-- Tombol Simpan & Cetak -->
                <button id="btn-simpan-cetak" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 font-semibold text-lg inline-flex items-center justify-center space-x-2">
                    <!-- Icon Printer -->
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6 18.25M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                    <span>Simpan & Cetak Struk</span>
                </button>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- Referensi Elemen ---
        const metodePembayaran = document.getElementById('metode_pembayaran');
        const inputUangTunai = document.getElementById('input-uang-tunai');
        const infoKembalian = document.getElementById('info-kembalian');
        const uangTunaiInput = document.getElementById('uang_tunai');
        const uangKembalianText = document.getElementById('uang-kembalian');
        const totalHargaText = document.getElementById('total-harga');
        const keranjangItems = document.getElementById('keranjang-items');

        // --- Fungsi Helper ---

        // Fungsi untuk format mata uang
        const formatRupiah = (angka) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        };

        // Fungsi untuk parse string rupiah ke angka
        const parseRupiah = (str) => {
            return parseFloat(str.replace(/[^0-9,-]+/g,"").replace(/,/g,".")) || 0;
        };

        // Fungsi untuk menghitung total harga dari tabel
        const hitungTotalHarga = () => {
            let total = 0;
            keranjangItems.querySelectorAll('tr').forEach(row => {
                // Ambil subtotal dari kolom ke-5 (index 4)
                const subtotalText = row.children[4].textContent;
                total += parseRupiah(subtotalText);
            });
            totalHargaText.textContent = formatRupiah(total);
            return total;
        };

        // Fungsi untuk menghitung kembalian
        const hitungKembalian = () => {
            const total = hitungTotalHarga(); // Dapatkan total terbaru
            const tunai = parseFloat(uangTunaiInput.value) || 0;

            if (tunai >= total) {
                const kembalian = tunai - total;
                uangKembalianText.textContent = formatRupiah(kembalian);
                infoKembalian.classList.remove('hidden');
            } else {
                infoKembalian.classList.add('hidden'); // Sembunyikan jika uang tunai kurang
            }
        };

        // --- Event Listeners ---

        // Tampilkan/sembunyikan input uang tunai berdasarkan metode pembayaran
        metodePembayaran.addEventListener('change', function () {
            if (this.value === 'tunai') {
                inputUangTunai.classList.remove('hidden');
                hitungKembalian(); // Hitung ulang saat ganti ke tunai
            } else {
                inputUangTunai.classList.add('hidden');
                infoKembalian.classList.add('hidden'); // Sembunyikan kembalian jika non-tunai
                uangTunaiInput.value = ''; // Kosongkan input
            }
        });

        // Hitung kembalian secara real-time saat kasir mengetik
        uangTunaiInput.addEventListener('input', hitungKembalian);

        // TODO: Logika untuk 'form-tambah-item' (AJAX Search, dll)
        document.getElementById('form-tambah-item').addEventListener('submit', function (e) {
            e.preventDefault();
            console.log('Tambah item ke keranjang...');
            // Simulasi tambah item baru
            // Anda harus mengganti ini dengan data dari AJAX search
            const newRow = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Teh Pucuk</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 3.000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">Rp 3.000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="btn-hapus-item text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
            keranjangItems.insertAdjacentHTML('beforeend', newRow);

            // Hitung ulang semua
            hitungTotalHarga();
            hitungKembalian();
        });

        // Logika untuk 'btn-hapus-item' (Pakai Event Delegation)
        keranjangItems.addEventListener('click', function(e) {
            // Cari parent terdekat yang punya class 'btn-hapus-item'
            const hapusButton = e.target.closest('.btn-hapus-item');
            if (hapusButton) {
                if (confirm('Hapus item ini dari keranjang?')) {
                    hapusButton.closest('tr').remove();
                    hitungTotalHarga(); // Hitung ulang total
                    hitungKembalian(); // Hitung ulang kembalian
                    console.log('Item dihapus');
                }
            }
        });

        // Logika untuk 'btn-simpan-cetak'
        document.getElementById('btn-simpan-cetak').addEventListener('click', function () {
            // TODO: Validasi data
            // TODO: Kirim data ke backend (AJAX)
            // TODO: Buka popup struk (window.print()) [cite: 71-72]

            // Sesuai wireframe [cite: 71-72], kita akan simulasikan print
            alert('Simpan & Cetak Struk (Simulasi)...');
        });

        // Inisialisasi hitungan saat halaman dimuat
        hitungTotalHarga();
    });
</script>
@endpush
