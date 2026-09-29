 <!-- Kontainer Utama -->
 <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

     <div class="flex justify-between items-center mb-6">
         <h2 class="text-lg font-semibold text-slate-700">Daftar Produk</h2>

         <div class="flex items-center gap-3">
             {{-- search --}}
             <div class="relative w-64">
                 <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                     </svg>
                 </span>

                 <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
                     class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all outline-none text-sm">
             </div>

             {{-- download template --}}
             <a href="{{ route('admin.produk.download_template') }}"
                 class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-300 inline-flex items-center space-x-2">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                 </svg>
                 <span>Template</span>
             </a>

             {{-- import excel --}}
             <button id="btn-import-produk"
                 class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-300 inline-flex items-center space-x-2">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                 </svg>
                 <span>Import Excel</span>
             </button>

             {{-- tambah produk --}}
             <button id="btn-tambah-produk"
                 class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 inline-flex items-center space-x-2">
                 <!-- Icon Plus -->
                 <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                 </svg>
                 <span>Tambah Produk</span>
             </button>
         </div>
     </div>

     <!-- Tabel Responsif -->
     <div class="overflow-x-auto">
         <table class="min-w-full divide-y divide-gray-200">
             <thead class="bg-gray-50">
                 <tr>
                     <th scope="col"
                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                         Produk</th>
                     <th scope="col"
                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                     </th>
                     @if ($tampilkanHargaModal)
                         <th scope="col"
                             class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Modal
                         </th>
                     @endif
                     <th scope="col"
                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual
                     </th>
                     <th scope="col"
                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok
                     </th>
                     <th scope="col"
                         class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                     </th>
                 </tr>
             </thead>
             <tbody class="bg-white divide-y divide-gray-200">
                 @forelse($products as $product)
                     <tr wire:key="product-{{ $product->id }}">
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->nama_produk }}</td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                             {{ $product->kategori->nama_kategori ?? 'Tanpa Kategori' }}</td>
                         @if ($tampilkanHargaModal)
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                                 @if (($product->harga_modal ?? 0) > 0)
                                     Rp {{ number_format($product->harga_modal, 0, ',', '.') }}
                                 @else
                                     <span
                                         class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Belum
                                         diisi</span>
                                 @endif
                             </td>
                         @endif
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">Rp
                             {{ number_format($product->harga, 0, ',', '.') }}</td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stok_awal }}</td>
                         <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                             <button class="btn-edit text-indigo-600 hover:text-indigo-900"
                                 data-id="{{ $product->id }}" data-nama="{{ $product->nama_produk }}"
                                 data-kategori="{{ $product->kategori_id }}"
                                 @if ($tampilkanHargaModal) data-harga-modal="{{ $product->harga_modal ?? 0 }}" @endif
                                 data-harga="{{ $product->harga }}"
                                 data-stok="{{ $product->stok_awal }}" data-foto="{{ $product->foto_produk }}">
                                 Edit
                             </button>
                             <button type="button" class="btn-hapus text-red-600 hover:text-red-900"
                                 data-nama="{{ $product->nama_produk }}"
                                 data-action="{{ route('admin.produk.destroy', $product->id) }}">
                                 Hapus
                             </button>
                         </td>
                     </tr>
                 @empty
                     <tr>
                         <td colspan="{{ $tampilkanHargaModal ? 6 : 5 }}" class="px-6 py-12 text-center bg-gray-50">
                             <div class="flex flex-col items-center justify-center">
                                 <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                 </svg>
                                 <span class="text-gray-500 font-medium text-lg">Belum ada produk di toko ini.</span>
                                 <p class="text-gray-400 text-sm">Silahkan tambahkan produk baru melalui form di atas.
                                 </p>
                             </div>
                         </td>
                     </tr>
                 @endforelse
             </tbody>
         </table>
         <div class="my-5">
             {{ $products->links() }}
         </div>

     </div>

 </div>
