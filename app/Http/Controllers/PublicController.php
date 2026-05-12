<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DecisionTreeService;
use App\Models\ParkingHistory;
use App\Models\ParkingDataset;

class PublicController extends Controller
{
    protected $decisionTreeService;

    public function __construct(DecisionTreeService $decisionTreeService)
    {
        $this->decisionTreeService = $decisionTreeService;
    }

    public function index()
    {
        return view('public.index');
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string',
            'waktu' => 'required|date_format:H:i',
            'jenis_kendaraan' => 'required|in:sepeda motor,mobil',
        ]);

        $jamInput = $validated['waktu'];
        $jamEksak = '08:30:00';
        $namaSesi = 'Pagi';

        // Logika Time Grouping
        if ($jamInput >= '07:00' && $jamInput <= '10:59') {
            $jamEksak = '08:30:00';
            $namaSesi = 'Pagi';
        } elseif ($jamInput >= '11:00' && $jamInput <= '14:59') {
            $jamEksak = '12:30:00';
            $namaSesi = 'Siang';
        } else {
            $jamEksak = '16:30:00';
            $namaSesi = 'Sore';
        }

        $daftarLokasi = ParkingDataset::distinct()->pluck('lokasi');

        $hasilPencarian = [
            'Rekomendasi' => [], 'Alternatif' => [], 'Hindari' => []
        ];

        $perwakilanLokasi = 'Sistem Global';
        $perwakilanKondisi = 'sedang';
        $perwakilanJumlah = 0;
        $perwakilanKeputusan = 'Hindari';

        foreach ($daftarLokasi as $lokasi) {
            $historicalData = ParkingDataset::where('lokasi', $lokasi)
                ->where('hari', $validated['hari'])
                ->where('jam', $jamEksak)
                ->where('jenis_kendaraan', $validated['jenis_kendaraan'])
                ->get();

            if ($historicalData->isNotEmpty()) {
                $estimasiJumlah = (int) $historicalData->avg('jumlah_kendaraan');
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

        ParkingHistory::create([
            'user_id' => auth()->check() ? auth()->id() : null,
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
            'success' => 'Analisis selesai!',
            'hasil_list' => $hasilPencarian,
            'status_lokasi' => $statusPerLokasi,
            'keterangan_waktu' => "Berdasarkan sesi {$namaSesi} ({$jamEksak} WIB)"
        ]);
    }

    public function myHistory()
    {
        $riwayat = ParkingHistory::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('public.history', compact('riwayat'));
    }
}
