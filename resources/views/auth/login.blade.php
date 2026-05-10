<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4F46E5; --bg-color: #F3F4F6; --card-bg: #FFFFFF; --border: #D1D5DB; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background-color: var(--card-bg); width: 100%; max-width: 400px; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 0.9rem; }
        input { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-family: 'Poppins', sans-serif; }
        button { width: 100%; background-color: var(--primary); color: white; padding: 14px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .error-text { color: #DC2626; font-size: 0.8rem; margin-top: 5px; }
        .text-center { text-align: center; margin-top: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Masuk Sistem</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@mahasiswa.pcr.ac.id" required>
                @error('email') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <div class="text-center">
                Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Daftar di sini</a>
            </div>
        </form>
    </div>
</body>
</html>