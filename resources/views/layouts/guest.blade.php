<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toserba Hasan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="h-full bg-slate-100">

    <div class="min-h-screen flex items-center justify-center p-6 relative">

        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('img/perusahaan-retail-adalah.jpg.webp') }}"
                alt="Latar belakang Toserba Hasan"
                class="w-full h-full object-cover blur-sm brightness-10"
                onerror="this.style.display='none'"
            >
            <!-- Fallback jika gambar gagal dimuat -->
            <div class="w-full h-full bg-slate-800" style="display: none;" onerror="this.style.display='block'"></div>

            <!-- Overlay Gelap -->
            <div class="absolute inset-0 bg-black/60 z-10"></div>
        </div>

        <div class="relative z-20 w-full max-w-md bg-white p-8 rounded-2xl shadow-2xl">

            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-blue-600 tracking-wider">TOSERBA HASAN</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan login untuk melanjutkan</p>
            </div>

           {{ $slot }}

        </div>

        <p class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 text-sm text-white/80">
            © Toserba Hasan 2025
        </p>

    </div>

</body>
</html>

