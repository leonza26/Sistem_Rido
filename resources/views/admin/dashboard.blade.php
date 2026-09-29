@extends('admin.layouts.layout')

@section('title', 'Dashboard Pemilik - Toserba Hasan')
@section('page-title', 'Dashboard Pemilik')

@section('content')

<!-- Grid Finansial & Kas Hari Ini -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

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
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-xs {{ $revenueChange > 0 ? 'text-green-500' : 'text-red-500' }} mt-1">{{ $revenueChange >= 0 ? '↑' : '↓' }} {{ abs(round($revenueChange, 1)) }}% dari kemarin</p>
        </div>
    </div>

    <!-- Card 2: Penjualan Tunai -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Penjualan Tunai</h3>
            <span class="p-2 bg-emerald-100 rounded-lg">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </span>
        </div>
        <div>
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($todayCash, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">Uang tunai masuk hari ini</p>
        </div>
    </div>

    <!-- Card 3: Penjualan Non-Tunai -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Penjualan Non-Tunai</h3>
            <span class="p-2 bg-violet-100 rounded-lg">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </span>
        </div>
        <div>
            <p class="text-2xl font-bold text-violet-600">Rp {{ number_format($todayNonCash, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">Pembayaran QRIS</p>
        </div>
    </div>

    <!-- Card 4: Uang Fisik Kasir (Kas Saat Ini) -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-xl text-white shadow-md transition-shadow duration-300">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-blue-100">Kas Fisik Kasir (Saat Ini)</h3>
            <span class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </span>
        </div>
        <div>
            <p class="text-2xl font-bold">Rp {{ number_format($todayKasKasir, 0, ',', '.') }}</p>
            <p class="text-xs text-blue-200 mt-1">Modal Awal (Rp {{ number_format($todayModalKasir, 0, ',', '.') }}) + Tunai</p>
        </div>
    </div>

</div>

<!-- Grid Operasional & Stok -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

    <!-- Card: Modal Awal Kasir Hari Ini -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Modal Awal Kasir</h3>
            <a href="{{ route('admin.modal_kasir') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 underline">Atur Modal →</a>
        </div>
        <div>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($todayModalKasir, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">Modal kembalian kasir hari ini</p>
        </div>
    </div>

    <!-- Card: Total Barang Terjual -->
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
            <p class="text-2xl font-bold text-slate-800">{{ $todayQty ?? 0 }} Pcs</p>
            <p class="text-xs {{ $todayQty > 0 ? 'text-slate-500' : 'text-red-500' }} mt-1">
                @if(($todayQty ?? 0) > 0)
                Total hari ini
                @else
                Tidak ada penjualan hari ini
                @endif
            </p>
        </div>
    </div>

    <!-- Card: Barang Terlaris -->
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
            @if($mostSoldProduct && $mostSoldProduct->produk)
            <p class="text-2xl font-bold text-slate-800 truncate" title="{{ $mostSoldProduct->produk->nama_produk }}">{{ $mostSoldProduct->produk->nama_produk }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ $mostSoldProduct->total_qty }} Pcs terjual</p>
            @else
            <p class="text-2xl font-bold text-slate-800">-</p>
            <p class="text-xs text-red-500 mt-1 font-medium italic">Belum ada penjualan!</p>
            @endif
        </div>
    </div>

    <!-- Card: Stok Hampir Habis -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm transition-shadow duration-300">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Stok Hampir Habis</h3>

            @if($lowStockProducts > 0)
            <span class="p-2 bg-red-100 rounded-lg">
                <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </span>
            @else
            <span class="p-2 bg-green-100 rounded-lg">
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </span>
            @endif
        </div>
        <div>
            @if($lowStockProducts > 0)
            <p class="text-2xl font-bold text-red-600">{{ $lowStockProducts }} Produk</p>
            <p class="text-xs text-red-500 mt-1 font-medium italic">Perlu restock segera</p>
            @else
            <p class="text-2xl font-bold text-slate-800">Aman</p>
            <p class="text-xs text-green-500 mt-1">Semua stok di atas 10 pcs</p>
            @endif
        </div>
    </div>

</div>

<!-- Tombol Aksi (Dibuat lebih modern) -->
<div class="mt-8">
    <a href="{{ route('admin.laporan') }}"
        class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Lihat Detail Laporan
    </a>
</div>

<div class="mt-8 bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
    <h3 class="text-lg font-bold text-slate-800 mb-6">Grafik Penjualan Mingguan</h3>

    <div class="h-80 rounded-[2rem] bg-slate-50 p-6 pt-10 relative overflow-visible">

        <div class="absolute top-10 left-4 flex flex-col justify-between pr-4 text-right text-[10px] font-bold text-slate-400"
            style="height: 180px; z-index: 10;">
            <span>Rp {{ number_format($yAxisMax, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($yAxisMax / 2, 0, ',', '.') }}</span>
            <span></span>
        </div>

        <div class="absolute bottom-14 left-16 right-6 border-t border-slate-200 border-dashed" style="z-index: 5;"></div>

        <div class="flex items-end h-[200px] space-x-6 justify-center pl-16 relative" style="z-index: 20;">
            @foreach($salesData as $data)
            @php
            // Logic: (Current Revenue / Max Revenue) * 100
            $percentage = ($yAxisMax > 0) ? ($data['revenue'] / $yAxisMax) * 100 : 0;

            // Force a minimum visual height if there is data
            $h = $data['revenue'] > 0 ? max($percentage, 5) : 0;
            @endphp

            <div class="flex flex-col items-center flex-1 max-w-[50px] group relative h-full justify-end">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-all whitespace-nowrap z-50">
                    {{ $data['formatted'] }}
                </div>

                <div class="w-full transition-all duration-300 ease-out rounded-t-lg bg-blue-200 group-hover:bg-blue-600 group-hover:shadow-[0_0_15px_rgba(37,99,235,0.4)] group-hover:scale-x-105"
                    style="height:{{ $h }}%;min-width:30px">
                </div>

                <span class="text-[10px] font-bold mt-4 transition-colors duration-300 text-slate-400 group-hover:text-blue-600">
                    {{ $data['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection