<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'no_hp',
        'tanggal_masuk',
        'tanggal_selesai',
        'kamar_id',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
