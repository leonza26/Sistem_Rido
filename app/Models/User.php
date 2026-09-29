<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kode role pengguna yang disimpan pada kolom users.role.
     * 0 = Admin, 1 = Kasir, 2 = Pemilik.
     */
    public const ROLE_ADMIN = 0;
    public const ROLE_KASIR = 1;
    public const ROLE_PEMILIK = 2;

    /**
     * Nama role yang dipakai pada middleware route => kode role.
     * Contoh: ->middleware('rolemanager:pemilik,admin')
     */
    public const ROLES = [
        'admin' => self::ROLE_ADMIN,
        'kasir' => self::ROLE_KASIR,
        'pemilik' => self::ROLE_PEMILIK,
    ];

    /**
     * Label role untuk ditampilkan pada antarmuka.
     */
    public const ROLE_LABELS = [
        self::ROLE_PEMILIK => 'Pemilik',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_KASIR => 'Kasir',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer',
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function isPemilik(): bool
    {
        return $this->role === self::ROLE_PEMILIK;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKasir(): bool
    {
        return $this->role === self::ROLE_KASIR;
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? 'Tidak dikenal';
    }

    /**
     * Role yang boleh diberikan pengguna ini saat menambah atau mengubah akun lain.
     * Pemilik boleh memberikan semua role, Admin hanya role Admin dan Kasir.
     *
     * @return list<int>
     */
    public function assignableRoles(): array
    {
        if ($this->isPemilik()) {
            return [self::ROLE_PEMILIK, self::ROLE_ADMIN, self::ROLE_KASIR];
        }

        if ($this->isAdmin()) {
            return [self::ROLE_ADMIN, self::ROLE_KASIR];
        }

        return [];
    }

    /**
     * Nama route halaman awal setelah login sesuai role.
     * Null berarti role tidak dikenal.
     */
    public function homeRoute(): ?string
    {
        return match ($this->role) {
            self::ROLE_PEMILIK => 'admin',          // Dashboard
            self::ROLE_ADMIN => 'admin.products',   // Menu Produk
            self::ROLE_KASIR => 'kasir',            // Dashboard kasir
            default => null,
        };
    }
}
