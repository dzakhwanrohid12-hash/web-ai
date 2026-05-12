@extends('layouts.admin')

@section('title', 'Import Dataset')
@section('page_title', 'Kelola Dataset CSV')

@section('content')
<div class="dataset-page">
    <div class="dataset-header">
        <div>
            <h1><i class="fas fa-database"></i> Kelola Dataset</h1>
            <p>Upload data parkir historis untuk meningkatkan akurasi model Decision Tree</p>
        </div>
        <div class="stats-badge">
            <i class="fas fa-chart-line"></i> Total Data: <strong>{{ $totalData }}</strong> baris
        </div>
    </div>

    <div class="dataset-grid">
        {{-- Card Upload --}}
        <div class="upload-card">
            <div class="card-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h3>Upload Data Baru</h3>
            <p>Unggah file CSV dengan format yang sesuai. Data akan mempengaruhi prediksi parkir secara real-time.</p>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.dataset.import') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                @csrf
                <div class="file-input-wrapper">
                    <label for="file_csv" class="file-label">
                        <i class="fas fa-folder-open"></i> Pilih File CSV
                    </label>
                    <input type="file" name="file_csv" id="file_csv" accept=".csv" required>
                    <span class="file-name" id="fileName">Tidak ada file dipilih</span>
                    @error('file_csv')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-upload">
                    <i class="fas fa-upload"></i> Upload & Proses
                </button>
            </form>
            <div class="format-info">
                <i class="fas fa-info-circle"></i> Format CSV: <code>lokasi,hari,jam,jenis_kendaraan,label_keputusan</code>
            </div>
        </div>

        {{-- Card Preview Tabel --}}
        <div class="preview-card">
            <div class="preview-header">
                <h3><i class="fas fa-table"></i> Preview Dataset Latih</h3>
                <span class="record-count">{{ $datasets->total() }} record</span>
            </div>
            <div class="table-responsive">
                <table class="dataset-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lokasi</th>
                            <th>Hari & Jam</th>
                            <th>Kendaraan</th>
                            <th>Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datasets as $index => $data)
                        <tr>
                            <td class="text-center">{{ $datasets->firstItem() + $index }}</td>
                            <td><i class="fas fa-map-pin"></i> {{ $data->lokasi }}</td>
                            <td><i class="far fa-calendar-alt"></i> {{ $data->hari }}, {{ \Carbon\Carbon::parse($data->jam)->format('H:i') }}</td>
                            <td>
                                @if($data->jenis_kendaraan == 'mobil')
                                    <i class="fas fa-car"></i> Mobil
                                @else
                                    <i class="fas fa-motorcycle"></i> Motor
                                @endif
                            </td>
                            <td>
                                @php
                                    $label = $data->label_keputusan_final ?? $data->kondisi ?? '';
                                    $badgeClass = match(strtolower($label)) {
                                        'sepi', 'ringan', 'rekomendasi' => 'badge-success',
                                        'sedang', 'alternatif' => 'badge-warning',
                                        'padat', 'hindari' => 'badge-danger',
                                        default => 'badge-neutral'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $label ?: '-' }}</span>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-row">Belum ada data. Silakan upload file CSV.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $datasets->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Halaman Dataset Admin */
    .dataset-page {
        padding: 20px 24px;
        max-width: 1400px;
        margin: 0 auto;
    }
    .dataset-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }
    .dataset-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1E293B, #4F46E5);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 6px;
    }
    .dataset-header p {
        color: #64748B;
        margin: 0;
    }
    .stats-badge {
        background: white;
        border-radius: 40px;
        padding: 8px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        border: 1px solid #E2E8F0;
        font-size: 0.9rem;
    }
    .stats-badge i {
        color: #4F46E5;
    }
    .dataset-grid {
        display: grid;
        grid-template-columns: 1fr 1.8fr;
        gap: 28px;
    }
    /* Upload Card */
    .upload-card, .preview-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.03);
        border: 1px solid #E2E8F0;
        overflow: hidden;
        transition: transform 0.2s;
    }
    .upload-card {
        padding: 28px;
        text-align: center;
    }
    .card-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
        border-radius: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .card-icon i {
        font-size: 28px;
        color: #4F46E5;
    }
    .upload-card h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .upload-card p {
        color: #64748B;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 24px;
    }
    .alert-success, .alert-error {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 0.85rem;
        text-align: left;
    }
    .alert-success {
        background: #DCFCE7;
        color: #166534;
        border: 1px solid #BBF7D0;
    }
    .alert-error {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }
    .upload-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .file-input-wrapper {
        text-align: left;
    }
    .file-label {
        display: inline-block;
        background: #F1F5F9;
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .file-label i {
        margin-right: 6px;
    }
    input[type="file"] {
        display: none;
    }
    .file-name {
        font-size: 0.8rem;
        color: #64748B;
        margin-left: 8px;
    }
    .field-error {
        color: #DC2626;
        font-size: 0.75rem;
        margin-top: 6px;
    }
    .btn-upload {
        background: linear-gradient(135deg, #4F46E5, #7C3AED);
        border: none;
        padding: 12px;
        border-radius: 40px;
        color: white;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(79,70,229,0.3);
    }
    .format-info {
        margin-top: 16px;
        background: #F8FAFC;
        padding: 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        color: #475569;
    }
    /* Preview Card */
    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid #F1F5F9;
        background: #FAFAFE;
    }
    .preview-header h3 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    .record-count {
        background: #E2E8F0;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .dataset-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .dataset-table th {
        text-align: left;
        padding: 14px 16px;
        background: #F8FAFC;
        color: #334155;
        font-weight: 600;
        border-bottom: 1px solid #E2E8F0;
    }
    .dataset-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }
    .dataset-table tbody tr:hover {
        background: #FEFCE8;
    }
    .text-center {
        text-align: center;
    }
    .badge {
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-success { background: #DCFCE7; color: #166534; }
    .badge-warning { background: #FEF3C7; color: #92400E; }
    .badge-danger { background: #FEE2E2; color: #991B1B; }
    .badge-neutral { background: #F1F5F9; color: #475569; }
    .empty-row {
        text-align: center;
        padding: 40px;
        color: #94A3B8;
    }
    .pagination-wrapper {
        padding: 16px 24px;
        border-top: 1px solid #F1F5F9;
        display: flex;
        justify-content: center;
    }
    @media (max-width: 900px) {
        .dataset-grid {
            grid-template-columns: 1fr;
        }
        .dataset-page {
            padding: 16px;
        }
    }
</style>
<script>
    // Menampilkan nama file yang dipilih
    document.getElementById('file_csv')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        document.getElementById('fileName').innerText = fileName ? fileName : 'Tidak ada file dipilih';
    });
</script>
@endpush
