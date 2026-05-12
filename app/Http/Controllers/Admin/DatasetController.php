<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingDataset;

class DatasetController extends Controller
{
    public function index()
    {
        $totalData = ParkingDataset::count();
        $datasets = ParkingDataset::orderBy('id', 'desc')->paginate(10);

        return view('admin.admin', compact('totalData', 'datasets'));
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), "r");

        $isFirstLine = true;
        $count = 0;

        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
            if ($isFirstLine) {
                $isFirstLine = false;
                continue;
            }

            if (isset($data[1])) {
                ParkingDataset::create([
                    'lokasi'                => $data[1],
                    'hari'                  => $data[2],
                    'jam'                   => $data[4],
                    'jenis_kendaraan'       => $data[5],
                    'kondisi'               => $data[6],
                    'jumlah_kendaraan'      => (int) $data[7],
                    'label_keputusan_final' => $data[9] ?? $data[6],
                ]);
                $count++;
            }
        }

        fclose($handle);
        return back()->with('success', "Berhasil mengimpor {$count} data latih baru!");
    }
}
