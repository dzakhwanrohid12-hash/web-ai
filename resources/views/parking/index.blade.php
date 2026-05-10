<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Prediksi Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #F3F4F6;
            --card-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border-color: #D1D5DB;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: var(--card-bg);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-weight: 600;
        }

        p.subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="time"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        button {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid #10B981;
        }

        .result-box {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 6px;
            display: inline-block;
            border: 2px dashed #065F46;
        }
        
        .error-text {
            color: #DC2626;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .map-wrapper {
            position: relative;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid var(--border-color);
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .map-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }

        .map-pin {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            transform: translate(-50%, -50%); /* Agar titik pusatnya pas */
            cursor: pointer;
            transition: transform 0.2s;
        }

        .map-pin:hover {
            transform: translate(-50%, -50%) scale(1.3);
        }

        .pin-label {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #1F2937;
            pointer-events: none;
            transform: translate(-50%, -30px);
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <div class="container">
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" style="display:inline-block; margin-bottom: 15px; color: var(--primary);">Masuk ke Dashboard Admin</a>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background: transparent; border: none; color: inherit; font-size: inherit; cursor: pointer; text-decoration: underline;">
                Keluar (Logout)
            </button>
        </form>
        <h2>Cek Status Parkir</h2>
        <p class="subtitle">Sistem Prediksi Berbasis Decision Tree</p>

        @if(session('status_lokasi'))
            <h4 style="margin-top: 30px; margin-bottom: 15px; font-weight: 600; text-align: center;">Denah Rekomendasi Visual</h4>
            
            <div class="map-wrapper">
                <img src="{{ asset('images/denah-parkir.jpeg') }}" alt="Denah Parkir">

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
                            if($status == 'Rekomendasi') $warna = '#10B981';
                            elseif($status == 'Alternatif') $warna = '#F59E0B';
                            elseif($status == 'Hindari') $warna = '#EF4444';
                        @endphp
                        
                        <div class="map-pin" 
                             style="top: {{ $posisi['top'] }}; left: {{ $posisi['left'] }}; background-color: {{ $warna }};" 
                             title="{{ $namaLokasi }} - {{ $status }}">
                        </div>
                        
                        <div class="pin-label" style="top: {{ $posisi['top'] }}; left: {{ $posisi['left'] }};">
                            {{ $namaLokasi }}
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error" style="background: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('parking.predict') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="hari">Hari Kedatangan</label>
                <select name="hari" id="hari" required>
                    <option value="" disabled selected>Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                </select>
                @error('hari') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="waktu">Estimasi Waktu</label>
                <select name="waktu" id="waktu" required>
                    <option value="" disabled selected>Pilih Waktu</option>
                    <option value="pagi">Pagi (Sekitar 08:30)</option>
                    <option value="siang">Siang (Sekitar 12:30)</option>
                    <option value="sore">Sore (Sekitar 16:30)</option>
                </select>
                @error('waktu') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="jenis_kendaraan">Jenis Kendaraan</label>
                <select name="jenis_kendaraan" id="jenis_kendaraan" required>
                    <option value="" disabled selected>Pilih Kendaraan</option>
                    <option value="sepeda motor">Sepeda Motor</option>
                    <option value="mobil">Mobil</option>
                </select>
                @error('jenis_kendaraan') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <button type="submit">Cek Rekomendasi Parkir</button>
        </form>
    </div>

</body>
</html>