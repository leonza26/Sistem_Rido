<?php

/*
|--------------------------------------------------------------------------
| Pengujian hak akses 3 role: Pemilik, Admin, Kasir
|--------------------------------------------------------------------------
| Jalankan: php artisan test --filter=HakAksesRoleTest
*/

use App\Exports\ProdukTemplateExport;
use App\Imports\ProdukImport;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

function buatUser(int $role, array $atribut = []): User
{
    return User::factory()->create(array_merge(['role' => $role], $atribut));
}

function buatProduk(array $atribut = []): Produk
{
    $kategori = Kategori::firstOrCreate(['nama_kategori' => 'Makanan & Minuman']);

    return Produk::create(array_merge([
        'nama_produk' => 'Indomie Goreng',
        'kategori_id' => $kategori->id,
        'harga_modal' => 3000,
        'harga' => 3500,
        'stok_awal' => 50,
    ], $atribut));
}

// ===== Login: diarahkan ke halaman awal sesuai role =====

test('pemilik diarahkan ke dashboard setelah login', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK);

    $this->post('/login', ['email' => $pemilik->email, 'password' => 'password'])
        ->assertRedirect(route('admin', absolute: false));
});

test('admin diarahkan ke menu produk setelah login', function () {
    $admin = buatUser(User::ROLE_ADMIN);

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.products', absolute: false));
});

test('kasir diarahkan ke dashboard kasir setelah login', function () {
    $kasir = buatUser(User::ROLE_KASIR);

    $this->post('/login', ['email' => $kasir->email, 'password' => 'password'])
        ->assertRedirect(route('kasir', absolute: false));
});

test('akun dengan role tidak dikenal ditolak saat login', function () {
    $user = buatUser(9);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

// ===== Hak akses menu =====

test('pemilik dapat membuka semua menu', function (string $route) {
    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->get(route($route))
        ->assertOk();
})->with(['admin', 'admin.laporan', 'admin.modal_kasir', 'admin.products', 'admin.manage_pengguna']);

test('admin dapat membuka menu produk dan pengguna', function (string $route) {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->get(route($route))
        ->assertOk();
})->with(['admin.products', 'admin.manage_pengguna']);

test('admin tidak dapat membuka menu khusus pemilik', function (string $route) {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->get(route($route))
        ->assertRedirect(route('admin.products'))
        ->assertSessionHas('akses_ditolak');
})->with(['admin', 'admin.laporan', 'admin.laporan.pdf', 'admin.laporan.excel', 'admin.modal_kasir', 'kasir', 'kasir.transaksi']);

test('admin tidak dapat menyimpan modal kasir', function () {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->post(route('admin.modal_kasir.store'), ['tanggal' => now()->toDateString(), 'modal_awal' => 100000])
        ->assertRedirect(route('admin.products'));

    $this->assertDatabaseCount('modal_kasirs', 0);
});

test('kasir tidak dapat membuka menu pemilik maupun admin', function (string $route) {
    $this->actingAs(buatUser(User::ROLE_KASIR))
        ->get(route($route))
        ->assertRedirect(route('kasir'))
        ->assertSessionHas('akses_ditolak');
})->with(['admin', 'admin.laporan', 'admin.modal_kasir', 'admin.products', 'admin.manage_pengguna']);

test('kasir tetap dapat membuka menu kasir', function (string $route) {
    $this->actingAs(buatUser(User::ROLE_KASIR))
        ->get(route($route))
        ->assertOk();
})->with(['kasir', 'kasir.transaksi', 'kasir.stok_barang']);

test('pemilik tidak membuka menu kasir', function () {
    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->get(route('kasir'))
        ->assertRedirect(route('admin'));
});

test('tamu diarahkan ke halaman login', function () {
    $this->get(route('admin.products'))->assertRedirect(route('login'));
});

test('sidebar admin hanya menampilkan menu produk dan pengguna', function () {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->get(route('admin.products'))
        ->assertOk()
        ->assertSee(route('admin.products'), false)
        ->assertSee(route('admin.manage_pengguna'), false)
        ->assertDontSee(route('admin.laporan'), false)
        ->assertDontSee(route('admin.modal_kasir'), false);
});

// ===== Harga modal tersembunyi dari Admin =====

test('admin tidak melihat harga modal di menu produk', function () {
    buatProduk(['harga_modal' => 3000]);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->get(route('admin.products'))
        ->assertOk()
        ->assertSee('Indomie Goreng')
        ->assertDontSee('data-harga-modal="', false)
        ->assertDontSee('name="harga_modal"', false)
        ->assertDontSee('Rp 3.000');
});

test('pemilik melihat harga modal di menu produk', function () {
    buatProduk(['harga_modal' => 3000]);

    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->get(route('admin.products'))
        ->assertOk()
        ->assertSee('data-harga-modal="3000', false)
        ->assertSee('name="harga_modal"', false)
        ->assertSee('Rp 3.000');
});

test('produk yang ditambah admin tersimpan tanpa harga modal', function () {
    $kategori = Kategori::create(['nama_kategori' => 'Alat Tulis']);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->post(route('admin.produk.store'), [
            'nama_produk' => 'Pulpen',
            'kategori_id' => (string) $kategori->id, // form mengirim string
            'harga_modal' => 1500, // dikirim paksa, harus diabaikan
            'harga' => 2500,
            'stok_awal' => 20,
        ])
        ->assertSessionHasNoErrors();

    expect((float) Produk::where('nama_produk', 'Pulpen')->value('harga_modal'))->toBe(0.0);
});

test('admin mengubah produk tidak mengubah harga modal', function () {
    $produk = buatProduk(['harga_modal' => 3000]);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->put(route('admin.produk.update', $produk->id), [
            'nama_produk' => 'Indomie Goreng Jumbo',
            'kategori_id' => $produk->kategori_id,
            'harga_modal' => 1, // dikirim paksa, harus diabaikan
            'harga' => 4000,
            'stok_awal' => 40,
        ])
        ->assertSessionHasNoErrors();

    $produk->refresh();
    expect($produk->nama_produk)->toBe('Indomie Goreng Jumbo')
        ->and((float) $produk->harga)->toBe(4000.0)
        ->and((float) $produk->harga_modal)->toBe(3000.0);
});

test('pemilik dapat mengisi harga modal', function () {
    $produk = buatProduk(['harga_modal' => 0]);

    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->put(route('admin.produk.update', $produk->id), [
            'nama_produk' => $produk->nama_produk,
            'kategori_id' => $produk->kategori_id,
            'harga_modal' => 2800,
            'harga' => 3500,
            'stok_awal' => 50,
        ])
        ->assertSessionHasNoErrors();

    expect((float) $produk->refresh()->harga_modal)->toBe(2800.0);
});

test('template excel untuk admin tanpa kolom harga modal', function () {
    Excel::fake();

    $this->actingAs(buatUser(User::ROLE_ADMIN))->get(route('admin.produk.download_template'));

    Excel::assertDownloaded('Template_Produk.xlsx', function (ProdukTemplateExport $export) {
        return ! in_array('Harga Modal', $export->headings());
    });
});

test('template excel untuk pemilik memuat kolom harga modal', function () {
    Excel::fake();

    $this->actingAs(buatUser(User::ROLE_PEMILIK))->get(route('admin.produk.download_template'));

    Excel::assertDownloaded('Template_Produk.xlsx', function (ProdukTemplateExport $export) {
        return in_array('Harga Modal', $export->headings());
    });
});

test('import excel oleh admin mengabaikan kolom harga modal', function () {
    $lama = buatProduk(['harga_modal' => 3000]);

    (new ProdukImport(false))->collection(collect([
        ['nama_produk' => 'Indomie Goreng', 'nama_kategori' => 'Makanan & Minuman', 'harga_modal' => 1, 'harga_jual' => 3600, 'stok_awal' => 60],
        ['nama_produk' => 'Teh Botol', 'nama_kategori' => 'Makanan & Minuman', 'harga_modal' => 4000, 'harga_jual' => 5000, 'stok_awal' => 24],
    ]));

    expect((float) $lama->refresh()->harga_modal)->toBe(3000.0)
        ->and((float) $lama->harga)->toBe(3600.0)
        ->and((float) Produk::where('nama_produk', 'Teh Botol')->value('harga_modal'))->toBe(0.0);
});

test('import excel oleh pemilik menyimpan harga modal', function () {
    Kategori::firstOrCreate(['nama_kategori' => 'Makanan & Minuman']);

    (new ProdukImport(true))->collection(collect([
        ['nama_produk' => 'Teh Botol', 'nama_kategori' => 'Makanan & Minuman', 'harga_modal' => 4000, 'harga_jual' => 5000, 'stok_awal' => 24],
    ]));

    expect((float) Produk::where('nama_produk', 'Teh Botol')->value('harga_modal'))->toBe(4000.0);
});

// ===== Kelola pengguna & pemulihan akses =====

test('pilihan role admin hanya admin dan kasir', function () {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->get(route('admin.manage_pengguna'))
        ->assertOk()
        ->assertViewHas('roleOptions', [User::ROLE_ADMIN => 'Admin', User::ROLE_KASIR => 'Kasir']);
});

test('pilihan role pemilik mencakup semua role', function () {
    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->get(route('admin.manage_pengguna'))
        ->assertOk()
        ->assertViewHas('roleOptions', [User::ROLE_PEMILIK => 'Pemilik', User::ROLE_ADMIN => 'Admin', User::ROLE_KASIR => 'Kasir']);
});

test('admin dapat menambah kasir', function () {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->post(route('users.store'), [
            'nama' => 'Kasir Baru',
            'email' => 'kasirbaru@toserbahasan.test',
            'role' => User::ROLE_KASIR,
            'password' => 'rahasia123',
            'konfirmasi_password' => 'rahasia123',
        ])
        ->assertSessionHasNoErrors();

    expect(User::where('email', 'kasirbaru@toserbahasan.test')->value('role'))->toBe(User::ROLE_KASIR);
});

test('admin tidak dapat membuat akun pemilik', function () {
    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->post(route('users.store'), [
            'nama' => 'Pemilik Palsu',
            'email' => 'palsu@toserbahasan.test',
            'role' => User::ROLE_PEMILIK,
            'password' => 'rahasia123',
            'konfirmasi_password' => 'rahasia123',
        ])
        ->assertSessionHasErrors('role');

    $this->assertDatabaseMissing('users', ['email' => 'palsu@toserbahasan.test']);
});

test('admin tidak dapat menaikkan kasir menjadi pemilik', function () {
    $kasir = buatUser(User::ROLE_KASIR);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->put(route('users.update', $kasir->id), [
            'nama' => $kasir->name,
            'email' => $kasir->email,
            'role' => User::ROLE_PEMILIK,
        ])
        ->assertSessionHasErrors('role');

    expect($kasir->refresh()->role)->toBe(User::ROLE_KASIR);
});

test('admin dapat mereset password pemilik yang lupa password', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK, ['name' => 'Pak Hasan', 'email' => 'hasan@toserbahasan.test']);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->put(route('users.update', $pemilik->id), [
            'nama' => 'Diubah Admin',            // harus diabaikan
            'email' => 'diubah@toserbahasan.test', // harus diabaikan
            'role' => User::ROLE_KASIR,           // harus diabaikan
            'password' => 'passwordbaru',
            'konfirmasi_password' => 'passwordbaru',
        ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $pemilik->refresh();
    expect(Hash::check('passwordbaru', $pemilik->password))->toBeTrue()
        ->and($pemilik->name)->toBe('Pak Hasan')
        ->and($pemilik->email)->toBe('hasan@toserbahasan.test')
        ->and($pemilik->role)->toBe(User::ROLE_PEMILIK);

    // pemilik bisa login lagi dengan password baru
    auth()->logout();
    $this->post('/login', ['email' => 'hasan@toserbahasan.test', 'password' => 'passwordbaru'])
        ->assertRedirect(route('admin', absolute: false));
});

test('reset password pemilik oleh admin wajib mengisi password', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->put(route('users.update', $pemilik->id), ['nama' => 'x', 'email' => 'x@x.test'])
        ->assertSessionHasErrors('password');
});

test('pemilik dapat mereset password admin', function () {
    $admin = buatUser(User::ROLE_ADMIN);

    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->put(route('users.update', $admin->id), [
            'nama' => $admin->name,
            'email' => $admin->email,
            'role' => User::ROLE_ADMIN,
            'password' => 'adminbaru1',
            'konfirmasi_password' => 'adminbaru1',
        ])
        ->assertSessionHasNoErrors();

    expect(Hash::check('adminbaru1', $admin->refresh()->password))->toBeTrue();
});

test('admin tidak dapat menghapus akun pemilik', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK);

    $this->actingAs(buatUser(User::ROLE_ADMIN))
        ->delete(route('users.destroy', $pemilik->id))
        ->assertSessionHasErrors();

    $this->assertModelExists($pemilik);
});

test('pengguna tidak dapat menghapus akunnya sendiri', function (int $role) {
    $user = buatUser($role);

    $this->actingAs($user)
        ->delete(route('users.destroy', $user->id))
        ->assertSessionHasErrors();

    $this->assertModelExists($user);
})->with([User::ROLE_PEMILIK, User::ROLE_ADMIN]);

test('role akun sendiri tidak dapat diubah', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK);

    $this->actingAs($pemilik)
        ->put(route('users.update', $pemilik->id), [
            'nama' => 'Pemilik Baru',
            'email' => $pemilik->email,
            'role' => User::ROLE_KASIR,
        ])
        ->assertSessionHasNoErrors();

    $pemilik->refresh();
    expect($pemilik->role)->toBe(User::ROLE_PEMILIK)
        ->and($pemilik->name)->toBe('Pemilik Baru');
});

test('pemilik dapat menghapus admin', function () {
    $admin = buatUser(User::ROLE_ADMIN);

    $this->actingAs(buatUser(User::ROLE_PEMILIK))
        ->delete(route('users.destroy', $admin->id))
        ->assertSessionHasNoErrors();

    $this->assertModelMissing($admin);
});

test('kasir tidak dapat menambah pengguna', function () {
    $this->actingAs(buatUser(User::ROLE_KASIR))
        ->post(route('users.store'), [
            'nama' => 'Siapa',
            'email' => 'siapa@toserbahasan.test',
            'role' => User::ROLE_ADMIN,
            'password' => 'rahasia123',
            'konfirmasi_password' => 'rahasia123',
        ])
        ->assertRedirect(route('kasir'));

    $this->assertDatabaseMissing('users', ['email' => 'siapa@toserbahasan.test']);
});

test('registrasi publik dinonaktifkan', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register', [
        'name' => 'Orang Luar',
        'email' => 'luar@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertDatabaseMissing('users', ['email' => 'luar@example.com']);
});

// ===== Pemulihan darurat oleh pengembang =====

test('perintah artisan dapat mereset password pengguna', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK, ['email' => 'pemilik@toserbahasan.test']);

    $this->artisan('user:reset-password', ['email' => 'pemilik@toserbahasan.test'])
        ->expectsQuestion('Password baru (minimal 6 karakter)', 'darurat123')
        ->expectsQuestion('Ulangi password baru', 'darurat123')
        ->assertSuccessful();

    expect(Hash::check('darurat123', $pemilik->refresh()->password))->toBeTrue();
});

test('perintah artisan menolak konfirmasi yang berbeda', function () {
    $pemilik = buatUser(User::ROLE_PEMILIK, ['email' => 'pemilik@toserbahasan.test']);

    $this->artisan('user:reset-password', ['email' => 'pemilik@toserbahasan.test'])
        ->expectsQuestion('Password baru (minimal 6 karakter)', 'darurat123')
        ->expectsQuestion('Ulangi password baru', 'berbeda123')
        ->assertFailed();

    expect(Hash::check('password', $pemilik->refresh()->password))->toBeTrue();
});

test('perintah artisan menolak email yang tidak terdaftar', function () {
    $this->artisan('user:reset-password', ['email' => 'tidakada@toserbahasan.test'])
        ->assertFailed();
});
