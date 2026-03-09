@extends('admin.layout')

@section('title', 'Настройки')

@section('content')
<div class="mb-4">
    <h2>
        <i class="bi bi-gear me-2"></i>
        Настройки Системы
    </h2>
    <p class="text-muted">Управление API ключами и интеграциями</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-plug me-2"></i>
            Интеграция Nezhna
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="mb-3">
                <label for="nezhna_api_key" class="form-label">API ключ Nezhna</label>
                <input 
                    type="text" 
                    class="form-control @error('nezhna_api_key') is-invalid @enderror" 
                    id="nezhna_api_key" 
                    name="nezhna_api_key" 
                    value="{{ old('nezhna_api_key', $nezhnaApiKey) }}"
                    placeholder="Вставьте API ключ от Nezhna"
                >
                @error('nezhna_api_key')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    API ключ используется для запуска трафика через сервис Nezhna при старте стрима
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>
                Сохранить
            </button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-info-circle me-2"></i>
            Информация о сервисе
        </h5>
    </div>
    <div class="card-body">
        <p class="mb-2"><strong>Nezhna</strong> - сервис для управления трафиком стримов.</p>
        <p class="text-muted small mb-0">
            При запуске стрима пользователем, система автоматически отправляет запрос в Nezhna API 
            для запуска трафика на указанный Twitch канал пользователя.
        </p>
    </div>
</div>
@endsection
