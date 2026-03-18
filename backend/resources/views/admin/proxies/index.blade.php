@extends('admin.layout')

@section('title', 'Управление прокси')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-hdd-network me-2"></i>Управление прокси</h2>
    <p class="text-muted">Для генерации прокси перейдите на страницу конкретного пользователя</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="username" class="form-control" placeholder="Поиск по username..." value="{{ request('username') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Ожидают активации</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Активные</option>
                    <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Онлайн</option>
                    <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Офлайн</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Фильтр
                </button>
            </div>
            @if(request('username') || request('status'))
            <div class="col-md-3">
                <a href="{{ route('admin.proxies.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-x-lg"></i> Сбросить
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Пользователь</th>
                        <th>IP адрес</th>
                        <th>Порт</th>
                        <th>Логин</th>
                        <th>Пароль</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proxies as $proxy)
                    <tr>
                        <td>{{ $proxies->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $proxy->user->name }}</strong>
                        </td>
                        <td><code>{{ $proxy->host }}</code></td>
                        <td><code>{{ $proxy->port }}</code></td>
                        <td><code class="text-muted small">{{ $proxy->username ?? '—' }}</code></td>
                        <td><code class="text-muted small">{{ $proxy->password ? '••••••' : '—' }}</code></td>
                        <td>
                            @if($proxy->status === 'pending')
                                <span class="badge bg-warning">Ожидает</span>
                            @elseif($proxy->status === 'active')
                                <span class="badge bg-primary">Активный</span>
                            @elseif($proxy->status === 'online')
                                <span class="badge bg-success">Онлайн</span>
                            @elseif($proxy->status === 'offline')
                                <span class="badge bg-danger">Офлайн</span>
                            @endif
                        </td>
                        <td><small>{{ $proxy->created_at->format('d.m.Y H:i') }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($proxy->status === 'pending')
                                    <form method="POST" action="{{ route('admin.proxies.activate', $proxy) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Активировать">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.proxies.deactivate', $proxy) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning" title="Деактивировать">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.proxies.destroy', $proxy) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить прокси?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Прокси не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $proxies->links() }}
    </div>
</div>
@endsection
