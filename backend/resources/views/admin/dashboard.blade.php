@extends('admin.layout')

@section('title', 'Дашборд')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Дашборд</h2>
    <span class="text-muted">{{ now()->format('d.m.Y H:i') }}</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stats-card">
            <i class="bi bi-people fs-1"></i>
            <h3>{{ $stats['users_count'] }}</h3>
            <p>Всего пользователей</p>
            <small>Активных: {{ $stats['active_users'] }}</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="bi bi-camera-video fs-1"></i>
            <h3>{{ $stats['videos_count'] }}</h3>
            <p>Видеозаписей</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="bi bi-file-earmark-image fs-1"></i>
            <h3>{{ $stats['templates_count'] }}</h3>
            <p>Шаблонов</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="bi bi-broadcast fs-1"></i>
            <h3>{{ $stats['streams_count'] }}</h3>
            <p>Запусков стримов</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
            <i class="bi bi-shield-check fs-1"></i>
            <h3>{{ $stats['active_proxies'] }}</h3>
            <p>Активных прокси</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-person-plus me-2"></i>Последние пользователи</h5>
                <div class="list-group list-group-flush">
                    @foreach($recent_users as $user)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $user->name }}</strong><br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div>
                            @if($user->is_admin)
                                <span class="badge bg-danger">Админ</span>
                            @endif
                            @if($user->is_banned)
                                <span class="badge bg-warning">Заблокирован</span>
                            @else
                                <span class="badge bg-success">Активен</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-broadcast me-2"></i>Последние запуски стримов</h5>
                <div class="list-group list-group-flush">
                    @foreach($recent_streams as $stream)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $stream->user->name }}</strong><br>
                                <small class="text-muted">{{ $stream->twitch_url }}</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">День {{ $stream->day_index }}</span>
                            </div>
                        </div>
                        <small class="text-muted">{{ $stream->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
