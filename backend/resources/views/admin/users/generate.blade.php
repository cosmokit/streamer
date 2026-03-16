@extends('admin.layout')

@section('title', 'Генерация пользователя')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-person-plus me-2"></i>Генерация пользователя</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.generate', ['user' => 0]) }}">
            @csrf

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Username и пароль будут сгенерированы автоматически
            </div>

            <div class="mb-3">
                <label class="form-label">Telegram</label>
                <input type="text" name="telegram" class="form-control @error('telegram') is-invalid @enderror" 
                       value="{{ old('telegram') }}" placeholder="@username или ссылка">
                @error('telegram')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Twitch</label>
                <input type="text" name="twitch" class="form-control @error('twitch') is-invalid @enderror" 
                       value="{{ old('twitch') }}" placeholder="username или ссылка">
                @error('twitch')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_admin" class="form-check-input" id="is_admin" 
                       value="1" {{ old('is_admin') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_admin">
                    Администратор
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Генерировать
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
