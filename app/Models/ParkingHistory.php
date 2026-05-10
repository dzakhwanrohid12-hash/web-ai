<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingHistory extends Model
{
    protected $fillable = [
        'user_id',
        'lokasi',
        'hari',
        'jam',
        'jenis_kendaraan',
        'kondisi',
        'jumlah_kendaraan',
        'hasil_keputusan',
        'hasil_list'
    ];

    protected $casts = [
        'hasil_list' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
