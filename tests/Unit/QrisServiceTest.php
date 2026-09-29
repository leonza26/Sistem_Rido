<?php

/*
|--------------------------------------------------------------------------
| Pengujian pembentukan QRIS (tanpa database)
|--------------------------------------------------------------------------
| Contoh QRIS di bawah adalah data fiktif (TOKO CONTOH). Kode pemeriksa
| (CRC) contoh dihitung dengan program terpisah sebagai pembanding.
| Jalankan: php artisan test --filter=QrisServiceTest
*/

use App\Services\QrisService;

const QRIS_STATIS_CONTOH = '00020101021126680020ID.CO.BANKCONTOH.WWW01189360000000000000010211123456789010303UMI51440014ID.CO.QRIS.WWW0215ID10200000000010303UMI5204541153033605802ID5911TOKO CONTOH6009PEKANBARU61052811162070703A016304C8F3';

const QRIS_DINAMIS_35000_CONTOH = '00020101021226680020ID.CO.BANKCONTOH.WWW01189360000000000000010211123456789010303UMI51440014ID.CO.QRIS.WWW0215ID10200000000010303UMI5204541153033605405350005802ID5911TOKO CONTOH6009PEKANBARU61052811162070703A016304EBEB';

const QRIS_DENGAN_TIP_CONTOH = '00020101021126680020ID.CO.BANKCONTOH.WWW01189360000000000000010211123456789010303UMI51440014ID.CO.QRIS.WWW0215ID10200000000010303UMI52045411530336054031005502015802ID5911TOKO CONTOH6009PEKANBARU61052811162070703A016304FB44';

function blokQris(string $payload): array
{
    $hasil = [];
    foreach (QrisService::parse($payload) as [$tag, $isi]) {
        $hasil[$tag] = $isi;
    }

    return $hasil;
}

test('rumus CRC16 sesuai nilai uji standar', function () {
    // nilai uji baku CRC-16/CCITT-FALSE untuk teks "123456789" adalah 29B1
    expect(QrisService::crc16('123456789'))->toBe('29B1');
});

test('QRIS contoh dikenali valid dan QRIS rusak ditolak', function () {
    expect(QrisService::isValid(QRIS_STATIS_CONTOH))->toBeTrue()
        ->and(QrisService::isValid(substr(QRIS_STATIS_CONTOH, 0, -4) . '0000'))->toBeFalse()
        ->and(QrisService::isValid('bukan qris'))->toBeFalse();
});

test('QRIS dinamis sama persis dengan hasil hitungan pembanding', function () {
    expect(QrisService::withAmount(QRIS_STATIS_CONTOH, 35000))->toBe(QRIS_DINAMIS_35000_CONTOH);
});

test('QRIS dinamis berisi nominal dan data merchant tidak berubah', function () {
    $statis = blokQris(QRIS_STATIS_CONTOH);
    $dinamis = blokQris(QrisService::withAmount(QRIS_STATIS_CONTOH, 1000));

    expect($dinamis['01'])->toBe('12')          // 12 = QR dinamis
        ->and($dinamis['54'])->toBe('1000')     // nominal
        ->and($dinamis['26'])->toBe($statis['26'])
        ->and($dinamis['51'])->toBe($statis['51'])
        ->and($dinamis['59'])->toBe($statis['59'])
        ->and($dinamis['62'])->toBe($statis['62'])
        ->and(QrisService::isValid(QrisService::withAmount(QRIS_STATIS_CONTOH, 1000)))->toBeTrue();
});

test('blok nominal diletakkan tepat setelah blok mata uang', function () {
    $urutan = array_column(QrisService::parse(QrisService::withAmount(QRIS_STATIS_CONTOH, 1000)), 0);
    $posisiMataUang = array_search('53', $urutan, true);

    expect($urutan[$posisiMataUang + 1])->toBe('54')
        ->and(end($urutan))->toBe('63');
});

test('nominal lama dan blok tip diganti dengan nominal belanja', function () {
    $dinamis = blokQris(QrisService::withAmount(QRIS_DENGAN_TIP_CONTOH, 25000));

    expect($dinamis['54'])->toBe('25000')
        ->and($dinamis)->not->toHaveKey('55');
});

test('mode statis menampilkan QRIS toko apa adanya', function () {
    $qris = new QrisService(QRIS_STATIS_CONTOH, QrisService::MODE_STATIS);

    expect($qris->payloadFor(35000))->toBe(QRIS_STATIS_CONTOH)
        ->and($qris->mode())->toBe('statis');
});

test('mode tidak dikenal dianggap dinamis', function () {
    expect((new QrisService(QRIS_STATIS_CONTOH, 'sembarang'))->mode())->toBe('dinamis');
});

test('nama merchant dan NMID dibaca dari QRIS', function () {
    $qris = new QrisService(QRIS_STATIS_CONTOH);

    expect($qris->merchantName())->toBe('TOKO CONTOH')
        ->and($qris->nmid())->toBe('ID1020000000001');
});

test('nominal nol ditolak', function () {
    QrisService::withAmount(QRIS_STATIS_CONTOH, 0);
})->throws(InvalidArgumentException::class);

test('QRIS kosong atau rusak dianggap belum diatur', function () {
    expect((new QrisService(null))->isConfigured())->toBeFalse()
        ->and((new QrisService(''))->isConfigured())->toBeFalse()
        ->and((new QrisService(substr(QRIS_STATIS_CONTOH, 0, -4) . '0000'))->isConfigured())->toBeFalse();
});
