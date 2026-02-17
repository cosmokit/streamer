<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ-панель | ProfitStream</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a0f2e 0%, #0f1419 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            background: rgba(20, 15, 35, 0.9);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(139, 92, 246, 0.15);
            max-width: 420px;
            width: 100%;
        }
        .login-title {
            background: linear-gradient(135deg, #a78bfa 0%, #86efac 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .form-control {
            background: rgba(30, 25, 45, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.3);
            color: #e5e7eb;
            padding: 12px 16px;
            border-radius: 8px;
        }
        .form-control:focus {
            background: rgba(30, 25, 45, 0.9);
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.1);
            color: #e5e7eb;
        }
        .btn-login {
            background: linear-gradient(135deg, #7c3aed 0%, #86efac 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3);
        }
        .text-muted {
            color: #9ca3af !important;
        }
        .invalid-feedback {
            color: #f87171;
        }
        label {
            color: #d1d5db;
            font-weight: 500;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h1 class="login-title">Админ-панель</h1>
            <p class="text-muted">ProfitStream Management</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171;">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-muted" for="remember">
                    Запомнить меня
                </label>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Войти
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="/" class="text-muted" style="text-decoration: none;">
                <i class="bi bi-arrow-left me-1"></i>На главную
            </a>
        </div>
    </div>
</body>
</html>
