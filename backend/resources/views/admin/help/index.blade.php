@extends('admin.layout')

@section('title', 'Статьи помощи')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0"><i class="bi bi-question-circle me-2"></i>Статьи помощи</h2>
    <a href="{{ route('admin.help.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить статью
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Порядок</th>
                        <th width="100">Тег</th>
                        <th>Заголовок</th>
                        <th width="150">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $article->sort_order }}</span></td>
                        <td><span class="badge bg-info">{{ $article->tag }}</span></td>
                        <td>{{ $article->title }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.help.edit', $article) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.help.destroy', $article) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить статью?')">
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
                            Статьи не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $articles->links() }}
    </div>
</div>
@endsection
