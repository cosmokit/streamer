@extends('admin.layout')

@section('title', 'Прокси пользователя: ' . $user->name)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-hdd-network me-2"></i>Прокси: {{ $user->name }}</h2>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Назад к профилю
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Всего прокси: <span class="badge bg-primary">{{ $proxies->total() }}</span></h5>
                <div class="d-flex gap-3 mt-2">
                    <span class="text-muted small">
                        <i class="bi bi-clock-history"></i> Ожидают: {{ $user->proxies()->where('status', 'pending')->count() }}
                    </span>
                    <span class="text-muted small">
                        <i class="bi bi-check-circle"></i> Активных: {{ $user->proxies()->where('status', 'active')->count() }}
                    </span>
                    <span class="text-muted small">
                        <i class="bi bi-circle-fill text-success"></i> Онлайн: {{ $user->proxies()->where('status', 'online')->count() }}
                    </span>
                    <span class="text-muted small">
                        <i class="bi bi-circle-fill text-danger"></i> Офлайн: {{ $user->proxies()->where('status', 'offline')->count() }}
                    </span>
                </div>
            </div>
            @if($proxies->total() > 0)
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('admin.users.activate-all-proxies', $user) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Активировать все прокси со статусом Ожидает и Офлайн?')">
                        <i class="bi bi-check-circle"></i> Активировать все
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.users.deactivate-all-proxies', $user) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Деактивировать все активные прокси и прокси онлайн?')">
                        <i class="bi bi-x-circle"></i> Деактивировать все
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.users.delete-all-proxies', $user) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить ВСЕ прокси этого пользователя? Это действие нельзя отменить!')">
                        <i class="bi bi-trash"></i> Удалить все
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IP адрес</th>
                        <th>Порт</th>
                        <th>Логин</th>
                        <th>Пароль</th>
                        <th>Статус</th>
                        <th>Дата добавления</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proxies as $proxy)
                    <tr>
                        <td>{{ $proxy->id }}</td>
                        <td><code>{{ $proxy->host }}</code></td>
                        <td><code>{{ $proxy->port }}</code></td>
                        <td><code class="small">{{ $proxy->username ?? '—' }}</code></td>
                        <td><code class="small text-muted">{{ $proxy->password ? substr($proxy->password, 0, 4) . '••••' : '—' }}</code></td>
                        <td>
                            <form method="POST" action="{{ route('admin.proxies.update-status', $proxy) }}" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="this.form.submit()">
                                    <option value="pending" {{ $proxy->status === 'pending' ? 'selected' : '' }}>⏳ Ожидает</option>
                                    <option value="active" {{ $proxy->status === 'active' ? 'selected' : '' }}>✅ Активный</option>
                                    <option value="online" {{ $proxy->status === 'online' ? 'selected' : '' }}>🟢 Онлайн</option>
                                    <option value="offline" {{ $proxy->status === 'offline' ? 'selected' : '' }}>🔴 Офлайн</option>
                                </select>
                            </form>
                        </td>
                        <td><small>{{ $proxy->created_at->format('d.m.Y H:i') }}</small></td>
                        <td>
                            <form method="POST" action="{{ route('admin.proxies.destroy', $proxy) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Удалить прокси?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            У пользователя нет прокси
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
