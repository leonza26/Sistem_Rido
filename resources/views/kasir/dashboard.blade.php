@extends('kasir.layouts.layout')

@section('title', 'Dashboard Kasir - Toserba Hasan')
@section('page-title', 'Dashboard Kasir')

@section('content')

    <!-- Tombol Aksi Utama Sesuai Wireframe [cite: 38] -->
    <div class="mb-8">
        <a href="#" class="px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-flex items-center space-x-2">
            <!-- Icon: Plus -->
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Mulai Transaksi Baru</span>
        </a>
    </div>

    <!-- Grid untuk Statistik Card (Sesuai Wireframe Kasir [cite: 39-40]) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

        <!-- Card 1: Total Transaksi Hari Ini -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Total Transaksi Hari Ini</h3>
                <span class="p-2 bg-blue-100 rounded-lg">
                    <!-- Icon: Rupiah/Dollar -->
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <!-- Data dummy dari wireframe -->
                <p class="text-2xl font-bold text-slate-800">Rp 500.000</p>
                <p class="text-xs text-slate-500 mt-1">Total transaksi Anda</p>
            </div>
        </div>

        <!-- Card 2: Jumlah Barang Terjual -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Jumlah Barang Terjual</h3>
                <span class="p-2 bg-green-100 rounded-lg">
                    <!-- Icon: Shopping Bag -->
                    <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <!-- Data dummy dari wireframe -->
                <p class="text-2xl font-bold text-slate-800">56 Pcs</p>
                <p class="text-xs text-slate-500 mt-1">Barang yang Anda jual hari ini</p>
            </div>
        </div>

    </div>
@endsection

