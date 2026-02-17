@extends('admin.layout')

@section('title', 'Шаблоны')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0"><i class="bi bi-file-earmark-image me-2"></i>Шаблоны</h2>
    <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить шаблон
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Категория</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th width="150">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                    <tr>
                        <td><span class="badge bg-info">{{ $template->category }}</span></td>
                        <td>{{ $template->name }}</td>
                        <td>{{ Str::limit($template->description, 50) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.templates.destroy', $template) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить шаблон?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Шаблоны не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $templates->links() }}
    </div>
</div>
@endsection
