<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    //
    //
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'transaction_id',
        'transaction_date',
        'user_id',
        'total_amount',
        'cashier_name',
        'payment_method',
    ];
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function getTotalModalAttribute()
    {
        return $this->details->sum(function ($detail) {
            $modal = ($detail->harga_modal > 0)
                ? $detail->harga_modal
                : ($detail->produk->harga_modal ?? 0);

            return $modal * $detail->qty;
        });
    }

    public function getLabaKotorAttribute()
    {
        return $this->total_amount - $this->total_modal;
    }

    /**
     * Label metode pembayaran untuk tampilan (tunai -> Tunai, qris -> QRIS).
     */
    public function getMetodeLabelAttribute(): string
    {
        $metode = strtolower((string) $this->payment_method);

        return match ($metode) {
            '' => '-',
            'tunai' => 'Tunai',
            'qris' => 'QRIS',
            default => ucwords(str_replace('_', ' ', $metode)),
        };
    }

    /**
     * Filter metode pembayaran pada laporan: tunai, qris (semua non-tunai), atau semua.
     */
    public function scopeMetode($query, ?string $metode)
    {
        return match ($metode) {
            'tunai' => $query->whereRaw('LOWER(payment_method) = ?', ['tunai']),
            'qris' => $query->whereRaw('LOWER(payment_method) != ?', ['tunai']),
            default => $query,
        };
    }
}
