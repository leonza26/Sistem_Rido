<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukImport implements ToCollection, WithHeadingRow, WithEvents
{
    private $images = [];

    /**
     * @param  bool  $bolehIsiHargaModal  true jika yang mengimpor adalah Pemilik.
     *                                    Untuk Admin, kolom Harga Modal diabaikan.
     */
    public function __construct(private bool $bolehIsiHargaModal = true)
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Index starts at 0 for data row 1. With heading row 1, data starts at row 2 in Excel.
            $excelRowNumber = $index + 2; 

            // Validasi data penting
            if (!isset($row['nama_produk']) || !isset($row['nama_kategori'])) {
                continue;
            }

            $namaProduk = $row['nama_produk'];
            $namaKategori = $row['nama_kategori'];
            $hargaModal = $row['harga_modal'] ?? 0;
            $harga = $row['harga_jual'] ?? $row['harga'] ?? 0;
            $stokAwal = $row['stok_awal'] ?? 0;

            // Cari kategori berdasarkan nama
            $kategori = Kategori::where('nama_kategori', $namaKategori)->first();

            // Jika kategori tidak ditemukan, abaikan baris ini sesuai request
            if (!$kategori) {
                continue;
            }

            // Dapatkan foto dari array $images jika ada
            $fotoPath = null;
            if (isset($this->images[$excelRowNumber])) {
                $fotoPath = $this->images[$excelRowNumber];
            }

            // Cek apakah produk sudah ada
            $produk = Produk::where('nama_produk', $namaProduk)->first();

            if ($produk) {
                // Update
                $updateData = [
                    'kategori_id' => $kategori->id,
                    'harga' => $harga,
                    'stok_awal' => $stokAwal,
                ];

                // harga modal lama tidak diubah jika yang mengimpor adalah Admin
                if ($this->bolehIsiHargaModal) {
                    $updateData['harga_modal'] = $hargaModal;
                }

                // Update foto jika ada foto baru
                if ($fotoPath) {
                    if ($produk->foto_produk) {
                        Storage::disk('public')->delete($produk->foto_produk);
                    }
                    $updateData['foto_produk'] = $fotoPath;
                }

                $produk->update($updateData);
            } else {
                // Create
                Produk::create([
                    'nama_produk' => $namaProduk,
                    'kategori_id' => $kategori->id,
                    'harga_modal' => $this->bolehIsiHargaModal ? $hargaModal : 0,
                    'harga' => $harga,
                    'stok_awal' => $stokAwal,
                    'foto_produk' => $fotoPath,
                ]);
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $drawingCollection = $sheet->getDrawingCollection();
                
                foreach ($drawingCollection as $drawing) {
                    // Dapatkan sel tempat gambar berada (contoh: 'E2')
                    $coordinates = $drawing->getCoordinates();
                    
                    // Ekstrak baris (contoh: 'E2' -> '2')
                    preg_match('/[A-Z]+(\d+)/', $coordinates, $matches);
                    if (isset($matches[1])) {
                        $rowNumber = (int) $matches[1];

                        $extension = 'jpg';
                        $imageContents = null;

                        if ($drawing instanceof MemoryDrawing) {
                            ob_start();
                            call_user_func(
                                $drawing->getRenderingFunction(),
                                $drawing->getImageResource()
                            );
                            $imageContents = ob_get_contents();
                            ob_end_clean();
                            switch ($drawing->getMimeType()) {
                                case MemoryDrawing::MIMETYPE_PNG :
                                    $extension = 'png'; break;
                                case MemoryDrawing::MIMETYPE_GIF:
                                    $extension = 'gif'; break;
                                case MemoryDrawing::MIMETYPE_JPEG :
                                    $extension = 'jpg'; break;
                            }
                        } else if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Drawing) {
                            $imagePath = $drawing->getPath();
                            if (file_exists($imagePath)) {
                                $imageContents = file_get_contents($imagePath);
                                $extension = pathinfo($drawing->getPath(), PATHINFO_EXTENSION);
                            } else {
                                $imageContents = file_get_contents($imagePath);
                                $extension = pathinfo($drawing->getPath(), PATHINFO_EXTENSION);
                            }
                        }

                        if ($imageContents) {
                            // Cek folder
                            if (!Storage::disk('public')->exists('products')) {
                                Storage::disk('public')->makeDirectory('products');
                            }

                            $filename = 'products/imported_' . Str::random(10) . '_' . time() . '.' . $extension;
                            Storage::disk('public')->put($filename, $imageContents);
                            $this->images[$rowNumber] = $filename;
                        }
                    }
                }
            }
        ];
    }
}
