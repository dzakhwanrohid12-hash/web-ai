@extends('layouts.admin')

@section('title', 'Visualisasi Model')
@section('page_title', 'Arsitektur Decision Tree (Pohon Keputusan)')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h3 style="color: #1E293B; margin: 0;">Bagan Model C4.5</h3>
            <p style="color: #64748B; font-size: 0.9rem; margin-top: 5px;">Visualisasi aturan keputusan berdasarkan pelatihan dataset parkir PCR.</p>
        </div>
    </div>

    <div style="text-align: center; background: #F8FAFC; padding: 40px; border-radius: 16px; border: 2px dashed #E2E8F0; margin-bottom: 30px;">
        <img src="{{ asset('images/tree.jpeg') }}" alt="Visualisasi Decision Tree" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <p style="margin-top: 15px; font-size: 0.8rem; color: #94A3B8;">* Gambar ini dihasilkan dari proses pelatihan data latih di RapidMiner/Python.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div style="background: #EEF2FF; padding: 25px; border-radius: 16px; border-left: 5px solid #4F46E5;">
            <h4 style="color: #4338CA; margin-bottom: 15px;"><i class="fas fa-brain"></i> Cara Sistem Berpikir</h4>
            <p style="color: #3730A3; font-size: 0.85rem; line-height: 1.6; margin: 0;">
                Sistem menelusuri bagan di atas dari akar paling atas (Root). Keputusan "Rekomendasi", "Alternatif", atau "Hindari" diambil berdasarkan kombinasi nilai <b>Kondisi Mayoritas</b> dan <b>Estimasi Jumlah Kendaraan</b> pada sesi dan lokasi yang dicari.
            </p>
        </div>
        <div style="background: white; padding: 20px; border-radius: 16px;">
            <h4 style="color: #1E293B; margin-bottom: 10px;">Parameter Model</h4>
            <p style="color: #64748B; font-size: 0.85rem; line-height: 1.6;">
                Kriteria Pemisahan: Gini Index<br>
                Kedalaman Pohon: Maksimal sesuai hasil pruning.<br>
                Tujuan: Meminimalkan waktu pencarian parkir mahasiswa.
            </p>
        </div>
    </div>
</div>
@endsection
