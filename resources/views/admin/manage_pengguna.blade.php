@extends('admin.layouts.layout')

@section('title', 'Manajemen Pengguna - Toserba Hasan')
@section('page-title', 'Manajemen Pengguna')

@section('content')

    <!-- Kontainer Utama -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

        <!-- Header Card dan Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-slate-700">Daftar Pengguna</h2>
            <!-- Tombol untuk memicu modal tambah -->
            <button id="btn-tambah-pengguna" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 inline-flex items-center space-x-2">
                <!-- Icon Plus -->
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        <!-- Tabel Responsif -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Header Tabel -->
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <!-- Data Dummy Sesuai Wireframe [cite: 118] -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dita</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">dita01</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                              Kasir
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button class="btn-edit text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button class="btn-hapus text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>

                    <!-- Data Dummy 2 (Pemilik) -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Hasan (Pemilik)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">admin_hasan</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                              Pemilik
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button class="btn-edit text-indigo-600 hover:text-indigo-900">Edit</button>
                            <!-- Tombol hapus non-aktif sesuai wireframe [cite: 121] -->
                            <button class="btn-hapus text-gray-300 cursor-not-allowed" disabled>Hapus</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    <!-- Akhir Kontainer Utama -->


    <!-- Modal Tambah/Edit Pengguna -->
    <div id="modal-pengguna" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg">

            <!-- Header Modal -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-semibold text-slate-800">Tambah Pengguna Baru</h3>
                <button id="btn-tutup-modal" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <!-- Form di dalam Modal -->
            <form id="form-pengguna" action="#" method="POST">
                @csrf
                <input type="hidden" id="pengguna-id" name="pengguna_id">

                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" id="nama" name="nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                        <input type="text" id="username" name="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
                        <select id="role" name="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="kasir">Kasir</option>
                            <option value="pemilik">Pemilik</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                    </div>

                    <div>
                        <label for="konfirmasi_password" class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                    </div>

                </div>

                <!-- Footer Modal -->
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
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('modal-pengguna');
        const btnTambah = document.getElementById('btn-tambah-pengguna');
        const btnTutupModal = document.getElementById('btn-tutup-modal');
        const btnBatal = document.getElementById('btn-batal');
        const modalTitle = document.getElementById('modal-title');
        const passwordInput = document.getElementById('password');

        // Fungsi untuk menampilkan modal
        const bukaModal = (mode = 'tambah', data = null) => {
            document.getElementById('form-pengguna').reset();
            document.getElementById('pengguna-id').value = '';

            if (mode === 'edit') {
                modalTitle.textContent = 'Edit Pengguna';
                passwordInput.placeholder = 'Kosongkan jika tidak ingin mengubah';
                // TODO: Isi form dengan data
                // document.getElementById('nama').value = data.nama;
                // document.getElementById('username').value = data.username;
                // document.getElementById('role').value = data.role;
            } else {
                modalTitle.textContent = 'Tambah Pengguna Baru';
                passwordInput.placeholder = 'Minimal 6 karakter';
            }
            modal.classList.remove('hidden');
        };

        // Fungsi untuk menutup modal
        const tutupModal = () => {
            modal.classList.add('hidden');
        };

        btnTambah.addEventListener('click', () => bukaModal('tambah'));

        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', () => {
                // TODO: Ambil data pengguna dari baris tabel
                const data = { /* data dummy */ };
                bukaModal('edit', data);
            });
        });

        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', () => {
                if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                    // TODO: Logika hapus
                    console.log('Hapus pengguna');
                }
            });
        });

        btnTutupModal.addEventListener('click', tutupModal);
        btnBatal.addEventListener('click', tutupModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                tutupModal();
            }
        });

    });
</script>
@endpush
