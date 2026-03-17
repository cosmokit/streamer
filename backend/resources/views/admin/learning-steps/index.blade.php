@extends('admin.layout')

@section('title', 'Шаги Обучения')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0"><i class="bi bi-ladder me-2"></i>Шаги Обучения</h2>
    <a href="{{ route('admin.learning-steps.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Создать Шаг
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Порядок</th>
                        <th>Заголовок</th>
                        <th>Описание</th>
                        <th width="80">URL</th>
                        <th width="100">Статус</th>
                        <th width="150">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($steps as $step)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $step->order }}</span></td>
                        <td>{{ $step->title }}</td>
                        <td>{{ Str::limit($step->description, 60) }}</td>
                        <td>
                            <a href="{{ $step->external_url }}" target="_blank" class="text-primary">
                                <i class="bi bi-link-45deg"></i>
                            </a>
                        </td>
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
                                <form method="POST" action="{{ route('admin.learning-steps.destroy', $step) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить шаг?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Шаги не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $steps->links() }}
    </div>
</div>
@endsection
