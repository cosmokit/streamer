@extends('admin.layout')

@section('title', 'Прогресс Пользователя')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад к профилю
    </a>
</div>

<div class="mb-4">
    <h2>
        <i class="bi bi-graph-up me-2"></i>
        Прогресс: {{ $user->name }}
    </h2>
    <p class="text-muted">{{ $user->email }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Шаг</th>
                        <th>Описание</th>
                        <th>Статус</th>
                        <th>Начат</th>
                        <th>Завершён</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($learningProgress as $progress)
                    <tr>
                        <td>
                            <strong>{{ $progress->learningStep->title }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ Str::limit($progress->learningStep->description, 60) }}
                            </small>
                        </td>
                        <td>
                            @switch($progress->status)
                                @case('new')
                                    <span class="badge bg-secondary">Новый</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-info">В процессе</span>
                                    @break
                                @case('awaiting_confirmation')
                                    <span class="badge bg-warning">Ожидает подтверждения</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">Выполнено</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($progress->started_at)
                                <small class="text-muted">{{ $progress->started_at->format('d.m.Y H:i') }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($progress->completed_at)
                                <small class="text-muted">{{ $progress->completed_at->format('d.m.Y H:i') }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($progress->status === 'awaiting_confirmation')
                                <form method="POST" action="{{ route('admin.users.progress.confirm', [$user, $progress->id]) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Подтвердить
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Пользователь ещё не начал обучение
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $learningProgress->links() }}
</div>
@endsection
