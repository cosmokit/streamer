@extends('admin.layout')

@section('title', 'Видеозаписи')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Видеозаписи</h1>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Добавить видео
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Ссылка</th>
                        <th>Длительность</th>
                        <th>Дата добавления</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                    <tr>
                        <td>{{ $video->id }}</td>
                        <td>{{ $video->title }}</td>
                        <td>
                            <a href="{{ $video->url }}" target="_blank" class="text-primary">
                                <i class="bi bi-youtube"></i> YouTube
                            </a>
                        </td>
                        <td>{{ $video->duration }}</td>
                        <td>{{ $video->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Удалить это видео?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Видео не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $videos->links() }}
        </div>
    </div>
</div>

<div class="mt-3">
    <div class="alert alert-info">
        <strong>Всего видео:</strong> {{ $videos->total() }} |
        <strong>Общая длительность:</strong> {{ number_format($videos->sum('duration_minutes')) }} мин
    </div>
</div>
@endsection
