@extends('admin.layout')

@section('title', 'Шаги Обучения')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-ladder me-2"></i>Шаги Обучения</h2>
    <a href="{{ route('admin.learning-steps.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Создать Шаг
    </a>
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
                        <th>№</th>
                        <th>Заголовок</th>
                        <th>Описание</th>
                        <th>URL</th>
                        <th>Порядок</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($steps as $step)
                    <tr>
                        <td>{{ $step->id }}</td>
                        <td>{{ $step->title }}</td>
                        <td><small class="text-muted">{{ Str::limit($step->description, 50) }}</small></td>
                        <td>
                            <a href="{{ $step->external_url }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </td>
                        <td><span class="badge bg-secondary">{{ $step->order }}</span></td>
                        <td>
                            @if($step->is_active)
                                <span class="badge bg-success">Активен</span>
                            @else
                                <span class="badge bg-secondary">Неактивен</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.learning-steps.edit', $step) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.learning-steps.destroy', $step) }}" 
                                      onsubmit="return confirm('Удалить шаг?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Шаги не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $steps->links() }}
</div>
@endsection
