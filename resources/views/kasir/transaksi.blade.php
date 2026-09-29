@extends('kasir.layouts.layout')

@section('title', 'Transaksi Kasir - Toserba Hasan')
@section('page-title', 'Transaksi Baru')

@section('content')
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <livewire:transaksi-search />

        <div class="lg:col-span-4 space-y-6">

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2.5"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        Ringkasan
                    </h2>

                    <div id="ringkasan-list" class="space-y-4 overflow-y-auto pr-1 mb-6" style="max-height: 250px;">
                    </div>

                    <div class="bg-slate-900 p-6 rounded-[2rem] text-white shadow-xl">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-slate-400 text-xs">
                                <span>Subtotal</span>
                                <span id="subtotal-display" class="font-bold text-white">Rp 0</span>
                            </div>
                            <div class="pt-4 border-t border-slate-800">
                                <p class="text-[10px] text-slate-400 mb-1 uppercase tracking-widest">Total Tagihan</p>
                                <p id="total-tagihan" class="text-3xl font-black text-blue-400">Rp 0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Metode
                                    Bayar</label>
                                <select id="metode-bayar" onchange="toggleTunaiFields()"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none">
                                    <option value="qris" selected>QRIS</option>
                                    <option value="tunai">Tunai</option>
                                </select>
                            </div>
                        </div>

                        <div id="tunai-fields" class="space-y-4 hidden">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Uang
                                    Bayar</label>
                                <input type="number" id="input-bayar" value="0"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-blue-600 outline-none">
                            </div>

                            <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex justify-between items-center">
                                <span class="text-[10px] font-bold text-blue-800/50 uppercase">Kembalian</span>
                                <span id="kembalian-text" class="text-xl font-black text-blue-600">Rp 0</span>
                            </div>
                        </div>

                        <button type="button" id="btn-proses-transaksi"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2">
                            PROSES TRANSAKSI
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-12 mt-4">
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-lg font-bold text-slate-800">Detail Barang Terpilih</h2>
                    <span id="jenis-produk-count"
                        class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase">0 Jenis
                        Produk</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="text-slate-400">
                                <th class="px-6 py-4 text-left text-[10px] font-bold uppercase tracking-widest">Nama Produk
                                </th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold uppercase tracking-widest">Harga</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold uppercase tracking-widest">Qty</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold uppercase tracking-widest">Subtotal
                                </th>
                                <th class="px-6 py-4 text-right text-[10px] font-bold uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="keranjang-items" class="divide-y divide-slate-50">
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">
                                    Belum ada barang yang dipilih.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran QRIS -->
    <div id="modal-qris" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md text-center">
            <h3 class="text-lg font-bold text-slate-800">Pembayaran QRIS</h3>
            <p id="qris-merchant" class="text-sm font-semibold text-slate-600 mt-1"></p>
            <p id="qris-nmid" class="text-xs text-slate-400"></p>

            <div id="qris-qr" style="width: 280px; height: 280px; margin: 1rem auto;"></div>

            <p class="text-[10px] text-slate-400 uppercase tracking-widest">Total Tagihan</p>
            <p id="qris-total" class="text-3xl font-black text-blue-600">Rp 0</p>
            <p id="qris-info-statis" class="hidden text-xs font-semibold text-red-600 mt-1">
                QR statis: pembeli mengetik nominal sendiri, pastikan sama dengan total tagihan.
            </p>

            <ol class="text-left text-xs text-slate-500 mt-4 list-inside" style="list-style-type: decimal;">
                <li>Minta pembeli scan QR dengan aplikasi bank atau e-wallet.</li>
                <li>Tunggu notifikasi pembayaran masuk di Livin' Merchant.</li>
                <li>Cocokkan nominal, lalu klik <strong>Pembayaran Diterima</strong>.</li>
            </ol>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" id="btn-qris-batal"
                    class="py-3 bg-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-300">
                    Batalkan
                </button>
                <button type="button" id="btn-qris-diterima"
                    class="py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700">
                    Pembayaran Diterima
                </button>
            </div>
        </div>
    </div>

    <!-- Notifikasi singkat (tidak perlu diklik) -->
    <div id="notifikasi-transaksi"
        class="hidden fixed top-5 right-5 z-[60] rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-lg">
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
@endsection

@push('scripts')
    {{-- pustaka untuk menggambar QRIS di browser (tanpa koneksi ke layanan luar) --}}
    <script src="{{ asset('js/vendor/qrcode-generator.js') }}"></script>
    <script>
        let cart = [];
        let isProcessing = false;

        // Add Item to Cart (Triggered by Katalog Buttons)
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.btn-add-to-cart');
            if (button) {
                const id = button.dataset.id;
                const name = button.dataset.name;
                const price = Number(button.dataset.price);
                addToCart(id, name, price);
            }
        });

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.qty += 1;
                existingItem.subtotal = existingItem.qty * existingItem.price;
            } else {
                cart.push({
                    id,
                    name,
                    price,
                    qty: 1,
                    subtotal: price
                });
            }
            renderEverything();
        }

        // Change Quantity (+ / -)
        function changeQty(id, delta) {
            const item = cart.find(item => item.id == id);
            if (item) {
                item.qty += delta;
                if (item.qty <= 0) {
                    removeFromCart(id);
                } else {
                    item.subtotal = item.qty * item.price;
                    renderEverything();
                }
            }
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id != id);
            renderEverything();
        }

        function renderEverything() {
            const tbody = document.getElementById('keranjang-items');
            const sidebarList = document.getElementById('ringkasan-list');

            tbody.innerHTML = '';
            sidebarList.innerHTML = '';

            if (cart.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada barang dipilih.</td></tr>';
                sidebarList.innerHTML = '<p class="text-center text-slate-400 text-xs py-4">Keranjang kosong</p>';
                updateSummary(0);
                return;
            }

            let grandTotal = 0;

            cart.forEach(item => {
                grandTotal += item.subtotal;

                // Render Bottom Table
                tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700">${item.name}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">Rp ${item.price.toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-bold">${item.qty}</td>
                    <td class="px-6 py-4 text-sm text-blue-600 font-black">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="removeFromCart('${item.id}')" class="text-red-400 hover:text-red-600">Hapus</button>
                    </td>
                </tr>
            `);

                // Render Sidebar Ringkasan
                sidebarList.insertAdjacentHTML('beforeend', `
                <div class="flex items-center gap-3 bg-slate-50/80 p-3 rounded-2xl border border-slate-100">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-slate-800 text-xs truncate">${item.name}</h4>
                        <p class="text-[10px] text-slate-500">Rp ${item.price.toLocaleString('id-ID')} x ${item.qty}</p>
                    </div>
                    <div class="flex items-center bg-white rounded-lg border border-slate-200 p-1">
                        <button onclick="changeQty('${item.id}', -1)" class="w-5 h-5 flex items-center justify-center text-slate-400">-</button>
                        <span class="w-6 text-center font-bold text-xs">${item.qty}</span>
                        <button onclick="changeQty('${item.id}', 1)" class="w-5 h-5 flex items-center justify-center text-slate-400">+</button>
                    </div>
                </div>
            `);
            });

            updateSummary(grandTotal);
        }

        function updateSummary(total) {
            document.getElementById('subtotal-display').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total-tagihan').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('jenis-produk-count').innerText = `${cart.length} Jenis Produk`;
            hitungKembalian(); // Recalculate change whenever total changes
        }

        // Hitung Kembalian Logic
        document.addEventListener('DOMContentLoaded', function() {
            const inputBayar = document.getElementById('input-bayar');

            // Trigger calculation every time a key is pressed
            if (inputBayar) {
                inputBayar.addEventListener('input', hitungKembalian);
            }
        });

        function hitungKembalian() {
            // 1. Get the Total Tagihan (Strip "Rp" and "." so it becomes a number)
            const totalElement = document.getElementById('total-tagihan');
            if (!totalElement) return;

            const totalValue = parseInt(totalElement.innerText.replace(/[^0-9]/g, '')) || 0;

            // 2. Get the amount paid by the customer
            const bayarValue = parseInt(document.getElementById('input-bayar').value) || 0;

            // 3. Calculate Change
            const kembalian = bayarValue - totalValue;

            // 4. Display the result
            const kembalianDisplay = document.getElementById('kembalian-text');

            if (kembalian < 0) {
                kembalianDisplay.innerText = "Rp 0";
                kembalianDisplay.classList.add('text-red-500'); // Optional: show red if not enough
            } else {
                kembalianDisplay.innerText = "Rp " + kembalian.toLocaleString('id-ID');
                kembalianDisplay.classList.remove('text-red-500');
            }
        }

        function toggleTunaiFields() {
            const metodeBayar = document.getElementById('metode-bayar').value;
            const tunaiFields = document.getElementById('tunai-fields');
            if (metodeBayar === 'tunai') {
                tunaiFields.classList.remove('hidden');
            } else {
                tunaiFields.classList.add('hidden');
            }
        }

        async function prosesTransaksi() {
            if (isProcessing) {
                return;
            }

            if (cart.length === 0) return alert("Keranjang masih kosong!");

            const metodeBayar = document.getElementById('metode-bayar').value;
            const btnProses = document.getElementById('btn-proses-transaksi');
            const originalText = btnProses.innerHTML;

            isProcessing = true;
            btnProses.disabled = true;
            btnProses.innerHTML =
                '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> MEMPROSES...</span>';

            try {
                if (metodeBayar === 'tunai') {
                    const totalValue = parseInt(document.getElementById('total-tagihan').innerText.replace(/[^0-9]/g,
                        '')) || 0;
                    const bayarValue = parseInt(document.getElementById('input-bayar').value) || 0;

                    if (bayarValue < totalValue) {
                        throw new Error("Uang pembayaran kurang!");
                    }

                    const response = await fetch('{{ route('kasir.simpanTransaksi') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: 'CASH-' + Date.now(),
                            payment_type: 'tunai',
                            cart: cart
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Transaksi tunai gagal disimpan.');
                    }

                    alert("Transaksi Tunai Berhasil!");
                    cart = [];
                    renderEverything();
                    document.getElementById('input-bayar').value = 0;
                    hitungKembalian();
                    return;
                }

                // Pembayaran QRIS: minta server membuat QR berisi total belanja
                const response = await fetch('{{ route('kasir.transaksi.qris') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        cart: cart
                    })
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Gagal membuat QRIS.');
                }

                bukaModalQris(data);
            } catch (error) {
                console.error(error);
                alert(error.message || "Terjadi kesalahan pada sistem!");
            } finally {
                if (isProcessing) {
                    btnProses.innerHTML = originalText;
                    btnProses.disabled = false;
                    isProcessing = false;
                }
            }
        }

        // ===== Pembayaran QRIS =====
        let qrisOrderId = null;
        let qrisSibuk = false;

        function bukaModalQris(data) {
            qrisOrderId = data.order_id;

            // gambar QR dari teks QRIS yang dibuat server
            const qr = qrcode(0, 'M');
            qr.addData(data.qris_payload);
            qr.make();
            document.getElementById('qris-qr').innerHTML = qr.createSvgTag({
                cellSize: 6,
                margin: 4,
                scalable: true
            });

            document.getElementById('qris-total').innerText = data.total_formatted;
            document.getElementById('qris-merchant').innerText = data.merchant_name || '';
            document.getElementById('qris-nmid').innerText = data.nmid ? 'NMID: ' + data.nmid : '';
            document.getElementById('qris-info-statis').classList.toggle('hidden', data.mode !== 'statis');
            document.getElementById('modal-qris').classList.remove('hidden');
        }

        function tutupModalQris() {
            document.getElementById('modal-qris').classList.add('hidden');
            document.getElementById('qris-qr').innerHTML = '';
            qrisOrderId = null;
        }

        function aturTombolQris(nonaktif) {
            document.getElementById('btn-qris-diterima').disabled = nonaktif;
            document.getElementById('btn-qris-batal').disabled = nonaktif;
        }

        async function kirimAksiQris(url) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok || result.status !== 'success') {
                throw new Error(result.message || 'Terjadi kesalahan pada sistem!');
            }

            return result;
        }

        async function pembayaranQrisDiterima() {
            if (!qrisOrderId || qrisSibuk) {
                return;
            }

            qrisSibuk = true;
            aturTombolQris(true);

            try {
                const url = '{{ route('kasir.transaksi.qris.konfirmasi', '__ORDER__') }}'
                    .replace('__ORDER__', encodeURIComponent(qrisOrderId));
                const result = await kirimAksiQris(url);

                tutupModalQris();
                cart = [];
                renderEverything();
                tampilkanNotifikasi(result.message);
            } catch (error) {
                console.error(error);
                alert(error.message);
            } finally {
                qrisSibuk = false;
                aturTombolQris(false);
            }
        }

        async function batalkanQris() {
            if (!qrisOrderId || qrisSibuk) {
                return;
            }

            if (!confirm('Batalkan pembayaran QRIS ini? Keranjang tidak akan dihapus.')) {
                return;
            }

            qrisSibuk = true;
            aturTombolQris(true);

            try {
                const url = '{{ route('kasir.transaksi.qris.batal', '__ORDER__') }}'
                    .replace('__ORDER__', encodeURIComponent(qrisOrderId));
                await kirimAksiQris(url);
                tutupModalQris();
            } catch (error) {
                console.error(error);
                alert(error.message);
            } finally {
                qrisSibuk = false;
                aturTombolQris(false);
            }
        }

        // notifikasi yang hilang sendiri, agar kasir tidak perlu klik OK
        function tampilkanNotifikasi(pesan) {
            const notifikasi = document.getElementById('notifikasi-transaksi');
            notifikasi.innerText = pesan;
            notifikasi.classList.remove('hidden');
            clearTimeout(window.timerNotifikasi);
            window.timerNotifikasi = setTimeout(() => notifikasi.classList.add('hidden'), 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnProses = document.getElementById('btn-proses-transaksi');

            if (btnProses) {
                btnProses.addEventListener('click', prosesTransaksi);
            }

            document.getElementById('btn-qris-diterima').addEventListener('click', pembayaranQrisDiterima);
            document.getElementById('btn-qris-batal').addEventListener('click', batalkanQris);
        });
    </script>
    
@endpush
