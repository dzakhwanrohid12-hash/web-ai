<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'lokasi',
        'hari',
        'jam',
        'jenis_kendaraan',
        'kondisi',
        'jumlah_kendaraan',
        'label_keputusan_final'
    ];
}