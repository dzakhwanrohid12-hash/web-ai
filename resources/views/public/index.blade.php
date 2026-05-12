@extends('layouts.public')
@section('title', 'Pencarian Parkir - Beranda')

@section('content')
<div class="hero-parking">
    <div class="siluet siluet-left"><i class="fas fa-parking"></i> <i class="fas fa-traffic-light"></i></div>
    <div class="siluet siluet-right"><i class="fas fa-road"></i> <i class="fas fa-car"></i></div>
    <div class="siluet-mobil"><i class="fas fa-car-side"></i></div>
    <div class="siluet-motor"><i class="fas fa-motorcycle"></i></div>

    <div class="search-card">
        <div class="search-header">
            <h2>Cari Lokasi Parkir</h2>
            <p><i class="fas fa-robot"></i> Prediksi Berbasis Decision Tree <i class="fas fa-chart-line"></i></p>
        </div>

        @if(session('error'))
            <div class="alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif

        <form action="{{ route('parking.predict') }}" method="POST">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Hari Kedatangan</label>
                <div class="input-icon">
                    <i class="fas fa-calendar-week"></i>
                    <select name="hari" id="hari" class="form-control" required>
                        <option value="" disabled selected>Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-clock"></i> Estimasi Waktu Tiba</label>
                <div class="input-icon">
                    <i class="fas fa-hourglass-half"></i>
                    <select name="waktu" id="waktu" class="form-control" required>
                        <option value="" disabled selected>Pilih Jam (07:00 - 17:00)</option>
                        @for ($h = 7; $h <= 17; $h++)
                            @foreach (['00', '15', '30', '45'] as $m)
                                @if ($h == 17 && $m != '00') @continue @endif
                                @php $jamFormat = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $m; @endphp
                                <option value="{{ $jamFormat }}">{{ $jamFormat }} WIB</option>
                            @endforeach
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-motorcycle"></i> Jenis Kendaraan</label>
                <div class="input-icon">
                    <i class="fas fa-car"></i>
                    <select name="jenis_kendaraan" id="jenis_kendaraan" class="form-control" required>
                        <option value="" disabled selected>Pilih Kendaraan</option>
                        <option value="sepeda motor">Sepeda Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-submit"><i class="fas fa-search-location"></i> Analisis Kepadatan</button>
        </form>
    </div>
</div>

@if(session('status_lokasi'))
<div id="resultModal" class="modal show">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2 style="font-size: 1.6rem; font-weight: 700; background: linear-gradient(135deg, #1E293B 0%, #4F46E5 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">Rekomendasi Area Parkir</h2>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 5px;">Hasil analisis untuk kendaraan <b>{{ request('jenis_kendaraan') }}</b> pada hari <b>{{ request('hari') }}</b>.</p>

        <div class="modal-body">
            <div class="map-container">
                <div class="map-wrapper">
                    <img src="{{ asset('images/denah-parkir.jpeg') }}" alt="Denah Parkir Kampus">
                    @php
                        $statusLokasi = session('status_lokasi');
                        $koordinatPeta = [
                            'Gedung Utama'      => ['top' => '55%', 'left' => '28%'],
                            'Gedung Utama 2'    => ['top' => '50%', 'left' => '50%'],
                            'Workshop Industri' => ['top' => '35%', 'left' => '60%'],
                            'Belakang kantin'   => ['top' => '50%', 'left' => '70%'],
                            'Samping GOR'       => ['top' => '60%', 'left' => '13%'],
                            'Depan GSG'         => ['top' => '40%', 'left' => '30%'],
                            'Parkir Depan 1'    => ['top' => '73%', 'left' => '25%'],
                            'Parkir Depan 2'    => ['top' => '73%', 'left' => '52%'],
                        ];
                    @endphp
                    @foreach($koordinatPeta as $namaLokasi => $posisi)
                        @if(isset($statusLokasi[$namaLokasi]))
                            @php
                                $status = $statusLokasi[$namaLokasi];
                                $warna = '#9CA3AF';
                                $animasi = '';
                                if($status == 'Rekomendasi') { $warna = '#10B981'; $animasi = 'pin-rekomendasi'; }
                                elseif($status == 'Alternatif') { $warna = '#F59E0B'; }
                                elseif($status == 'Hindari') { $warna = '#EF4444'; }
                            @endphp
                            <div class="map-pin {{ $animasi }}" style="top: {{ $posisi['top'] }}; left: {{ $posisi['left'] }}; background-color: {{ $warna }};" title="{{ $namaLokasi }} - {{ $status }}"></div>
                            <div class="pin-label" style="top: {{ $posisi['top'] }}; left: {{ $posisi['left'] }};">{{ $namaLokasi }}</div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="analysis-container">
                <div class="info-box" style="background: #F0FDF4; border-color: #BBF7D0;">
                    <div class="info-title" style="color: #166534;">✅ Rekomendasi Utama</div>
                    <ul class="list-hasil">
                        @forelse(session('hasil_list')['Rekomendasi'] ?? [] as $loc) <li>{{ $loc }}</li>
                        @empty <li style="list-style:none; padding-left:0; color:#6B7280; font-weight:400;">Tidak ada area sepi</li> @endforelse
                    </ul>
                </div>

                <div class="info-box" style="background: #FFFBEB; border-color: #FDE68A;">
                    <div class="info-title" style="color: #92400E;">⚠️ Area Alternatif</div>
                    <ul class="list-hasil">
                        @forelse(session('hasil_list')['Alternatif'] ?? [] as $loc) <li>{{ $loc }}</li>
                        @empty <li style="list-style:none; padding-left:0; color:#6B7280; font-weight:400;">Tidak ada area alternatif</li> @endforelse
                    </ul>
                </div>

                <div class="note-box">
                    <strong style="color: #1E293B;">💡 Penjelasan Sistem:</strong><br>
                    Berdasarkan model *Decision Tree*, area "Sepi" berarti ketersediaan slot > 60%. Hindari titik merah karena diprediksi padat.
                </div>

                <div class="legend-container">
                    <div class="legend-item"><span class="dot" style="background: #10B981;"></span> Sepi (Rekomendasi)</div>
                    <div class="legend-item"><span class="dot" style="background: #F59E0B;"></span> Sedang</div>
                    <div class="legend-item"><span class="dot" style="background: #EF4444;"></span> Padat</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- ========== BAGIAN CARA KERJA - REDESIGN ========== --}}

<div class="how-it-works">
    <div style="text-align: center;">
        <span class="section-badge"><i class="fas-regular fa-circle-question"></i> Panduan Cepat</span>
        <h3>Bagaimana Sistem Ini Bekerja?</h3>
        <p class="section-subtitle">Hanya 3 langkah mudah untuk mendapatkan rekomendasi parkir terbaik</p>
    </div>

    <div class="card-grid">
        <div class="work-card">
            <div class="card-step">1</div>
            <div class="card-icon">
                <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
            </div>
            <h4>Input Jadwal</h4>
            <p>Masukkan rencana hari dan jam kedatangan Anda ke kampus Politeknik Caltex Riau.</p>
        </div>

        <div class="work-card">
            <div class="card-step">2</div>
            <div class="card-icon">
                <i class="fas fa-brain" style="color: var(--primary);"></i>
            </div>
            <h4>Analisis AI</h4>
            <p>Algoritma Decision Tree akan mencocokkan jadwal Anda dengan pola historis kepadatan parkir.</p>
        </div>

        <div class="work-card">
            <div class="card-step">3</div>
            <div class="card-icon">
                <i class="fas fa-map-marked-alt" style="color: #10B981;"></i>
            </div>
            <h4>Dapatkan Rekomendasi</h4>
            <p>Sistem menampilkan area parkir yang paling sepi dan area padat yang harus dihindari.</p>
        </div>
    </div>
</div>

    <div id="mapOnlyModal" class="modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6);">
        <div class="modal-content" style="background: white; margin: 5% auto; padding: 20px; border-radius: 16px; max-width: 700px; position: relative;">
            <span onclick="closeMapModal()" style="position: absolute; right: 20px; top: 15px; font-size: 24px; cursor: pointer; color: #64748B;">&times;</span>
            <h2 style="margin-bottom: 15px; font-size: 1.4rem; color: #1E293B;">Peta Denah Parkir PCR</h2>
            <img src="{{ asset('images/denah-parkir.jpeg') }}" alt="Denah Kampus" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        </div>
    </div>

    {{-- ========== FOOTER REDESIGN ========== --}}
<footer class="modern-footer">
    <div class="footer-container">
        <div class="footer-grid">
            {{-- Kolom 1: Logo & Deskripsi --}}
            <div class="footer-col">
                <img src="{{ asset('images/logo-pcr.png') }}" alt="Logo PCR" class="footer-logo-img">
                <p>Sistem Prediksi Parkir Cerdas berbasis Decision Tree untuk membantu civitas akademika menemukan area parkir terbaik.</p>
            </div>

            {{-- Kolom 2: Tautan Cepat --}}
            <div class="footer-col">
                <h4>Tautan Cepat</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('parking.index') }}"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="javascript:void(0)" onclick="openMapModal()"><i class="fas fa-map"></i> Denah Kampus</a></li>
                    @auth
                    <li><a href="{{ route('user.history') }}"><i class="fas fa-history"></i> Riwayat Saya</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Kolom 3: Informasi --}}
            <div class="footer-col">
                <h4>Informasi</h4>
                <p><i class="fas fa-map-marker-alt"></i> Politeknik Caltex Riau</p>
                <p><i class="fas fa-clock"></i> Senin - Jumat, 07:00 - 17:00</p>
                <p><i class="fas fa-envelope"></i> parkir@pcr.ac.id</p>
            </div>

            {{-- Kolom 4: Media Sosial --}}
            <div class="footer-col">
                <h4>Ikuti Kami</h4>
                <div class="social-icons">
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Sistem Rekomendasi Parkir Cerdas. Hak Cipta Dilindungi.</p>
            <p>Dikembangkan untuk Civitas Akademika Politeknik Caltex Riau.</p>
        </div>
    </div>
</footer>
@endsection
@push('scripts')
<script src="{{ asset('js/public.js') }}"></script>
@endpush
