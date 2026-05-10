<?php

namespace App\Services;

class DecisionTreeService
{
    public function predict(string $kondisi, int $jumlahKendaraan): string
    {
        $kondisi = strtolower($kondisi);

        if ($kondisi === 'sepi') {
            return 'Rekomendasi';
        } 
        
        elseif ($kondisi === 'sedang') {
            if ($jumlahKendaraan <= 50) {
                return 'Rekomendasi';
            } else {
                return 'Alternatif';
            }
        } 
        
        elseif ($kondisi === 'padat') {
            if ($jumlahKendaraan > 150) {
                return 'Hindari';
            } else {
                return 'Alternatif';
            }
        }

        return 'Tidak Terklasifikasi'; 
    }
}