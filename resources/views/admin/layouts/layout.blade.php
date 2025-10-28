<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toserba Hasan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @stack('scripts')
</head>

<body class="bg-slate-50 antialiased">


    <div class="flex min-h-screen">

        <!-- === Sidebar Admin === -->
        @include('admin.layouts.sidebar')

        <!-- === Konten Utama === -->
        <div class="flex-1 flex flex-col">


            <header class="bg-white w-full p-6 flex justify-between items-center border-b border-slate-200">

                <h1 class="text-xl sm:text-2xl font-semibold text-slate-800">
                    @yield('page-title', 'Dashboard')
                </h1>


                <div class="text-right">
                    <div class="text-sm font-medium text-slate-700">Nama Pemilik</div>
                    <div class="text-xs text-slate-500">Pemilik Toko</div>
                </div>
            </header>


            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-6 md:p-8">
                @yield('content')
            </main>


            <footer class="bg-white p-4 text-center text-sm text-slate-500 border-t border-slate-200 mt-auto">
                © Toserba Hasan 2025
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>



