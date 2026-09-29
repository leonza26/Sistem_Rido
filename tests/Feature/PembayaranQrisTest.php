<?php

/*
|--------------------------------------------------------------------------
| Pengujian alur pembayaran QRIS di halaman kasir
|--------------------------------------------------------------------------
| QRIS yang dipakai adalah data fiktif (TOKO CONTOH), bukan QRIS toko asli.
| Jalankan: php artisan test --filter=PembayaranQrisTest
*/

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\QrisService;
use Illuminate\Support\Facades\Route;

function qrisTokoContoh(): string
{
    return '00020101021126680020ID.CO.BANKCONTOH.WWW01189360000000000000010211123456789010303UMI51440014ID.CO.QRIS.WWW0215ID10200000000010303UMI5204541153033605802ID5911TOKO CONTOH6009PEKANBARU61052811162070703A016304C8F3';
}

function kasirQris(): User
{
    return User::factory()->create(['role' => User::ROLE_KASIR]);
}

function produkQris(array $atribut = []): Produk
{
    $kategori = Kategori::firstOrCreate(['nama_kategori' => 'Makanan & Minuman']);

    return Produk::create(array_merge([
        'nama_produk' => 'Kopi Kapal Api 165g',
        'kategori_id' => $kategori->id,
        'harga_modal' => 11000,
        'harga' => 14000,
        'stok_awal' => 30,
    ], $atribut));
}

function nominalDiQris(string $payload): ?string
{
    foreach (QrisService::parse($payload) as [$tag, $isi]) {
        if ($tag === '54') {
            return $isi;
        }
    }

    return null;
}

beforeEach(function () {
    config([
        'services.qris.payload' => qrisTokoContoh(),
        'services.qris.mode' => 'dinamis',
    ]);
});

test('kasir mendapat QRIS berisi total belanja dari harga database', function () {
    $kopi = produkQris();
    $gula = produkQris(['nama_produk' => 'Gula Pasir 1kg', 'harga' => 17500]);

    $response = $this->actingAs(kasirQris())
        ->postJson(route('kasir.transaksi.qris'), [
            'cart' => [
                ['id' => $kopi->id, 'qty' => 2, 'price' => 1], // harga dari browser diabaikan
                ['id' => $gula->id, 'qty' => 1, 'price' => 1],
            ],
        ])
        ->assertOk()
        ->assertJson([
            'status' => 'success',
            'total' => 45500,
            'total_formatted' => 'Rp 45.500',
            'mode' => 'dinamis',
            'merchant_name' => 'TOKO CONTOH',
            'nmid' => 'ID1020000000001',
        ]);

    $payload = $response->json('qris_payload');

    expect(QrisService::isValid($payload))->toBeTrue()
        ->and(nominalDiQris($payload))->toBe('45500')
        ->and($response->json('order_id'))->toStartWith('QRIS-');

    // belum ada transaksi dan stok belum berkurang sebelum kasir mengonfirmasi
    expect(Transaksi::count())->toBe(0)
        ->and($kopi->refresh()->stok_awal)->toBe(30);
});

test('pembayaran diterima menyimpan transaksi QRIS dan mengurangi stok', function () {
    $kopi = produkQris();
    $kasir = kasirQris();

    $orderId = $this->actingAs($kasir)
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 3]]])
        ->json('order_id');

    $this->actingAs($kasir)
        ->postJson(route('kasir.transaksi.qris.konfirmasi', $orderId))
        ->assertOk()
        ->assertJson(['status' => 'success', 'transaction_id' => $orderId, 'total' => 42000]);

    $transaksi = Transaksi::where('transaction_id', $orderId)->firstOrFail();

    expect($transaksi->payment_method)->toBe('qris')
        ->and((int) $transaksi->total_amount)->toBe(42000)
        ->and($transaksi->user_id)->toBe($kasir->id)
        ->and($transaksi->metode_label)->toBe('QRIS')
        ->and($kopi->refresh()->stok_awal)->toBe(27);
});

test('total tersimpan sama dengan nominal QR walau harga berubah sebelum konfirmasi', function () {
    $kopi = produkQris();
    $kasir = kasirQris();

    $orderId = $this->actingAs($kasir)
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 1]]])
        ->json('order_id');

    $kopi->update(['harga' => 20000]); // harga diubah setelah pembeli membayar Rp14.000

    $this->actingAs($kasir)->postJson(route('kasir.transaksi.qris.konfirmasi', $orderId))->assertOk();

    expect((int) Transaksi::where('transaction_id', $orderId)->value('total_amount'))->toBe(14000);
});

test('konfirmasi dua kali tidak membuat transaksi ganda', function () {
    $kopi = produkQris();
    $kasir = kasirQris();

    $orderId = $this->actingAs($kasir)
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 1]]])
        ->json('order_id');

    $this->actingAs($kasir)->postJson(route('kasir.transaksi.qris.konfirmasi', $orderId))->assertOk();
    $this->actingAs($kasir)->postJson(route('kasir.transaksi.qris.konfirmasi', $orderId))->assertOk();

    expect(Transaksi::where('transaction_id', $orderId)->count())->toBe(1)
        ->and($kopi->refresh()->stok_awal)->toBe(29);
});

test('pembayaran QRIS yang dibatalkan tidak menyimpan transaksi', function () {
    $kopi = produkQris();
    $kasir = kasirQris();

    $orderId = $this->actingAs($kasir)
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 2]]])
        ->json('order_id');

    $this->actingAs($kasir)->postJson(route('kasir.transaksi.qris.batal', $orderId))->assertOk();

    // setelah dibatalkan, QR lama tidak bisa dikonfirmasi lagi
    $this->actingAs($kasir)->postJson(route('kasir.transaksi.qris.konfirmasi', $orderId))->assertNotFound();

    expect(Transaksi::count())->toBe(0)
        ->and($kopi->refresh()->stok_awal)->toBe(30);
});

test('QRIS tidak dibuat jika stok tidak mencukupi', function () {
    $kopi = produkQris(['stok_awal' => 1]);

    $this->actingAs(kasirQris())
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 2]]])
        ->assertStatus(422)
        ->assertJson(['status' => 'error']);
});

test('QRIS tidak dibuat jika keranjang kosong', function () {
    $this->actingAs(kasirQris())
        ->postJson(route('kasir.transaksi.qris'), ['cart' => []])
        ->assertStatus(400);
});

test('kasir mendapat pesan jelas jika QRIS toko belum diatur', function () {
    config(['services.qris.payload' => null]);
    $kopi = produkQris();

    $this->actingAs(kasirQris())
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 1]]])
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'QRIS toko belum diatur. Isi QRIS_PAYLOAD di file .env.']);
});

test('mode statis menampilkan QRIS toko tanpa nominal', function () {
    config(['services.qris.mode' => 'statis']);
    $kopi = produkQris();

    $response = $this->actingAs(kasirQris())
        ->postJson(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 1]]])
        ->assertOk()
        ->assertJson(['mode' => 'statis', 'total' => 14000]);

    expect($response->json('qris_payload'))->toBe(qrisTokoContoh());
});

test('transaksi tunai tetap berjalan seperti sebelumnya', function () {
    $kopi = produkQris();

    $this->actingAs(kasirQris())
        ->postJson(route('kasir.simpanTransaksi'), [
            'order_id' => 'CASH-123',
            'payment_type' => 'tunai',
            'cart' => [['id' => $kopi->id, 'qty' => 2]],
        ])
        ->assertOk();

    $transaksi = Transaksi::where('transaction_id', 'CASH-123')->firstOrFail();

    expect($transaksi->payment_method)->toBe('tunai')
        ->and((int) $transaksi->total_amount)->toBe(28000)
        ->and($kopi->refresh()->stok_awal)->toBe(28);
});

test('transaksi non-tunai tidak bisa disimpan tanpa alur QRIS', function () {
    $kopi = produkQris();

    $this->actingAs(kasirQris())
        ->postJson(route('kasir.simpanTransaksi'), [
            'order_id' => 'QRIS-PALSU',
            'payment_type' => 'qris',
            'cart' => [['id' => $kopi->id, 'qty' => 1]],
        ])
        ->assertStatus(422);

    expect(Transaksi::count())->toBe(0);
});

test('route Midtrans sudah dihapus', function () {
    expect(Route::has('kasir.transaksi.checkout'))->toBeFalse()
        ->and(Route::has('kasir.transaksi.finish'))->toBeFalse()
        ->and(config('services.midtrans'))->toBeNull();
});

test('halaman transaksi kasir memuat QRIS tanpa script Midtrans', function () {
    $this->actingAs(kasirQris())
        ->get(route('kasir.transaksi'))
        ->assertOk()
        ->assertSee('js/vendor/qrcode-generator.js', false)
        ->assertSee('id="modal-qris"', false)
        ->assertSee('<option value="qris" selected>QRIS</option>', false)
        ->assertDontSee('snap.js', false)
        ->assertDontSee('midtrans', false);
});

test('pemilik dan admin tidak dapat membuat QRIS kasir', function (int $role) {
    $kopi = produkQris();

    $this->actingAs(User::factory()->create(['role' => $role]))
        ->post(route('kasir.transaksi.qris'), ['cart' => [['id' => $kopi->id, 'qty' => 1]]])
        ->assertRedirect();

    expect(Transaksi::count())->toBe(0);
})->with([User::ROLE_PEMILIK, User::ROLE_ADMIN]);

test('laporan pemilik dapat difilter hanya transaksi QRIS untuk rekonsiliasi', function () {
    $pemilik = User::factory()->create(['role' => User::ROLE_PEMILIK]);

    Transaksi::create(['transaction_id' => 'QRIS-001', 'transaction_date' => now(), 'total_amount' => 45500, 'cashier_name' => 'Kasir', 'payment_method' => 'qris']);
    Transaksi::create(['transaction_id' => 'CASH-001', 'transaction_date' => now(), 'total_amount' => 12000, 'cashier_name' => 'Kasir', 'payment_method' => 'tunai']);

    $this->actingAs($pemilik)
        ->get(route('admin.laporan', ['metode' => 'qris']))
        ->assertOk()
        ->assertSee('QRIS-001')
        ->assertDontSee('CASH-001');

    $this->actingAs($pemilik)
        ->get(route('admin.laporan', ['metode' => 'tunai']))
        ->assertOk()
        ->assertSee('CASH-001')
        ->assertDontSee('QRIS-001');

    $this->actingAs($pemilik)
        ->get(route('admin.laporan'))
        ->assertOk()
        ->assertSee('QRIS-001')
        ->assertSee('CASH-001');
});
