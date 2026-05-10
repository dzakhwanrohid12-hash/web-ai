<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingDataset;
use Illuminate\Support\Facades\File;

class ParkingDatasetSeeder extends Seeder
{
    public function run()
    {
        $csvFile = base_path('database/data/Dataset_Final.csv');
        
        if (!File::exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, "r");
        $isFirstLine = true;

        while (($data = fgetcsv($file, 2000, ",")) !== FALSE) {
            if ($isFirstLine) {
                $isFirstLine = false;
                continue; // Lewati baris pertama (Header / Judul Kolom)
            }

            // Memasukkan data berdasarkan indeks kolom di CSV Anda
            // Indeks: 1=Lokasi, 2=Hari, 4=Jam, 5=Jenis Kendaraan, 6=Kondisi, 7=Jumlah Kend, 9=Label
            if (isset($data[1]) && isset($data[9])) {
                ParkingDataset::create([
                    'lokasi'          => $data[1],
                    'hari'            => $data[2],
                    'jam'             => $data[4],
                    'jenis_kendaraan' => $data[5],
                    'kondisi'         => $data[6],
                    'jumlah_kendaraan'     => (int) $data[7],
                    'label_keputusan_final' => $data[9],
                ]);
            }
        }

        fclose($file);
        $this->command->info('Data parkir historis berhasil di-seed!');
    }
}