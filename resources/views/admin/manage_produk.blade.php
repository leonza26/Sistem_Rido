@extends('admin.layouts.layout')

@section('title', 'Manajemen Produk - Toserba Hasan')
@section('page-title', 'Manajemen Produk')

@section('content')

    <div class="fixed top-10 inset-x-0 z-[100] flex flex-col items-center pointer-events-none space-y-4">

        @if ($errors->any())
            <div id="toast-error"
                class="pointer-events-auto min-w-[320px] max-w-md bg-white/80 backdrop-blur-lg border border-red-200 shadow-2xl rounded-2xl overflow-hidden transform transition-all duration-500">
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
                    <button onclick="closeToast('toast-error')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>
                <div class="h-1 bg-red-100 w-full">
                    <div id="error-progress" class="h-1 bg-red-500 transition-all linear duration-[5000ms] w-full"></div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div id="toast-success"
                class="pointer-events-auto min-w-[320px] max-w-md bg-white/80 backdrop-blur-lg border border-emerald-200 shadow-2xl rounded-2xl overflow-hidden transform transition-all duration-500">
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
                    <div id="success-progress" class="h-1 bg-emerald-500 transition-all linear duration-[5000ms] w-full">
                    </div>
                </div>
            </div>
        @endif
    </div>

   <livewire:product-search />

    <!-- Modal Tambah/Edit Produk -->
    <div id="modal-produk" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg">

            <!-- Header Modal -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-semibold text-slate-800">Tambah Produk Baru</h3>
                <button id="btn-tutup-modal" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <!-- Form di dalam Modal -->
            <form id="form-produk" action="{{ route('admin.produk.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="produk-id" name="produk_id">

                <div class="space-y-4">
                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-slate-700">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-slate-700">Foto Produk</label>
                        <input type="file" name="foto_produk" class="form-control border rounded w-full py-2 px-3">

                        <div id="file-info" class="mt-2 text-xs text-gray-500 hidden italic">
                            <span class="text-blue-600 font-semibold">Catatan:</span> Sudah ada gambar tersimpan. Biarkan
                            kosong jika tidak ingin mengubahnya.
                            <div id="preview-container" class="mt-1">
                                <img id="old-photo-preview" src=""
                                    class="h-10 w-10 object-cover rounded border shadow-sm hidden">
                            </div>
                        </div>

                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-slate-700">Kategori</label>
                        <select id="kategori" name="kategori_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- harga modal hanya dapat dilihat dan diisi oleh Pemilik --}}
                    @if (Auth::user()->isPemilik())
                        <div>
                            <label for="harga_modal" class="block text-sm font-medium text-slate-700">Harga Modal (Harga Beli/Dasar)</label>
                            <input type="number" id="harga_modal" name="harga_modal"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required min="0">
                        </div>
                    @else
                        <p class="text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-md px-3 py-2">
                            Harga modal diisi oleh Pemilik.
                        </p>
                    @endif
                    <div>
                        <label for="harga" class="block text-sm font-medium text-slate-700">Harga Jual</label>
                        <input type="number" id="harga" name="harga"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required min="0">
                    </div>
                    <div>
                        <label for="stok_awal" class="block text-sm font-medium text-slate-700">Stok Awal</label>
                        <input type="number" id="stok_awal" name="stok_awal"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Footer Modal [cite: 87] -->
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

    <!-- Modal Hapus Produk -->
    <div id="modal-hapus-produk" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">

            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-800">Hapus Produk</h3>
                    <p class="mt-2 text-sm text-slate-600">
                        Apakah Anda yakin ingin menghapus
                        <span id="nama-produk-hapus" class="font-semibold text-slate-800"></span>?
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Data produk yang sudah dihapus tidak bisa dikembalikan.</p>
                </div>
            </div>

            <form id="form-hapus-produk" method="POST" class="mt-6 flex justify-end space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" id="btn-batal-hapus"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Import Produk -->
    <div id="modal-import-produk" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">

            <!-- Header Modal -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Import Produk dari Excel</h3>
                <button id="btn-tutup-modal-import" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <form action="{{ route('admin.produk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="file_excel" class="block text-sm font-medium text-slate-700">File Excel (.xlsx)</label>
                        <input type="file" id="file_excel" name="file_excel" accept=".xlsx" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <p class="text-xs text-gray-500 mt-2">Pastikan format file sesuai dengan template. Gambar harus berada persis di dalam cell kolom "Foto Produk".</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="btn-batal-import"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Menjalankan script setelah dokumen siap
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('modal-produk');
            const formProduk = document.getElementById('form-produk');
            const btnTutupModal = document.getElementById('btn-tutup-modal');
            const btnBatal = document.getElementById('btn-batal');
            const modalTitle = document.getElementById('modal-title');
            const modalHapus = document.getElementById('modal-hapus-produk');
            const formHapusProduk = document.getElementById('form-hapus-produk');
            const btnBatalHapus = document.getElementById('btn-batal-hapus');
            const namaProdukHapus = document.getElementById('nama-produk-hapus');

            const modalImport = document.getElementById('modal-import-produk');
            const btnTutupModalImport = document.getElementById('btn-tutup-modal-import');
            const btnBatalImport = document.getElementById('btn-batal-import');


            // Fungsi untuk menampilkan modal
            const bukaModal = (mode = 'tambah', data = null) => {
                // Reset form (jika perlu)
                formProduk.reset();
                const existingMethod = formProduk.querySelector('input[name="_method"]');
                if (existingMethod) existingMethod.remove();

                if (mode === 'edit' && data) {
                    modalTitle.textContent = 'Edit Produk';
                    formProduk.action = `/admin/produk/${data.id}`;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    formProduk.appendChild(methodInput);

                    if (document.getElementById('produk-id')) document.getElementById('produk-id').value = data
                        .id;
                    if (document.getElementById('nama_produk')) document.getElementById('nama_produk').value =
                        data.nama;
                    if (document.getElementById('kategori')) document.getElementById('kategori').value = data
                        .kategori_id;
                    if (document.getElementById('harga_modal')) document.getElementById('harga_modal').value = data
                        .harga_modal ?? 0;
                    if (document.getElementById('harga')) document.getElementById('harga').value = data.harga;
                    if (document.getElementById('stok_awal')) document.getElementById('stok_awal').value = data
                        .stok;
                    const fileInfo = document.getElementById('file-info');
                    const photoPreview = document.getElementById('old-photo-preview');

                    if (fileInfo && photoPreview) {
                        // Jika ada data foto (tidak null dan tidak kosong)
                        if (data.foto && data.foto !== '') {
                            fileInfo.classList.remove('hidden');

                            // Pastikan path image sesuai dengan folder storage Anda
                            photoPreview.src = `/storage/${data.foto}`;
                            photoPreview.classList.remove('hidden');
                        } else {
                            // Jika tidak ada foto, pastikan info ini tersembunyi
                            fileInfo.classList.add('hidden');
                            photoPreview.classList.add('hidden');
                        }
                    }

                } else {
                    modalTitle.textContent = 'Tambah Produk Baru';
                    // Kembalikan action ke route STORE
                    formProduk.action = "{{ route('admin.produk.store') }}";
                    document.getElementById('produk-id').value = '';
                }
                modal.classList.remove('hidden');
            };

            // Fungsi untuk menutup modal
            const tutupModal = () => {
                modal.classList.add('hidden');
            };

            const bukaModalHapus = (data) => {
                formHapusProduk.action = data.action;
                namaProdukHapus.textContent = `"${data.nama}"`;
                modalHapus.classList.remove('hidden');
            };

            const tutupModalHapus = () => {
                modalHapus.classList.add('hidden');
                formHapusProduk.action = '';
                namaProdukHapus.textContent = '';
            };

            // Event listener tombol di dalam komponen Livewire memakai delegasi
            // agar tetap aktif setelah Livewire me-render ulang DOM.
            document.addEventListener('click', function(e) {
                const btnTambah = e.target.closest('#btn-tambah-produk');
                if (btnTambah) {
                    bukaModal('tambah');
                    return;
                }

                const btnImport = e.target.closest('#btn-import-produk');
                if (btnImport) {
                    modalImport.classList.remove('hidden');
                    return;
                }

                // Cari apakah yang diklik adalah tombol edit atau icon di dalam tombol edit
                const btnEdit = e.target.closest('.btn-edit');
                if (btnEdit) {
                    const data = {
                        id: btnEdit.getAttribute('data-id'),
                        nama: btnEdit.getAttribute('data-nama'),
                        kategori_id: btnEdit.getAttribute('data-kategori'),
                        harga_modal: btnEdit.getAttribute('data-harga-modal') || 0,
                        harga: btnEdit.getAttribute('data-harga'),
                        stok: btnEdit.getAttribute('data-stok'),
                        foto: btnEdit.getAttribute('data-foto')
                    };
                    bukaModal('edit', data);
                    return;
                }

                const btnHapus = e.target.closest('.btn-hapus');
                if (btnHapus) {
                    bukaModalHapus({
                        nama: btnHapus.getAttribute('data-nama'),
                        action: btnHapus.getAttribute('data-action')
                    });
                }
            });

            // Event listener untuk tombol tutup dan batal
            btnTutupModal.addEventListener('click', tutupModal);
            btnBatal.addEventListener('click', tutupModal);
            btnBatalHapus.addEventListener('click', tutupModalHapus);

            btnTutupModalImport.addEventListener('click', () => modalImport.classList.add('hidden'));
            btnBatalImport.addEventListener('click', () => modalImport.classList.add('hidden'));

            // Tutup modal jika klik di luar area modal
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    tutupModal();
                }
            });

            modalHapus.addEventListener('click', (e) => {
                if (e.target === modalHapus) {
                    tutupModalHapus();
                }
            });

            modalImport.addEventListener('click', (e) => {
                if (e.target === modalImport) {
                    modalImport.classList.add('hidden');
                }
            });

            const errorToast = document.getElementById('toast-container');
            if (errorToast) {
                setTimeout(() => {
                    errorToast.style.opacity = '0';
                    errorToast.style.transform = 'translateX(20px)';
                    setTimeout(() => errorToast.remove(), 500);
                }, 5000);
            }

            // Auto hide success toast
            const successToast = document.getElementById('toast-success');
            if (successToast) {
                setTimeout(() => {
                    successToast.style.opacity = '0';
                    successToast.style.transform = 'translateX(20px)';
                    setTimeout(() => successToast.remove(), 500);
                }, 5000);
            }
        });
    </script>
@endpush
