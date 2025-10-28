@extends('kasir.layouts.layout')

@section('title', 'Stok Barang - Toserba Hasan')
@section('page-title', 'Stok Barang')

@section('content')

    <!-- Kontainer Utama -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

        <!-- Header Card -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-slate-700">Daftar Stok Barang</h2>
            <!-- Di sini bisa ditambahkan filter/pencarian jika diperlukan -->
        </div>

        <!-- Tabel Responsif [cite: 131-133] -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Header Tabel -->
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Sekarang</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <!-- Data Dummy 1 (Stok Aman) -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Sabun Lifebuoy</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rumah Tangga</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp 5.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">15 Pcs</td>
                    </tr>

                    <!-- Data Dummy 2 (Stok Aman) -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Indomie Goreng</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Makanan</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp 3.500</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">120 Pcs</td>
                    </tr>

                    <!-- Data Dummy 3 (Stok Menipis) [cite: 136] -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Teh Pucuk</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Minuman</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp 3.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">3 Pcs</td>
                    </tr>

                    <!-- Data Dummy 4 (Stok Menipis) [cite: 136] -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Susu UHT Coklat 1L</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Minuman</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp 18.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">5 Pcs</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    <!-- Akhir Kontainer Utama -->
@endsection
