<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir - Toserba Hasan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('scripts')
</head>
<body class="bg-slate-50 font-sans text-slate-900">

    <div class="flex min-h-screen">

        <!-- Memanggil Sidebar KHUSUS Kasir -->
        @include('kasir.layouts.sidebar')

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col">

            <!-- Header Atas -->
            <header class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-10">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-slate-800">
                        @yield('page-title', 'Dashboard')
                    </h1>

                    <div class="text-sm text-right">
                        <div class="font-medium text-slate-700">Nama Kasir</div>
                        <div class="text-xs text-slate-500">Kasir</div>
                    </div>
                </div>
            </header>

            <!-- Area Konten Dinamis -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
