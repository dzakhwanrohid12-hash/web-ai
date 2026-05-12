<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Parkir | Politeknik Caltex Riau</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
</head>
<body>
    <div class="auth-card">
        <div class="logo-section" style="margin-bottom: 25px;">
            <img src="{{ asset('images/logo-pcr.png') }}" alt="Politeknik Caltex Riau" class="logo-img">
            <div class="logo-sub" style="text-transform: none; font-weight: 500; font-size: 0.8rem;">
                Silakan login untuk menyimpan riwayat parkir.
            </div>
        </div>

        {{-- <div class="auth-header">
            <h2>Masuk Sistem</h2>
            <p>Silakan login untuk menyimpan riwayat.</p>
        </div> --}}

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Alamat Email</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@mahasiswa.pcr.ac.id">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="text-center">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a><br>
            <a href="{{ route('parking.index') }}" class="back-link">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
