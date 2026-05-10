@extends('layouts.admin')

@section('title', 'Import Dataset')
@section('page_title', 'Kelola Dataset CSV')

@push('styles')
<style>
    .form-container { background-color: var(--card-bg); max-width: 600px; padding: 30px; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    input[type="file"] { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-family: 'Poppins', sans-serif; }
    button[type="submit"] { background-color: var(--primary); color: white; padding: 14px 20px; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: 0.3s; }
    button[type="submit"]:hover { background-color: var(--primary-hover); }
    .alert-success { background-color: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; border: 1px solid #10B981; }
    .stats-badge { display: inline-block; background: #EEF2FF; color: var(--primary); padding: 8px 15px; border-radius: 20px; font-weight: 600; margin-bottom: 25px; border: 1px solid #C7D2FE; }
</style>
@endpush

@section('content')
    <div class="form-container">
        <p style="color: var(--text-muted); margin-bottom: 20px;">Upload data CSV terbaru untuk meningkatkan akurasi Prediksi model Decision Tree.</p>

        <div class="stats-badge">
            Total Data Historis Saat Ini: {{ $totalData }} baris
        </div>

        @if(session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.dataset.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file_csv">Pilih File CSV Dataset (.csv)</label>
                <input type="file" name="file_csv" id="file_csv" accept=".csv" required>
                @error('file_csv') <div style="color: #DC2626; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div> @enderror
            </div>

            <button type="submit">Upload & Proses Data</button>
        </form>
    </div>
@endsection