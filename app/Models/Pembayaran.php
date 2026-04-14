<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kamar_id',
        'periode_bulan',
        'nominal',
        'status',
    ];

    protected $casts = [
        'periode_bulan' => 'date',
        'paid_at' => 'datetime',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
