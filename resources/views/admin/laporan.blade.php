@extends('admin.layouts.layout')

@section('title', 'Laporan Penjualan - Toserba Hasan')
@section('page-title', 'Laporan Penjualan')

@section('content')

    <!-- Kontainer Utama -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

        <!-- Header: Filter & Aksi [cite: 98] -->
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">

            <!-- Filter Tanggal -->
            <form action="#" method="GET" class="flex flex-wrap items-center gap-2">
                <label for="tanggal_awal" class="text-sm font-medium text-slate-600">Dari:</label>
                <input type
="date" id="tanggal_awal" name="tanggal_awal"
                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                    value="{{ date('Y-m-d') }}">

                <label for="tanggal_akhir" class="text-sm font-medium text-slate-600 ml-2">Sampai:</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                    value="{{ date('Y-m-d') }}">

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 text-sm font-medium">
                    Tampilkan
                </button>
            </form>

            <!-- Tombol Aksi (Export) [cite: 108] -->
            <div class="flex items-center gap-2">
                <button
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 text-sm font-medium inline-flex items-center space-x-2">
                    <!-- Icon PDF -->
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Cetak PDF</span>
                </button>
                <button
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 text-sm font-medium inline-flex items-center space-x-2">
                    <!-- Icon Excel -->
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Export Excel</span>
                </button>
            </div>
        </div>

        <!-- Tabel Laporan [cite: 100-103] -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            Transaksi</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode
                            Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <!-- Data Dummy 1 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRX-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">28-10-2025 09:30</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dita</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">Rp 150.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Tunai
                            </span>
                        </td>
                    </tr>

                    <!-- Data Dummy 2 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRX-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">28-10-2025 10:15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dita</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">Rp 320.500</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                QRIS
                            </span>
                        </td>
                    </tr>

                    <!-- Data Dummy 3 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRX-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">28-10-2025 11:00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Andi</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">Rp 75.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Transfer
                            </span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Footer: Total Pendapatan [cite: 106] -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex justify-end">
                <div class="text-right">
                    <span class="text-sm font-medium text-slate-600">Total Pendapatan (dari filter):</span>
                    <!-- Data Dummy -->
                    <p class="text-2xl font-bold text-slate-800">Rp 545.500</p>
                </div>
            </div>
        </div>

    </div>
    <!-- Akhir Kontainer Utama -->
@endsection
