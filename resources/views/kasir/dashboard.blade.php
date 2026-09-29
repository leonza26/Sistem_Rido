@extends('kasir.layouts.layout')

@section('title', 'Dashboard Kasir - Toserba Hasan')
@section('page-title', 'Dashboard Kasir')

@section('content')

<div class="mb-8">
    <a href="{{ route('kasir.transaksi') }}" class="px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300 inline-flex items-center space-x-2">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Mulai Transaksi Baru</span>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card 1: Total Transaksi Hari Ini -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Total Penjualan Hari Ini</h3>
            <span class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 mt-1">Total seluruh omzet hari ini</p>
    </div>

    <!-- Card 2: Penjualan Tunai -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Penjualan Tunai</h3>
            <span class="p-2 bg-emerald-100 rounded-lg">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($todayCash, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 mt-1">Uang tunai masuk dari pelanggan</p>
    </div>

    <!-- Card 3: Penjualan Non-Tunai -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Penjualan Non-Tunai</h3>
            <span class="p-2 bg-violet-100 rounded-lg">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-violet-600">Rp {{ number_format($todayNonCash, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 mt-1">Pembayaran QRIS</p>
    </div>

    <!-- Card 4: Modal Awal Kasir -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Modal Awal Kasir</h3>
            <span class="p-2 bg-amber-100 rounded-lg">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($todayModalKasir, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 mt-1">Uang kembalian awal kasir</p>
    </div>

    <!-- Card 5: Uang Fisik Kasir (Kas Saat Ini) -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-xl text-white shadow-md">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-blue-100">Uang Fisik Kasir (Saat Ini)</h3>
            <span class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </span>
        </div>
        <p class="text-2xl font-bold">Rp {{ number_format($todayKasKasir, 0, ',', '.') }}</p>
        <p class="text-xs text-blue-200 mt-1">Modal Awal + Transaksi Tunai (Fisik di laci)</p>
    </div>

    <!-- Card 6: Jumlah Barang Terjual -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-semibold text-slate-600">Jumlah Barang Terjual</h3>
            <span class="p-2 bg-green-100 rounded-lg">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalItemsSold }} Pcs</p>
        <p class="text-xs text-slate-500 mt-1">Barang yang Anda jual hari ini</p>
    </div>
</div>

<div class="mt-8 bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">3 Transaksi Terakhir</h3>
            <a href="{{ route('kasir.transaksi') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                        <th class="px-6 py-4 font-semibold">Waktu</th>
                        <th class="px-6 py-4 font-semibold text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestTransactions as $transaction)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $transaction->created_at->timezone('Asia/Jakarta')->format('H:i') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-slate-800">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">Belum ada transaksi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
    <h3 class="text-lg font-bold text-slate-800 mb-6">Grafik Penjualan Mingguan</h3>
    <div class="h-80 rounded-[2rem] bg-slate-50 p-6 pt-10 relative overflow-visible">

        <div class="absolute top-10 left-4 flex flex-col justify-between pr-4 text-right text-[10px] font-bold text-slate-400" style="height: 180px; z-index: 10;">
            <span>Rp {{ number_format($yAxisMax, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($yAxisMax / 2, 0, ',', '.') }}</span>
            <span></span>
        </div>

        <div class="absolute bottom-14 left-16 right-6 border-t border-slate-200 border-dashed" style="z-index: 5;"></div>

        <div class="flex items-end h-[200px] space-x-6 justify-center pl-16 relative" style="z-index: 20;">
            @foreach($salesData as $data)
            @php
            $percentage = ($yAxisMax > 0) ? ($data['revenue'] / $yAxisMax) * 100 : 0;
            $h = $data['revenue'] > 0 ? max($percentage, 5) : 0;
            @endphp
            <div class="flex flex-col items-center flex-1 max-w-[50px] group relative h-full justify-end">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-all whitespace-nowrap z-50">
                    {{ $data['formatted'] }}
                </div>
                <div class="w-full transition-all duration-300 ease-out rounded-t-lg bg-blue-200 group-hover:bg-blue-600 group-hover:shadow-[0_0_15px_rgba(37,99,235,0.4)]"
                    style="height:{{ $h }}%; min-width:30px">
                </div>
                <span class="text-[10px] font-bold mt-4 text-slate-400 group-hover:text-blue-600">
                    {{ $data['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection