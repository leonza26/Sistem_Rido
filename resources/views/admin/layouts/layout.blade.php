<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Toserba Hasan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Mencegah teks loncat saat animasi sidebar */
        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-minimized {
            width: 5rem !important;
        }

        .sidebar-minimized .sidebar-text,
        .sidebar-minimized #sidebar-logo-text {
            opacity: 0;
            width: 0;
            display: none;
        }

        .content-expanded {
            margin-left: 5rem !important;
        }
    </style>
    
</head>

<body class="bg-slate-50 antialiased overflow-x-hidden">

    @include('admin.layouts.sidebar')

    <div id="sidebar-backdrop"
        class="fixed inset-0 bg-slate-900/40 z-20 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

    <div id="main-content" class="flex flex-col min-h-screen lg:ml-64 transition-all duration-300 ease-in-out">

        <header
            class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-10 h-16 flex items-center shadow-sm">
            <div class="w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">

                <div class="flex items-center gap-4">
                    <button id="hamburger-btn" class="lg:hidden p-2 rounded-md text-slate-500 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-3 border-l pl-4 border-gray-100">
                    <div class="hidden sm:block text-right leading-tight">
                        <div class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">{{ Auth::user()->roleLabel() }}</div>
                    </div>
                    <div
                        class="h-9 w-9 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center text-indigo-700 font-bold">
                        {{ substr(Auth::user()->roleLabel(), 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                {{-- pesan saat pengguna membuka halaman yang bukan haknya --}}
                @if (session('akses_ditolak'))
                    <div class="mb-6 px-4 py-3 rounded-xl border border-red-200 bg-red-50 text-sm font-medium text-red-700">
                        {{ session('akses_ditolak') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
    <script>
        const sidebar = document.getElementById('main-sidebar');
        const mainContent = document.getElementById('main-content');
        const backdrop = document.getElementById('sidebar-backdrop');
        const desktopBtn = document.getElementById('desktop-minimize-btn');
        const mobileBtn = document.getElementById('hamburger-btn');
        const icon = document.getElementById('minimize-icon');

        // Logic Toggle Desktop (Minimize)
        desktopBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-minimized');
            mainContent.classList.toggle('content-expanded');
            icon.classList.toggle('rotate-180');
        });

        // Logic Toggle Mobile (Show/Hide)
        mobileBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        });

        backdrop?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        });
    </script>
    @stack('scripts')
</body>

</html>
