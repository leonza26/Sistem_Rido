<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\Kategori;

class ProdukTemplateExport implements WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    /**
     * @param  bool  $denganHargaModal  true untuk Pemilik. Template Admin tidak memuat kolom Harga Modal.
     */
    public function __construct(private bool $denganHargaModal = true)
    {
    }

    public function headings(): array
    {
        $headings = [
            'Nama Produk',
            'Nama Kategori',
            'Harga Modal',
            'Harga Jual',
            'Stok Awal',
            'Foto Produk (Gambar)'
        ];

        if (! $this->denganHargaModal) {
            $headings = array_values(array_diff($headings, ['Harga Modal']));
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Ambil semua nama kategori dari database
                $kategoris = Kategori::pluck('nama_kategori')->toArray();

                // Tulis kategori di kolom Z (di luar area data biasa)
                $row = 1;
                foreach ($kategoris as $kategori) {
                    $sheet->setCellValue('Z' . $row, $kategori);
                    $row++;
                }

                // Sembunyikan kolom Z agar tidak terlihat admin
                $sheet->getColumnDimension('Z')->setVisible(false);

                if (count($kategoris) > 0) {
                    $rowCount = count($kategoris);

                    // Buat Data Validation untuk B2
                    $validation = $sheet->getCell('B2')->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input Error');
                    $validation->setError('Kategori tidak valid. Silakan pilih dari dropdown.');
                    $validation->setPromptTitle('Pilih Kategori');
                    $validation->setPrompt('Pilih kategori produk dari daftar.');

                    // Gunakan reference ke range sel di kolom Z
                    $validation->setFormula1('=$Z$1:$Z$' . $rowCount);

                    // Terapkan validation ke banyak baris (misal sampai baris 1000)
                    $sheet->setDataValidation('B2:B1000', $validation);
                }
            },
        ];
    }
}
