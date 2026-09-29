<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Membentuk QRIS untuk pembayaran di kasir.
 *
 * QRIS berisi teks berformat TLV (Tag-Length-Value) sesuai standar EMVCo:
 * setiap blok = [ID 2 digit][panjang 2 digit][isi], dan blok terakhir (ID 63)
 * adalah kode pemeriksa CRC16 yang dihitung dari seluruh teks sebelumnya.
 *
 * Mode "dinamis": dari QRIS statis toko, dibentuk QR baru per transaksi yang
 * sudah berisi nominal belanja (ID 54), sehingga pembeli tidak perlu mengetik
 * nominal. Data rekening merchant tidak diubah sama sekali.
 *
 * Mode "statis": QRIS toko ditampilkan apa adanya, pembeli mengetik nominal.
 */
class QrisService
{
    public const MODE_DINAMIS = 'dinamis';
    public const MODE_STATIS = 'statis';

    private const TAG_POINT_OF_INITIATION = '01';
    private const TAG_CURRENCY = '53';
    private const TAG_AMOUNT = '54';
    private const TAG_CRC = '63';

    /** Blok tip/biaya layanan: dihapus pada QR dinamis agar nominal tetap sesuai total belanja. */
    private const TIP_TAGS = ['55', '56', '57'];

    public function __construct(
        private ?string $payload,
        private string $mode = self::MODE_DINAMIS,
    ) {
        $this->payload = $payload !== null ? trim($payload) : null;
        $this->mode = strtolower(trim($mode)) === self::MODE_STATIS ? self::MODE_STATIS : self::MODE_DINAMIS;
    }

    /**
     * Membuat service dari konfigurasi .env (QRIS_PAYLOAD dan QRIS_MODE).
     */
    public static function fromConfig(): self
    {
        return new self(
            config('services.qris.payload'),
            (string) config('services.qris.mode', self::MODE_DINAMIS),
        );
    }

    public function mode(): string
    {
        return $this->mode;
    }

    /**
     * QRIS toko sudah diisi dan kode pemeriksanya valid.
     */
    public function isConfigured(): bool
    {
        return $this->payload !== null && $this->payload !== '' && self::isValid($this->payload);
    }

    /**
     * Teks QRIS yang ditampilkan untuk satu transaksi.
     * Mode dinamis: berisi nominal. Mode statis: QRIS toko apa adanya.
     */
    public function payloadFor(int $nominal): string
    {
        $this->assertConfigured();

        if ($this->mode === self::MODE_STATIS) {
            return $this->payload;
        }

        return self::withAmount($this->payload, $nominal);
    }

    /**
     * Nama merchant yang tertera di QRIS (ID 59).
     */
    public function merchantName(): ?string
    {
        return $this->isConfigured() ? self::findTag($this->payload, '59') : null;
    }

    /**
     * Nomor NMID merchant (ID 51, sub-ID 02).
     */
    public function nmid(): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $merchantInfo = self::findTag($this->payload, '51');

        return $merchantInfo !== null ? self::findTag($merchantInfo, '02') : null;
    }

    /**
     * Membentuk QRIS dinamis: ID 01 menjadi "12", ID 54 berisi nominal,
     * blok tip dihapus, lalu kode pemeriksa (ID 63) dihitung ulang.
     */
    public static function withAmount(string $staticPayload, int $nominal): string
    {
        if ($nominal < 1) {
            throw new InvalidArgumentException('Nominal QRIS harus lebih dari 0.');
        }

        if (! self::isValid($staticPayload)) {
            throw new InvalidArgumentException('Teks QRIS tidak valid (kode pemeriksa tidak cocok).');
        }

        $amount = (string) $nominal;

        if (strlen($amount) > 13) {
            throw new InvalidArgumentException('Nominal QRIS terlalu besar.');
        }

        $result = '';
        $currencyFound = false;

        foreach (self::parse($staticPayload) as [$tag, $value]) {
            if ($tag === self::TAG_CRC || $tag === self::TAG_AMOUNT || in_array($tag, self::TIP_TAGS, true)) {
                continue;
            }

            if ($tag === self::TAG_POINT_OF_INITIATION) {
                $value = '12'; // 11 = statis, 12 = dinamis
            }

            $result .= self::encode($tag, $value);

            // nominal diletakkan tepat setelah blok mata uang (ID 53)
            if ($tag === self::TAG_CURRENCY) {
                $result .= self::encode(self::TAG_AMOUNT, $amount);
                $currencyFound = true;
            }
        }

        if (! $currencyFound) {
            throw new InvalidArgumentException('Teks QRIS tidak memiliki blok mata uang (ID 53).');
        }

        $result .= self::TAG_CRC . '04';

        return $result . self::crc16($result);
    }

    /**
     * Mengurai teks TLV menjadi daftar [ID, isi].
     *
     * @return list<array{0: string, 1: string}>
     */
    public static function parse(string $payload): array
    {
        $blocks = [];
        $position = 0;
        $length = strlen($payload);

        while ($position < $length) {
            $tag = substr($payload, $position, 2);
            $size = substr($payload, $position + 2, 2);

            if (strlen($tag) !== 2 || ! ctype_digit($size)) {
                throw new InvalidArgumentException('Format teks QRIS tidak valid.');
            }

            $value = substr($payload, $position + 4, (int) $size);

            if (strlen($value) !== (int) $size) {
                throw new InvalidArgumentException('Format teks QRIS tidak valid.');
            }

            $blocks[] = [$tag, $value];
            $position += 4 + (int) $size;
        }

        return $blocks;
    }

    /**
     * Kode pemeriksa (ID 63) cocok dengan isi QRIS.
     */
    public static function isValid(string $payload): bool
    {
        if (strlen($payload) < 8 || substr($payload, -8, 4) !== self::TAG_CRC . '04') {
            return false;
        }

        try {
            self::parse($payload);
        } catch (InvalidArgumentException) {
            return false;
        }

        return strtoupper(substr($payload, -4)) === self::crc16(substr($payload, 0, -4));
    }

    /**
     * CRC16-CCITT (polinomial 0x1021, nilai awal 0xFFFF) sesuai spesifikasi QRIS.
     */
    public static function crc16(string $data): string
    {
        $crc = 0xFFFF;

        for ($i = 0, $n = strlen($data); $i < $n; $i++) {
            $crc ^= ord($data[$i]) << 8;

            for ($bit = 0; $bit < 8; $bit++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return sprintf('%04X', $crc);
    }

    private static function encode(string $tag, string $value): string
    {
        return $tag . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private static function findTag(string $payload, string $tag): ?string
    {
        foreach (self::parse($payload) as [$currentTag, $value]) {
            if ($currentTag === $tag) {
                return $value;
            }
        }

        return null;
    }

    private function assertConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new InvalidArgumentException('QRIS toko belum diatur atau tidak valid. Isi QRIS_PAYLOAD di file .env.');
        }
    }
}
