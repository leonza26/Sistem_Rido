@extends('admin.layouts.layout')

@section('title', 'Manajemen Pengguna - Toserba Hasan')
@section('page-title', 'Manajemen Pengguna')

@section('content')

    <!-- Kontainer Utama -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

        <!-- Toast Container -->
        <div class="fixed top-5 right-5 z-[60] flex flex-col gap-4">
            @if ($errors->any())
                <div id="toast-error"
                    class="pointer-events-auto w-full max-w-sm bg-white/80 backdrop-blur-lg border border-red-200 shadow-2xl rounded-2xl overflow-hidden transform transition-all duration-500">
                    <div class="p-4 flex items-start space-x-4">
                        <div class="flex-shrink-0 bg-red-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-gray-900 font-bold text-sm">Terjadi Kesalahan</h3>
                            <ul class="text-xs text-gray-600 mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="closeToast('toast-error')"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>
                    <div class="h-1 bg-red-100 w-full">
                        <div id="error-progress" class="h-1 bg-red-500 transition-all linear duration-[5000ms] w-full">
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div id="toast-success"
                    class="pointer-events-auto w-full max-w-sm bg-white/80 backdrop-blur-lg border border-emerald-200 shadow-2xl rounded-2xl overflow-hidden transform transition-all duration-500">
                    <div class="p-4 flex items-center space-x-4">
                        <div class="flex-shrink-0 bg-emerald-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-gray-900 font-bold text-sm">Berhasil!</h3>
                            <p class="text-xs text-gray-600">{{ session('success') }}</p>
                        </div>
                        <button onclick="closeToast('toast-success')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>
                    <div class="h-1 bg-emerald-100 w-full">
                        <div id="success-progress"
                            class="h-1 bg-emerald-500 transition-all linear duration-[5000ms] w-full">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Header Card dan Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-slate-700">Daftar Pengguna</h2>
            <!-- Tombol untuk memicu modal tambah -->
            <button id="btn-tambah-pengguna"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 inline-flex items-center space-x-2">
                <!-- Icon Plus -->
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
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
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $warnaRole = [
                            \App\Models\User::ROLE_PEMILIK => 'bg-indigo-100 text-indigo-700',
                            \App\Models\User::ROLE_ADMIN => 'bg-blue-100 text-blue-800',
                            \App\Models\User::ROLE_KASIR => 'bg-green-100 text-green-800',
                        ];
                    @endphp
                    @foreach ($pengguna as $users)
                        @php
                            $akunSendiri = $users->id === auth()->id();
                            // Admin hanya dapat mereset password akun Pemilik (tidak dapat mengubah/menghapus)
                            $hanyaResetPassword = $users->isPemilik() && ! auth()->user()->isPemilik();
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $users->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $users->name }}
                                @if ($akunSendiri)
                                    <span class="text-xs text-slate-400">(Anda)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $users->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $warnaRole[$users->role] ?? 'bg-gray-100 text-gray-400' }}">
                                    {{ $users->roleLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button
                                    class="btn-edit btn-edit px-3 py-1.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition duration-200"
                                    data-id="{{ $users->id }}" data-nama="{{ $users->name }}"
                                    data-email="{{ $users->email }}" data-role="{{ $users->role }}"
                                    data-mode="{{ $hanyaResetPassword ? 'reset' : 'edit' }}"
                                    data-sendiri="{{ $akunSendiri ? '1' : '0' }}">
                                    {{ $hanyaResetPassword ? 'Reset Password' : 'Edit' }}
                                </button>
                                @unless ($akunSendiri || $hanyaResetPassword)
                                    <button
                                        class="btn-hapus btn-hapus px-3 py-1.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition duration-200"
                                        data-id="{{ $users->id }}">
                                        Hapus
                                    </button>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
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
                <input type="hidden" name="_method" id="method-field">
                <input type="hidden" name="id" id="pengguna-id">
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" id="nama" name="nama"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" id="email" name="email"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div id="role-wrapper">
                        <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
                        {{-- Pemilik dapat memberikan role Pemilik/Admin/Kasir, Admin hanya Admin/Kasir --}}
                        <select id="role" name="role"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($roleOptions as $kodeRole => $labelRole)
                                <option value="{{ $kodeRole }}" @selected($kodeRole === \App\Models\User::ROLE_KASIR)>
                                    {{ $labelRole }}
                                </option>
                            @endforeach
                        </select>
                        <p id="role-info" class="text-xs text-gray-500 mt-1 hidden">Role akun Anda sendiri tidak dapat
                            diubah.</p>
                    </div>
                    <p id="info-reset"
                        class="hidden text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-md px-3 py-2">
                        Sebagai Admin, Anda hanya dapat mengganti password akun Pemilik, misalnya saat Pemilik lupa
                        password.
                    </p>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" id="password" name="password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                    </div>

                    <div>
                        <label for="konfirmasi_password" class="block text-sm font-medium text-slate-700">Konfirmasi
                            Password</label>
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="btn-batal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Pengguna -->
    <div id="modal-hapus" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-sm text-center">
            <svg class="mx-auto mb-4 text-red-500 w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="mb-5 text-lg font-normal text-slate-700">Apakah Anda yakin ingin menghapus pengguna ini?</h3>

            <!-- Form di dalam Modal Hapus -->
            <form id="form-hapus-pengguna" action="#" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="hapus-pengguna-id" name="pengguna_id">

                <div class="flex justify-center space-x-3">
                    <button type="button" id="btn-batal-hapus"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-300">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ===== MODAL TAMBAH / EDIT =====
            const modal = document.getElementById('modal-pengguna');
            const form = document.getElementById('form-pengguna');
            const methodField = document.getElementById('method-field');

            const btnTambah = document.getElementById('btn-tambah-pengguna');
            const btnTutupModal = document.getElementById('btn-tutup-modal');
            const btnBatal = document.getElementById('btn-batal');

            const modalTitle = document.getElementById('modal-title');
            const passwordInput = document.getElementById('password');

            const inputNama = document.getElementById('nama');
            const inputEmail = document.getElementById('email');
            const selectRole = document.getElementById('role');
            const roleWrapper = document.getElementById('role-wrapper');
            const roleInfo = document.getElementById('role-info');
            const infoReset = document.getElementById('info-reset');

            const konfirmasiInput = document.getElementById('konfirmasi_password');
            const placeholderKonfirmasi = konfirmasiInput.placeholder;

            // mengunci/membuka field agar tidak dapat diubah
            const aturKunci = (input, terkunci) => {
                input.readOnly = terkunci;
                input.classList.toggle('bg-gray-100', terkunci);
                input.classList.toggle('cursor-not-allowed', terkunci);
            };

            const aturRoleNonaktif = (nonaktif) => {
                selectRole.disabled = nonaktif;
                selectRole.classList.toggle('bg-gray-100', nonaktif);
                selectRole.classList.toggle('cursor-not-allowed', nonaktif);
            };

            // ===== MODAL HAPUS =====
            const modalHapus = document.getElementById('modal-hapus');
            const formHapus = document.getElementById('form-hapus-pengguna');
            const btnBatalHapus = document.getElementById('btn-batal-hapus');
            const hapusPenggunaId = document.getElementById('hapus-pengguna-id');

            const bukaModal = (mode = 'tambah', data = null) => {
                form.reset();

                // kondisi awal: semua field dapat diisi
                aturKunci(inputNama, false);
                aturKunci(inputEmail, false);
                aturRoleNonaktif(false);
                roleWrapper.classList.remove('hidden');
                roleInfo.classList.add('hidden');
                infoReset.classList.add('hidden');
                passwordInput.required = false;
                konfirmasiInput.required = false;
                konfirmasiInput.placeholder = placeholderKonfirmasi;

                if (mode === 'edit' || mode === 'reset') {
                    // isi data
                    document.getElementById('pengguna-id').value = data.id;
                    inputNama.value = data.nama;
                    inputEmail.value = data.email;
                    selectRole.value = data.role;

                    // set action update
                    form.action = `/admin/users/${data.id}`;
                    methodField.value = 'PUT';

                    if (mode === 'reset') {
                        // Admin hanya dapat mereset password akun Pemilik
                        modalTitle.textContent = 'Reset Password Pemilik';
                        passwordInput.placeholder = 'Password baru untuk Pemilik';
                        passwordInput.required = true;
                        konfirmasiInput.placeholder = 'Ulangi password baru';
                        konfirmasiInput.required = true;
                        aturKunci(inputNama, true);
                        aturKunci(inputEmail, true);
                        aturRoleNonaktif(true);
                        roleWrapper.classList.add('hidden');
                        infoReset.classList.remove('hidden');
                    } else {
                        modalTitle.textContent = 'Edit Pengguna';
                        passwordInput.placeholder = 'Kosongkan jika tidak ingin mengubah';

                        // role akun sendiri tidak dapat diubah
                        if (data.sendiri === '1') {
                            aturRoleNonaktif(true);
                            roleInfo.classList.remove('hidden');
                        }
                    }

                } else {
                    modalTitle.textContent = 'Tambah Pengguna Baru';
                    passwordInput.placeholder = 'Minimal 6 karakter';

                    form.action = `/admin/users`;
                    methodField.value = 'POST';
                }

                modal.classList.remove('hidden');
            };

            const tutupModal = () => {
                modal.classList.add('hidden');
            };

            const bukaModalHapus = (id) => {
                hapusPenggunaId.value = id;

                // set action delete
                formHapus.action = `/admin/users/${id}`;

                modalHapus.classList.remove('hidden');
            };

            const tutupModalHapus = () => {
                modalHapus.classList.add('hidden');
            };

            // tombol tambah
            btnTambah.addEventListener('click', () => bukaModal('tambah'));

            // tombol edit
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', () => {
                    const data = {
                        id: button.dataset.id,
                        nama: button.dataset.nama,
                        email: button.dataset.email,
                        role: button.dataset.role,
                        sendiri: button.dataset.sendiri,
                    };

                    bukaModal(button.dataset.mode || 'edit', data);
                });
            });

            // tombol hapus
            document.querySelectorAll('.btn-hapus').forEach(button => {
                button.addEventListener('click', () => {
                    const userId = button.dataset.id;
                    bukaModalHapus(userId);
                });
            });

            // tombol close modal
            btnTutupModal.addEventListener('click', tutupModal);
            btnBatal.addEventListener('click', tutupModal);

            btnBatalHapus.addEventListener('click', tutupModalHapus);

            // klik background
            modal.addEventListener('click', (e) => {
                if (e.target === modal) tutupModal();
            });

            modalHapus.addEventListener('click', (e) => {
                if (e.target === modalHapus) tutupModalHapus();
            });

            const errorToast = document.getElementById('toast-error');
            if (errorToast) {
                const progress = document.getElementById('error-progress');
                if (progress) {
                    setTimeout(() => { progress.style.width = '0%'; }, 50);
                }
                setTimeout(() => {
                    errorToast.style.opacity = '0';
                    errorToast.style.transform = 'translateX(20px)';
                    setTimeout(() => errorToast.remove(), 500);
                }, 5000);
            }

            // Auto hide success toast
            const successToast = document.getElementById('toast-success');
            if (successToast) {
                const progress = document.getElementById('success-progress');
                if (progress) {
                    setTimeout(() => { progress.style.width = '0%'; }, 50);
                }
                setTimeout(() => {
                    successToast.style.opacity = '0';
                    successToast.style.transform = 'translateX(20px)';
                    setTimeout(() => successToast.remove(), 500);
                }, 5000);
            }

        });

        // Fungsi untuk menutup toast secara manual
        window.closeToast = function(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                setTimeout(() => toast.remove(), 500);
            }
        };
    </script>
@endpush
