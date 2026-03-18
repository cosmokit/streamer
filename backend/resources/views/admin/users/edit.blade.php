@extends('admin.layout')

@section('title', 'Редактировать пользователя')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-pencil me-2"></i>Редактировать пользователя</h2>
</div>

@if(session('generated_credentials'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 15px; line-height: 1.8;">
    <h4 class="alert-heading mb-3">
        <i class="bi bi-check-circle-fill me-2"></i>✅ Успех!
    </h4>
    <p class="mb-3 fw-bold">Пользователь успешно создан!</p>
    <p class="mb-2 fw-semibold">Данные нового пользователя:</p>
    <hr class="my-3">
    <p class="mb-2"><strong>Логин:</strong> <code class="bg-light px-2 py-1 rounded">{{ session('generated_credentials')['username'] }}</code></p>
    <p class="mb-2"><strong>Пароль:</strong> <code class="bg-light px-2 py-1 rounded">{{ session('generated_credentials')['password'] }}</code></p>
    @if(session('generated_credentials')['twitch'])
    <p class="mb-2"><strong>Twitch-канал:</strong> <a href="https://www.twitch.tv/{{ session('generated_credentials')['twitch'] }}" target="_blank">https://www.twitch.tv/{{ session('generated_credentials')['twitch'] }}</a></p>
    @endif
    @if(session('generated_credentials')['telegram'])
    <p class="mb-2"><strong>Telegram:</strong> {{ session('generated_credentials')['telegram'] }}</p>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Telegram <span class="text-muted small">(необязательно)</span></label>
                <input type="text" name="telegram" class="form-control @error('telegram') is-invalid @enderror" 
                       value="{{ old('telegram', $user->telegram) }}" placeholder="@username или ссылка">
                @error('telegram')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Twitch <span class="text-muted small">(необязательно)</span></label>
                <input type="text" name="twitch" class="form-control @error('twitch') is-invalid @enderror" 
                       value="{{ old('twitch', $user->twitch) }}" placeholder="username или ссылка">
                @error('twitch')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Новый пароль (оставьте пустым, чтобы не менять)</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_admin" class="form-check-input" id="is_admin" 
                       value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_admin">
                    Администратор
                </label>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_banned" class="form-check-input" id="is_banned" 
                       value="1" {{ old('is_banned', $user->is_banned) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_banned">
                    Заблокирован
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Причина блокировки</label>
                <textarea name="banned_reason" class="form-control" rows="3">{{ old('banned_reason', $user->banned_reason) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>

@if(!$user->is_admin)
<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-person-circle me-2"></i>Импорсонация
        </h5>
        <p class="text-muted mb-3">Войти под этим пользователем не покидая админскую сессию</p>
        <form method="POST" action="{{ route('admin.users.impersonate', $user) }}">
            @csrf
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-person-circle"></i> Войти как пользователь
            </button>
        </form>
    </div>
</div>
@endif

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-graph-up me-2"></i>Прогресс Обучения
        </h5>
        <div class="mb-3">
            <a href="{{ route('admin.users.progress', $user) }}" class="btn btn-info">
                <i class="bi bi-list-check"></i> Просмотр прогресса
            </a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-hdd-network me-2"></i>Управление прокси
        </h5>
        
        <div class="mb-4 d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.users.proxies', $user) }}" class="btn btn-primary">
                <i class="bi bi-list-ul"></i> Просмотр прокси пользователя ({{ $user->proxies()->count() }})
            </a>
            @php
                $proxyFilePath = storage_path("app/proxy_uploads/{$user->id}/proxies.txt");
                $hasProxyFile = file_exists($proxyFilePath);
            @endphp
            @if($hasProxyFile)
                <a href="{{ route('admin.users.download-proxy-file', $user) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Скачать загруженный файл
                </a>
                <span class="align-self-center text-muted small">
                    <i class="bi bi-check-circle text-success"></i>
                    Файл загружен {{ date('d.m.Y H:i', filemtime($proxyFilePath)) }}
                </span>
            @else
                <span class="align-self-center text-muted small">
                    <i class="bi bi-info-circle"></i> Пользователь не загружал файл прокси
                </span>
            @endif
        </div>
        
        <hr>
        
        <h6 class="mb-2">Генератор прокси</h6>
        <p class="text-muted small mb-3">
            Сгенерировать прокси и добавить в базу данных
        </p>
        <form method="POST" action="{{ route('admin.users.generate-proxies', $user) }}" class="d-flex gap-2 align-items-end">
            @csrf
            <div>
                <label class="form-label small">Количество</label>
                <input type="number" name="count" class="form-control form-control-sm" value="11" min="1" max="100">
            </div>
            <div>
                <label class="form-label small">Статус</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Создать
            </button>
        </form>
    </div>
</div>
@endsection
