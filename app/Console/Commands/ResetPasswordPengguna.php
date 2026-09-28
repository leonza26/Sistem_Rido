<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Pemulihan akses darurat oleh pengembang, dipakai jika Pemilik dan Admin
 * sama-sama tidak dapat login sehingga reset lewat menu Pengguna tidak mungkin.
 *
 * Cara pakai (dari folder proyek):
 *   php artisan user:reset-password pemilik@toserbahasan.test
 */
class ResetPasswordPengguna extends Command
{
    protected $signature = 'user:reset-password {email : Email akun yang password-nya akan direset}';

    protected $description = 'Reset password akun pengguna secara darurat (jika Pemilik dan Admin tidak dapat login)';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Akun dengan email tersebut tidak ditemukan.');

            return self::FAILURE;
        }

        $this->info("Akun: {$user->name} ({$user->roleLabel()})");

        $password = (string) $this->secret('Password baru (minimal 6 karakter)');
        $konfirmasi = (string) $this->secret('Ulangi password baru');

        if (strlen($password) < 6) {
            $this->error('Password minimal 6 karakter. Password tidak diubah.');

            return self::FAILURE;
        }

        if ($password !== $konfirmasi) {
            $this->error('Konfirmasi password tidak sama. Password tidak diubah.');

            return self::FAILURE;
        }

        $user->update(['password' => Hash::make($password)]);

        $this->info('Password berhasil direset. Silakan login dengan password baru.');

        return self::SUCCESS;
    }
}
