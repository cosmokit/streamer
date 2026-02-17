@extends('admin.layout')

@section('title', 'Пользователи')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0"><i class="bi bi-people me-2"></i>Пользователи</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Поиск по имени или email..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Поиск
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i> Сбросить
                    </a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Видео</th>
                        <th>Прокси</th>
                        <th>Стримы</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            {{ $user->name }}
                            @if($user->is_admin)
                                <span class="badge bg-danger ms-1">Админ</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-info">{{ $user->videos_count }}</span></td>
                        <td><span class="badge bg-secondary">{{ $user->proxies_count }}</span></td>
                        <td><span class="badge bg-primary">{{ $user->stream_runs_count }}</span></td>
                        <td>
                            @if($user->is_banned)
                                <span class="badge bg-warning">Заблокирован</span>
                            @else
                                <span class="badge bg-success">Активен</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $user->is_banned ? 'success' : 'warning' }}" 
                                            onclick="return confirm('Уверены?')">
                                        <i class="bi bi-{{ $user->is_banned ? 'unlock' : 'lock' }}"></i>
                                    </button>
                                </form>
                                @if(!$user->is_admin)
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить пользователя?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Пользователи не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</div>
@endsection
