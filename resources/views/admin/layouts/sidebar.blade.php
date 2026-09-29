<aside id="main-sidebar"
    class="w-64 flex-col fixed inset-y-0 bg-slate-50 border-r border-gray-200 shadow-xl z-30 
           transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out">

    <div class="flex flex-col h-full bg-white/80 backdrop-blur-md">
        <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100 flex-shrink-0">
            <a href="#" class="flex items-center gap-2 group" id="sidebar-logo-text">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-200">
                    <span class="text-white font-bold text-sm">H</span>
                </div>
                <span class="text-lg font-extrabold tracking-tight text-slate-800 group-hover:text-blue-600 transition-colors">
                    TOSERBA <span class="text-blue-600">HASAN</span>
                </span>
            </a>

            <button id="desktop-minimize-btn" class="hidden lg:flex p-1.5 rounded-full hover:bg-slate-100 text-slate-400 hover:text-blue-600 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 mb-4 sidebar-text transition-opacity duration-300">
                Menu {{ Auth::user()->roleLabel() }}
            </p>

            <ul class="space-y-1.5">
                @php
                    $current = request()->segment(2);
                    // Pemilik: semua menu. Admin: hanya Produk dan Pengguna.
                    $isPemilik = Auth::user()->isPemilik();
                @endphp

                @if ($isPemilik)
                <li>
                    <a href="{{ route('admin') }}"
                        class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 relative
                        {{ request()->routeIs('admin') 
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' 
                            : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                        
                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="ml-3 font-semibold sidebar-text whitespace-nowrap">Dashboard</span>
                        
                        @if(request()->routeIs('admin'))
                            <div class="absolute right-2 w-1.5 h-1.5 bg-white rounded-full sidebar-text"></div>
                        @endif
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('admin.products') }}"
                        class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 relative
                        {{ request()->routeIs('admin.products') 
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' 
                            : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">

                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10.5 11.25h3M12 15h.008m-7.5 0h15" />
                        </svg>
                        <span class="ml-3 font-semibold sidebar-text whitespace-nowrap">Produk</span>

                        @if(request()->routeIs('admin.products'))
                            <div class="absolute right-2 w-1.5 h-1.5 bg-white rounded-full sidebar-text"></div>
                        @endif
                    </a>
                </li>

                @if ($isPemilik)
                <li>
                    <a href="{{ route('admin.laporan') }}"
                        class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 relative
                        {{ request()->routeIs('admin.laporan') 
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' 
                            : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">

                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        <span class="ml-3 font-semibold sidebar-text whitespace-nowrap">Laporan</span>

                        @if(request()->routeIs('admin.laporan'))
                            <div class="absolute right-2 w-1.5 h-1.5 bg-white rounded-full sidebar-text"></div>
                        @endif
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('admin.manage_pengguna') }}"
                        class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 relative
                        {{ request()->routeIs('admin.manage_pengguna') 
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' 
                            : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">

                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.003c0 1.113.285 2.16.786 3.07M15 19.128c.331.18.681.303 1.05.372M9 11.25a3 3 0 100-6 3 3 0 000 6zM12 15a5.25 5.25 0 00-5.25 5.25H17.25a5.25 5.25 0 00-5.25-5.25z" />
                        </svg>
                        <span class="ml-3 font-semibold sidebar-text whitespace-nowrap">Pengguna</span>

                        @if(request()->routeIs('admin.manage_pengguna'))
                            <div class="absolute right-2 w-1.5 h-1.5 bg-white rounded-full sidebar-text"></div>
                        @endif
                    </a>
                </li>

                @if ($isPemilik)
                <li>
                    <a href="{{ route('admin.modal_kasir') }}"
                        class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 relative
                        {{ request()->routeIs('admin.modal_kasir') 
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' 
                            : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">

                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0H3m-1.25 0H2.25m1.5 0h16.5m0 0h.75m-.75 0a.75.75 0 01.75.75v.75m0 0H21m0 0v11.25c0 .754-.726 1.294-1.453 1.096A60.108 60.108 0 0016.5 18.75m-13.5 0c1.03 0 2.052.052 3.064.155.727.074 1.436-.454 1.436-1.186V12.75A2.25 2.25 0 005.25 10.5h-1.5" />
                        </svg>
                        <span class="ml-3 font-semibold sidebar-text whitespace-nowrap">Modal Kasir</span>

                        @if(request()->routeIs('admin.modal_kasir'))
                            <div class="absolute right-2 w-1.5 h-1.5 bg-white rounded-full sidebar-text"></div>
                        @endif
                    </a>
                </li>
                @endif
            </ul>
        </nav>

        <div class="p-4 bg-slate-50/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="group flex w-full items-center px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50 transition-all duration-200 font-semibold">
                    <div class="p-2 rounded-lg bg-rose-100 text-rose-600 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </div>
                    <span class="ml-3 sidebar-text">Logout {{ Auth::user()->roleLabel() }}</span>
                </button>
            </form>
        </div>
    </div>
</aside>