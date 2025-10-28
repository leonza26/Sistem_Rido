<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Form Login -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                Email
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="Masukkan email Anda"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                Password
            </label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                placeholder="Masukkan password Anda"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mb-5">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                Ingat saya
            </label>
        </div>

        <!-- Forgot Password + Login Button -->
        <div class="flex items-center justify-between">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                    Lupa password?
                </a>
            @endif

            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Login
            </button>
        </div>
    </form>

</x-guest-layout>
