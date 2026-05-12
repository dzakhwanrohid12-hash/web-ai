<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Sistem Parkir | Politeknik Caltex Riau</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
</head>
<body>
    <div class="auth-card">
        <div class="logo-section" style="margin-bottom: 25px;">
            <img src="{{ asset('images/logo-pcr.png') }}" alt="Politeknik Caltex Riau" class="logo-img">
            <div class="logo-sub" style="text-transform: none; font-weight: 500; font-size: 0.85rem; line-height: 1.5; margin-top: 10px;">
                Silakan lengkapi data untuk mendaftar akun prediksi parkir kampus.
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fauzi" required>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat Email</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@mahasiswa.pcr.ac.id atau Gmail" required>
                </div>
                <small style="color: #64748B; display: block; margin-top: 8px; font-size: 0.75rem; line-height: 1.4;">
                    *Gunakan email domain <b>@mahasiswa.pcr.ac.id</b> untuk otomatis dikenali sebagai Civitas PCR, atau email pribadi sebagai Tamu.
                </small>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label>Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>

        <div class="text-center">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a><br>
            <a href="{{ route('parking.index') }}" class="back-link">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
