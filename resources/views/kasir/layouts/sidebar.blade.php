<aside class="w-64 bg-white text-slate-800 flex-shrink-0 flex flex-col border-r border-slate-200">
    <div class="p-6 text-center">
        <h2 class="text-2xl font-bold text-blue-600 tracking-wider">TOSERBA HASAN</h2>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 px-4 py-2 space-y-2">

        <!-- Dashboard (Contoh Link Aktif) -->
        <a href="{{ route('kasir') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('kasir') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} font-medium transition-colors duration-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <!-- Transaksi -->
        <a href="{{ route('kasir.transaksi') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('kasir.transaksi') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} font-medium transition-colors duration-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            <span class="text-sm font-medium">Transaksi</span>
        </a>

        <!-- Stok Barang -->
        <a href="{{ route('kasir.stok_barang') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('kasir.stok_barang') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} font-medium transition-colors duration-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
            </svg>
            <span class="text-sm font-medium">Stok Barang</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="px-4 pb-4">
        <form action="{{ route('logout') }}" method="POST" class="flex items-center space-x-3">
            @csrf
            <button type="submit"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                <!-- Icon: Logout (Heroicons) -->
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>
