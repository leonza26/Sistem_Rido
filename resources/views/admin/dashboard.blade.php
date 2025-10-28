@extends('admin.layouts.layout')

@section('title', 'Dashboard Pemilik - Toserba Hasan')
@section('page-title', 'Dashboard Pemilik')

@section('content')

    <!-- Grid untuk Statistik Card (Revisi Desain) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Total Penjualan Hari Ini -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Total Penjualan Hari Ini</h3>
                <span class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <!-- Font lebih kecil dan seimbang -->
                <p class="text-2xl font-bold text-slate-800">Rp 1.250.000</p>
                <p class="text-xs text-green-500 mt-1">+15% dari kemarin</p>
            </div>
        </div>

        <!-- Card 2: Total Barang Terjual -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Total Barang Terjual</h3>
                <span class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">120 Pcs</p>
                <p class="text-xs text-slate-500 mt-1">Total hari ini</p>
            </div>
        </div>

        <!-- Card 3: Barang Terlaris -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Barang Terlaris</h3>
                <span class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.31h5.364c.518 0 .734.66.331.97l-4.364 3.178a.563.563 0 00-.18.636l1.64 5.034a.563.563 0 01-.84.62l-4.434-3.217a.563.563 0 00-.65 0l-4.434 3.217a.563.563 0 01-.84-.62l1.64-5.034a.563.563 0 00-.18.636l-4.364-3.178a.563.563 0 01.331-.97h5.364a.563.563 0 00.475-.31l2.125-5.111z" />
                    </svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">Indomie Goreng</p>
                <p class="text-xs text-slate-500 mt-1">30 Pcs terjual</p>
            </div>
        </div>

        <!-- Card 4: Stok Hampir Habis -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Stok Hampir Habis</h3>
                <span class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">5 Produk</p>
                <p class="text-xs text-red-500 mt-1">Segera restock!</p>
            </div>
        </div>

    </div>

    <!-- Tombol Aksi (Dibuat lebih modern) -->
    <div class="mt-8">
        <a href="#"
            class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Lihat Detail Laporan
        </a>
    </div>

    <!-- Konten Tambahan (Chart Dummy - Revisi 2) -->
    <div class="mt-8 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-700 mb-4">Grafik Penjualan Mingguan</h3>

        <!-- Wrapper baru untuk chart dummy dengan Y-Axis -->
        <div class="h-72 rounded-lg bg-slate-50 p-4 pt-8 relative">

            <!-- Y-Axis Labels (Absolute positioned) -->
            <!-- Label-label ini diposisikan absolut di sebelah kiri -->
            <div class="absolute top-6 left-0 flex flex-col justify-between pr-4 text-right text-xs text-slate-400"
                style="height: calc(100% - 5rem);">
                <span>Rp 3jt</span>
                <span>Rp 2jt</span>
                <span>Rp 1jt</span>
                <span>0</span>
            </div>

            <!-- X-Axis Line (Absolute positioned) -->
            <!-- Ini adalah garis putus-putus horizontal sebagai sumbu X -->
            <div class="absolute bottom-10 left-12 right-4 border-t border-slate-300 border-dashed"></div>

            <!-- Container untuk bar chart (diposisikan di dalam, dengan padding kiri untuk Y-axis) -->
            <!-- style="height: calc(100% - 2.5rem);" menyisakan ruang 2.5rem di bawah untuk label X-axis (Sen, Sel, dst.) -->
            <div class="flex items-end h-full space-x-4 justify-center pl-12" title="Grafik Penjualan 7 Hari Terakhir"
                style="height: calc(100% - 2.5rem);">


                <!-- Bar 1 (Senin) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-300 rounded-t-md hover:bg-blue-400 transition-colors" style="height: 40%"
                        title="Rp 1.2jt"></div>
                    <span class="text-xs text-slate-500 mt-2">Sen</span>
                </div>
                <!-- Bar 2 (Selasa) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-500 rounded-t-md hover:bg-blue-600 transition-colors" style="height: 60%"
                        title="Rp 1.8jt"></div>
                    <span class="text-xs text-slate-500 mt-2">Sel</span>
                </div>
                <!-- Bar 3 (Rabu) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-300 rounded-t-md hover:bg-blue-400 transition-colors" style="height: 50%"
                        title="Rp 1.5jt"></div>
                    <span class="text-xs text-slate-500 mt-2">Rab</span>
                </div>
                <!-- Bar 4 (Kamis) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-500 rounded-t-md hover:bg-blue-600 transition-colors" style="height: 75%"
                        title="Rp 2.1jt"></div>
                    <span class="text-xs text-slate-500 mt-2">Kam</span>
                </div>
                <!-- Bar 5 (Jumat) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-300 rounded-t-md hover:bg-blue-400 transition-colors" style="height: 65%"
                        title="Rp 1.9jt"></div>
                    <span class="text-xs text-slate-500 mt-2">Jum</span>
                </div>
                <!-- Bar 6 (Sabtu) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-300 rounded-t-md hover:bg-blue-400 transition-colors" style="height: 30%"
                        title="Rp 900rb"></div>
                    <span class="text-xs text-slate-500 mt-2">Sab</span>
                </div>
                <!-- Bar 7 (Minggu) -->
                <div class="flex flex-col items-center flex-1 max-w-[50px]">
                    <div class="w-full bg-blue-500 rounded-t-md hover:bg-blue-600 transition-colors" style="height: 85%"
                        title="Rp 2.4jt"></div>
                    <span class="text-xs text-slate-500 mt-2">Min</span>
                </div>

            </div>
        </div>
    </div>

@endsection
