<aside class="w-64 bg-white text-slate-800 flex-shrink-0 flex flex-col border-r border-slate-200">
    <div class="p-6 text-center">
        <!-- Logo/Nama Toko (Warna disesuaikan) -->
        <h2 class="text-2xl font-bold text-blue-600 tracking-wider">TOSERBA HASAN</h2>
    </div>

    <!-- Navigasi dengan flex-1 untuk mendorong logout ke bawah -->
    <nav class="flex-1 px-4 py-2 space-y-2">

        <!-- Menu Pemilik/Admin -->

        <!-- Dashboard -->
        <a href="#" class_comment="Ganti '#' dengan route('admin.dashboard')"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium transition-colors duration-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <!-- Produk-->
        <a href="#" class_comment="Ganti '#' dengan route('admin.produk')"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors duration-200">

            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10.5 11.25h3M12 15h.008m-7.5 0h15" />
            </svg>
            <span class="text-sm font-medium">Produk</span>
        </a>

        <!-- Laporan -->
        <a href="#" class_comment="Ganti '#' dengan route('admin.laporan')"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors duration-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span class="text-sm font-medium">Laporan</span>
        </a>

        <!-- Pengguna -->
        <a href="#" class_comment="Ganti '#' dengan route('admin.pengguna')"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors duration-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.003c0 1.113.285 2.16.786 3.07M15 19.128c.331.18.681.303 1.05.372M9 11.25a3 3 0 100-6 3 3 0 000 6zM12 15a5.25 5.25 0 00-5.25 5.25H17.25a5.25 5.25 0 00-5.25-5.25z" />
            </svg>
            <span class="text-sm font-medium">Pengguna</span>
        </a>
    </nav>

    <!-- Logout  -->
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
