@extends('admin.layouts.layout')

@section('title', 'Laporan Penjualan & Laba - Toserba Hasan')
@section('page-title', 'Laporan Penjualan & Laba Kotor')

@section('content')
<div class="space-y-6">

    <!-- 4 Ringkasan KPI Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Omzet Penjualan -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Total Omzet Penjualan</h3>
                <span class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                <span>Tunai: <strong class="text-emerald-600">Rp {{ number_format($totalTunai, 0, ',', '.') }}</strong></span>
                <span>•</span>
                <span>Non-Tunai: <strong class="text-violet-600">Rp {{ number_format($totalNonTunai, 0, ',', '.') }}</strong></span>
            </div>
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
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($totalTunai, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">Uang fisik yang diterima kasir</p>
        </div>

        <!-- Card 3: Total Modal Barang (HPP) -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-slate-600">Total Modal Barang (HPP)</h3>
                <span class="p-2 bg-amber-100 rounded-lg">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalModal, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">Harga beli/pokok seluruh barang terjual</p>
        </div>

        <!-- Card 4: Total Laba Kotor -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 p-5 rounded-xl text-white shadow-md">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-emerald-100">Total Laba Kotor</h3>
                <span class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold">Rp {{ number_format($totalLabaKotor, 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-200 mt-1">
                Margin Keuntungan: <span class="font-bold underline">{{ $marginLaba }}%</span>
            </p>
        </div>
    </div>

    <!-- Kontainer Utama: Filter & Laporan -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

        <!-- Header: Filter Tanggal & Export -->
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <!-- Filter Tanggal -->
            <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="tanggal_awal" class="text-xs font-semibold text-slate-600 uppercase">Dari:</label>
                    <input type="date" id="tanggal_awal" name="tanggal_awal"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        value="{{ $start_date ?? date('Y-m-d') }}">
                </div>

                <div class="flex items-center gap-2">
                    <label for="tanggal_akhir" class="text-xs font-semibold text-slate-600 uppercase">Sampai:</label>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        value="{{ $end_date ?? date('Y-m-d') }}">
                </div>

                <div class="flex items-center gap-2">
                    <label for="metode" class="text-xs font-semibold text-slate-600 uppercase">Metode:</label>
                    <select id="metode" name="metode"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="" @selected(! $metode)>Semua</option>
                        <option value="tunai" @selected($metode === 'tunai')>Tunai</option>
                        <option value="qris" @selected($metode === 'qris')>QRIS</option>
                    </select>
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-sm font-semibold shadow-sm">
                    Filter Laporan
                </button>

                <a href="{{ route('admin.laporan') }}"
                    class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                    Reset
                </a>
            </form>

            <!-- Tombol Export -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
                    class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition duration-200 text-sm font-semibold inline-flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Cetak PDF</span>
                </a>

                <a href="{{ route('admin.laporan.excel', request()->query()) }}"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200 text-sm font-semibold inline-flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        <!-- Navigation Tabs: Rekap Harian vs Detail Transaksi -->
        <div class="flex border-b border-gray-200 mb-6">
            <button id="tab-btn-rekap" type="button"
                onclick="switchTab('rekap')"
                class="tab-btn py-3 px-6 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Rekap Laporan Harian</span>
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ count($rekapHarian) }} Hari</span>
            </button>

            <button id="tab-btn-detail" type="button"
                onclick="switchTab('detail')"
                class="tab-btn py-3 px-6 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Rincian Transaksi</span>
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $transaksis->total() }}</span>
            </button>
        </div>

        <!-- TAB 1: Tabel Rekap Harian (Per Tanggal) -->
        <div id="tab-content-rekap" class="tab-content">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-600">Jumlah Transaksi</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600">Penjualan Tunai</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600">Penjualan Non-Tunai</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600">Total Omzet</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600">Modal Barang (HPP)</th>
                            <th class="px-6 py-3 text-right font-semibold text-emerald-600">Laba Kotor</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($rekapHarian as $rekap)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">
                                    {{ $rekap->tanggal->format('d M Y') }}
                                    @if($rekap->tanggal->isToday())
                                        <span class="ml-1 text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full font-bold">Hari Ini</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center font-semibold text-slate-700">
                                    {{ $rekap->jumlah_transaksi }} Transaksi
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-slate-700">
                                    Rp {{ number_format($rekap->total_tunai, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-slate-700">
                                    Rp {{ number_format($rekap->total_non_tunai, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-slate-900">
                                    Rp {{ number_format($rekap->total_omzet, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-amber-700 font-medium">
                                    Rp {{ number_format($rekap->total_modal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-600">
                                    Rp {{ number_format($rekap->laba_kotor, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    Tidak ada data transaksi pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: Tabel Detail Transaksi -->
        <div id="tab-content-detail" class="tab-content hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">ID Transaksi</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Kasir</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Metode</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600">Total Omzet</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600">Modal (HPP)</th>
                            <th class="px-6 py-3 text-right font-semibold text-emerald-600">Laba Kotor</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($transaksis as $trnsk)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">
                                    #{{ $trnsk->transaction_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    {{ $trnsk->transaction_date ? $trnsk->transaction_date->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                    {{ $trnsk->cashier_name ?? 'Kasir' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ strtolower($trnsk->payment_method) === 'tunai' ? 'bg-emerald-100 text-emerald-800' : 'bg-violet-100 text-violet-800' }}">
                                        {{ $trnsk->metode_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-slate-800">
                                    Rp {{ number_format($trnsk->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-amber-700">
                                    Rp {{ number_format($trnsk->total_modal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-600">
                                    Rp {{ number_format($trnsk->laba_kotor, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $trxData = [
                                            'id' => $trnsk->transaction_id,
                                            'tanggal' => $trnsk->transaction_date ? $trnsk->transaction_date->format('d-m-Y H:i') : '-',
                                            'kasir' => $trnsk->cashier_name ?? 'Kasir',
                                            'metode' => $trnsk->metode_label,
                                            'total_omzet' => (float) $trnsk->total_amount,
                                            'total_modal' => (float) $trnsk->total_modal,
                                            'total_laba' => (float) $trnsk->laba_kotor,
                                            'items' => $trnsk->details->map(function($d) {
                                                $modalUnit = ($d->harga_modal > 0) ? (float)$d->harga_modal : (float)($d->produk->harga_modal ?? 0);
                                                $jualUnit = (float)$d->harga;
                                                $subtotalJual = (float)$d->subtotal;
                                                $subtotalModal = $modalUnit * $d->qty;
                                                return [
                                                    'nama_produk' => $d->produk->nama_produk ?? 'Produk',
                                                    'kategori' => $d->produk->kategori->nama_kategori ?? '-',
                                                    'qty' => $d->qty,
                                                    'harga_modal' => $modalUnit,
                                                    'subtotal_modal' => $subtotalModal,
                                                    'harga_jual' => $jualUnit,
                                                    'subtotal_jual' => $subtotalJual,
                                                    'laba_kotor' => $subtotalJual - $subtotalModal,
                                                ];
                                            })->values()->all()
                                        ];
                                    @endphp
                                    <button type="button"
                                        onclick="handleDetailClick(this)"
                                        data-trx="{{ json_encode($trxData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}"
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">
                                    Tidak ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transaksis->hasPages())
                <div class="mt-4 pt-4 border-t border-gray-200">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>

    </div>

</div>

<!-- Modal Pop-up Rincian Transaksi & Harga Modal -->
<div id="modal-detail-transaksi" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden max-h-[90vh] flex flex-col animate-in fade-in zoom-in duration-150">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-50">
            <div>
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span>Rincian Transaksi:</span>
                    <span id="detail-modal-trx-id" class="text-blue-600 font-mono"></span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar item belanja, perbandingan harga modal (dasar/beli) dan harga jual.</p>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-700 text-2xl font-bold p-1 leading-none">&times;</button>
        </div>

        <!-- Modal Meta Info -->
        <div class="px-6 py-3 bg-blue-50/60 border-b border-blue-100 flex flex-wrap items-center justify-between gap-4 text-xs">
            <div>
                <span class="text-slate-500">Waktu Transaksi:</span>
                <strong id="detail-modal-tanggal" class="text-slate-800 ml-1"></strong>
            </div>
            <div>
                <span class="text-slate-500">Kasir:</span>
                <strong id="detail-modal-kasir" class="text-slate-800 ml-1"></strong>
            </div>
            <div>
                <span class="text-slate-500">Metode Bayar:</span>
                <span id="detail-modal-metode" class="ml-1 px-2.5 py-0.5 bg-blue-100 text-blue-700 font-bold rounded-full"></span>
            </div>
        </div>

        <!-- Modal Table of Items -->
        <div class="p-6 overflow-y-auto flex-1">
            <table class="min-w-full divide-y divide-gray-200 text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2.5 text-center font-semibold text-slate-600">No</th>
                        <th class="px-4 py-2.5 text-left font-semibold text-slate-600">Nama Produk</th>
                        <th class="px-3 py-2.5 text-center font-semibold text-slate-600">Qty</th>
                        <th class="px-4 py-2.5 text-right font-semibold text-slate-600">Harga Modal (@)</th>
                        <th class="px-4 py-2.5 text-right font-semibold text-slate-600">Harga Jual (@)</th>
                        <th class="px-4 py-2.5 text-right font-semibold text-amber-700">Subtotal Modal</th>
                        <th class="px-4 py-2.5 text-right font-semibold text-slate-800">Subtotal Jual</th>
                        <th class="px-4 py-2.5 text-right font-semibold text-emerald-600">Laba Kotor</th>
                    </tr>
                </thead>
                <tbody id="detail-modal-items-tbody" class="divide-y divide-gray-100">
                    <!-- Dinamis terisi oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Modal Footer (Summary Totals & Tutup) -->
        <div class="px-6 py-4 bg-slate-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <div>
                    <span class="text-[11px] text-slate-500 block">Total Omzet:</span>
                    <strong id="detail-modal-total-omzet" class="text-slate-900 text-base"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-slate-500 block">Total Modal (HPP):</span>
                    <strong id="detail-modal-total-modal" class="text-amber-700 text-base"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-slate-500 block">Laba Kotor Nota Ini:</span>
                    <strong id="detail-modal-total-laba" class="text-emerald-600 text-base"></strong>
                </div>
            </div>

            <button type="button" onclick="closeDetailModal()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-bold transition duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        const rekapContent = document.getElementById('tab-content-rekap');
        const detailContent = document.getElementById('tab-content-detail');
        const rekapBtn = document.getElementById('tab-btn-rekap');
        const detailBtn = document.getElementById('tab-btn-detail');

        if (tabName === 'rekap') {
            rekapContent.classList.remove('hidden');
            detailContent.classList.add('hidden');

            rekapBtn.classList.add('border-blue-600', 'text-blue-600', 'font-bold');
            rekapBtn.classList.remove('border-transparent', 'text-slate-500', 'font-medium');

            detailBtn.classList.remove('border-blue-600', 'text-blue-600', 'font-bold');
            detailBtn.classList.add('border-transparent', 'text-slate-500', 'font-medium');
        } else {
            detailContent.classList.remove('hidden');
            rekapContent.classList.add('hidden');

            detailBtn.classList.add('border-blue-600', 'text-blue-600', 'font-bold');
            detailBtn.classList.remove('border-transparent', 'text-slate-500', 'font-medium');

            rekapBtn.classList.remove('border-blue-600', 'text-blue-600', 'font-bold');
            rekapBtn.classList.add('border-transparent', 'text-slate-500', 'font-medium');
        }
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function handleDetailClick(button) {
        try {
            const trx = JSON.parse(button.getAttribute('data-trx'));
            openDetailModal(trx);
        } catch (e) {
            console.error('Gagal memproses data transaksi:', e);
        }
    }

    function openDetailModal(trx) {
        document.getElementById('detail-modal-trx-id').textContent = '#' + trx.id;
        document.getElementById('detail-modal-tanggal').textContent = trx.tanggal;
        document.getElementById('detail-modal-kasir').textContent = trx.kasir;
        document.getElementById('detail-modal-metode').textContent = trx.metode;

        document.getElementById('detail-modal-total-omzet').textContent = formatRupiah(trx.total_omzet);
        document.getElementById('detail-modal-total-modal').textContent = formatRupiah(trx.total_modal);
        document.getElementById('detail-modal-total-laba').textContent = formatRupiah(trx.total_laba);

        const tbody = document.getElementById('detail-modal-items-tbody');
        tbody.innerHTML = '';

        if (!trx.items || trx.items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-slate-400">Tidak ada rincian item.</td></tr>';
        } else {
            trx.items.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50';
                tr.innerHTML = `
                    <td class="px-3 py-2.5 text-center text-slate-500">${index + 1}</td>
                    <td class="px-4 py-2.5 font-medium text-slate-800">
                        ${item.nama_produk}
                        <span class="block text-[10px] text-slate-400">${item.kategori}</span>
                    </td>
                    <td class="px-3 py-2.5 text-center font-bold text-slate-700">${item.qty}</td>
                    <td class="px-4 py-2.5 text-right text-slate-600">${formatRupiah(item.harga_modal)}</td>
                    <td class="px-4 py-2.5 text-right text-slate-600">${formatRupiah(item.harga_jual)}</td>
                    <td class="px-4 py-2.5 text-right text-amber-700 font-medium">${formatRupiah(item.subtotal_modal)}</td>
                    <td class="px-4 py-2.5 text-right text-slate-800 font-bold">${formatRupiah(item.subtotal_jual)}</td>
                    <td class="px-4 py-2.5 text-right font-bold text-emerald-600">${formatRupiah(item.laba_kotor)}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('modal-detail-transaksi').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modal-detail-transaksi').classList.add('hidden');
    }

    // Close modal on Escape key or click outside modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
        }
    });

    document.getElementById('modal-detail-transaksi').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>
@endsection
