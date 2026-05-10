<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DecisionTreeService;
use App\Models\ParkingHistory;

class ParkingController extends Controller
{
    protected $decisionTreeService;

    public function __construct(DecisionTreeService $decisionTreeService)
    {
        $this->decisionTreeService = $decisionTreeService;
    }

    public function index()
    {
        return view('parking.index');
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string',
            'waktu' => 'required|in:pagi,siang,sore', 
            'jenis_kendaraan' => 'required|in:sepeda motor,mobil',
        ]);

        $jamMapping = [
            'pagi' => '08:30:00',
            'siang' => '12:30:00',
            'sore' => '16:30:00'
        ];
        $jamEksak = $jamMapping[$validated['waktu']];

        $daftarLokasi = \App\Models\ParkingDataset::distinct()->pluck('lokasi');

        $hasilPencarian = [
            'Rekomendasi' => [],
            'Alternatif' => [],
            'Hindari' => []
        ];

        $perwakilanLokasi = 'Sistem Global';
        $perwakilanKondisi = 'sedang';
        $perwakilanJumlah = 0;
        $perwakilanKeputusan = 'Hindari';

        foreach ($daftarLokasi as $lokasi) {
            $historicalData = \App\Models\ParkingDataset::where('lokasi', $lokasi)
                ->where('hari', $validated['hari'])
                ->where('jam', $jamEksak)
                ->where('jenis_kendaraan', $validated['jenis_kendaraan'])
                ->get();

            if ($historicalData->isNotEmpty()) {
                $estimasiJumlah = (int) $historicalData->avg('jumlah_kend');
                $estimasiKondisi = $historicalData->countBy('kondisi')->sortDesc()->keys()->first();

                $keputusan = $this->decisionTreeService->predict($estimasiKondisi, $estimasiJumlah);

                if (array_key_exists($keputusan, $hasilPencarian)) {
                    $hasilPencarian[$keputusan][] = $lokasi;
                }

                if ($keputusan == 'Rekomendasi' && $perwakilanKeputusan != 'Rekomendasi') {
                    $perwakilanLokasi = $lokasi;
                    $perwakilanKondisi = $estimasiKondisi;
                    $perwakilanJumlah = $estimasiJumlah;
                    $perwakilanKeputusan = $keputusan;
                } elseif ($keputusan == 'Alternatif' && $perwakilanKeputusan == 'Hindari') {
                    $perwakilanLokasi = $lokasi;
                    $perwakilanKondisi = $estimasiKondisi;
                    $perwakilanJumlah = $estimasiJumlah;
                    $perwakilanKeputusan = $keputusan;
                }
            }
        }

        \App\Models\ParkingHistory::create([
            'user_id' => auth()->id(),
            'lokasi' => $perwakilanLokasi,
            'hari' => $validated['hari'],
            'jam' => $jamEksak,
            'jenis_kendaraan' => $validated['jenis_kendaraan'],
            'kondisi' => $perwakilanKondisi,
            'jumlah_kendaraan' => $perwakilanJumlah,
            'hasil_keputusan' => $perwakilanKeputusan,
            'hasil_list' => $hasilPencarian
        ]);

        $statusPerLokasi = [];
        foreach ($hasilPencarian as $status => $lokasis) {
            foreach ($lokasis as $lok) {
                $statusPerLokasi[$lok] = $status;
            }
        }

        return back()->with([
            'success' => 'Analisis selesai! Berikut saran parkir untuk Anda:',
            'hasil_list' => $hasilPencarian,
            'status_lokasi' => $statusPerLokasi
        ]);
    }

    public function adminView()
    {
        $totalData = \App\Models\ParkingDataset::count();
        return view('parking.admin', compact('totalData'));
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

            if (isset($data[1]) && isset($data[9])) {
                \App\Models\ParkingDataset::create([
                    'lokasi'          => $data[1],
                    'hari'            => $data[2],
                    'jam'             => $data[4],
                    'jenis_kendaraan' => $data[5],
                    'kondisi'         => $data[6],
                    'jumlah_kendaraan'     => (int) $data[7],
                    'label_keputusan_final' => $data[9],
                ]);
                $count++;
            }
        }
        
        fclose($handle);

        return back()->with('success', "Berhasil mengimpor {$count} data baru ke dalam sistem!");
    }

    public function dashboard()
    {
        $totalPencarian = \App\Models\ParkingHistory::count();

        $kendaraanDominan = \App\Models\ParkingHistory::select('jenis_kendaraan')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('jenis_kendaraan')
            ->orderBy('count', 'desc')
            ->first();

        $statistikKeputusan = \App\Models\ParkingHistory::select('hasil_keputusan')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('hasil_keputusan')
            ->pluck('count', 'hasil_keputusan')
            ->toArray();

        $riwayatTerbaru = \App\Models\ParkingHistory::with('user')
        ->orderBy('created_at', 'desc')
        ->limit(15)
        ->get();

        return view('parking.dashboard', compact(
            'totalPencarian', 
            'kendaraanDominan', 
            'statistikKeputusan', 
            'riwayatTerbaru'
        ));
    }
}