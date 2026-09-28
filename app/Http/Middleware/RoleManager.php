<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Membatasi akses route berdasarkan role pengguna.
     * Satu route boleh diakses lebih dari satu role,
     * contoh: ->middleware('rolemanager:pemilik,admin')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // mengecek apakah pengguna belum login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // role tidak dikenal (data tidak valid): keluarkan pengguna dari sistem
        if ($user->homeRoute() === null) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Role akun Anda tidak valid. Hubungi pemilik atau admin.']);
        }

        // mengubah nama role pada route (pemilik/admin/kasir) menjadi kode role
        $allowedRoles = array_map(fn ($role) => User::ROLES[$role] ?? null, $roles);

        if (in_array($user->role, $allowedRoles, true)) {
            return $next($request);
        }

        // tidak berhak: arahkan ke halaman awal sesuai role
        return redirect()->route($user->homeRoute())
            ->with('akses_ditolak', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}
