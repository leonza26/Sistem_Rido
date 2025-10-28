@extends('admin.layouts.layout')

@section('title', 'Manajemen Produk - Toserba Hasan')
@section('page-title', 'Manajemen Produk')

@section('content')

    <!-- Kontainer Utama -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-slate-700">Daftar Produk</h2>
            <button id="btn-tambah-produk" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 inline-flex items-center space-x-2">
                <!-- Icon Plus -->
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Produk</span>
            </button>
        </div>

        <!-- Tabel Responsif -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Sabun Lifebuoy</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rumah Tangga</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 5.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button class="btn-edit text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button class="btn-hapus text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Indomie Goreng</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Makanan</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 3.500</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">120</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button class="btn-edit text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button class="btn-hapus text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal Tambah/Edit Produk -->
    <div id="modal-produk" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg">

            <!-- Header Modal -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-semibold text-slate-800">Tambah Produk Baru</h3>
                <button id="btn-tutup-modal" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <!-- Form di dalam Modal -->
            <form id="form-produk" action="#" method="POST">
                @csrf
                <input type="hidden" id="produk-id" name="produk_id">

                <div class="space-y-4">
                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-slate-700">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-slate-700">Kategori</label>
                        <input type="text" id="kategori" name="kategori" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium text-slate-700">Harga</label>
                        <input type="number" id="harga" name="harga" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="stok_awal" class="block text-sm font-medium text-slate-700">Stok Awal</label>
                        <input type="number" id="stok_awal" name="stok_awal" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Footer Modal [cite: 87] -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="btn-batal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Menjalankan script setelah dokumen siap
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('modal-produk');
        const btnTambah = document.getElementById('btn-tambah-produk');
        const btnTutupModal = document.getElementById('btn-tutup-modal');
        const btnBatal = document.getElementById('btn-batal');
        const modalTitle = document.getElementById('modal-title');

        // Fungsi untuk menampilkan modal
        const bukaModal = (mode = 'tambah', data = null) => {
            // Reset form (jika perlu)
            document.getElementById('form-produk').reset();
            document.getElementById('produk-id').value = '';

            if (mode === 'edit') {
                modalTitle.textContent = 'Edit Produk';
                // TODO: Isi form dengan 'data'
                // Contoh: document.getElementById('nama_produk').value = data.nama;
            } else {
                modalTitle.textContent = 'Tambah Produk Baru';
            }
            modal.classList.remove('hidden');
        };

        // Fungsi untuk menutup modal
        const tutupModal = () => {
            modal.classList.add('hidden');
        };

        // Event listener untuk tombol Tambah
        btnTambah.addEventListener('click', () => bukaModal('tambah'));

        // Event listener untuk tombol Edit (perlu delegasi event)
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', () => {
                // TODO: Ambil data produk dari baris tabel
                const data = { /* data dummy */ };
                bukaModal('edit', data);
            });
        });

        // Event listener untuk tombol Hapus (Sesuai wireframe [cite: 91])
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', () => {
                // Konfirmasi JS sebelum hapus
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    // TODO: Logika hapus (submit form hapus, dll)
                    console.log('Hapus produk');
                }
            });
        });

        // Event listener untuk tombol tutup dan batal
        btnTutupModal.addEventListener('click', tutupModal);
        btnBatal.addEventListener('click', tutupModal);

        // Tutup modal jika klik di luar area modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                tutupModal();
            }
        });

    });
</script>
@endpush
