{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Produksi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Hanken Grotesk', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface, #f7f9fe);
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 40px 32px;
            border: 1px solid #e2e3e0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo .icon {
            width: 120px;
            height: 120px;
            background: #1d4ed8;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            padding: 10px;
            margin-bottom: 12px;
        }
        .login-logo h1 {
            font-size: 20px;
            font-weight: 700;
            color: #181c20;
            margin: 0;
        }
        .login-logo p {
            font-size: 14px;
            color: #434655;
            margin: 4px 0 0;
        }
        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #181c20;
        }
        .form-control {
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 14px;
            padding: 10px 14px;
            border: 1px solid #e2e3e0;
            border-radius: 0.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29,78,216,0.12);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 0.5rem;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #0037b0;
        }
        .alert { border-radius: 0.5rem; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div class="icon"><div style="width: 120px; height: 120px; display:flex; align-items:center; justify-content:center;">
                    <img src="{{ asset('images/logoindorisakti3.png') }}" alt="Logo" 
                        style="width:100%; height:100%; object-fit:contain;">
                </div></i></div>
                <h1>PT INDO RISAKTI</h1>
                <p>Sistem Pelaporan Produksi</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                           id="username" name="username" value="{{ old('username') }}" 
                           placeholder="Masukkan username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>