@extends('admin.layout')

@section('title', 'Видеозаписи')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0"><i class="bi bi-camera-video me-2"></i>Видеозаписи</h2>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить видео
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th width="150">Ссылка</th>
                        <th width="120">Длительность</th>
                        <th width="150">Дата добавления</th>
                        <th width="150">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                    <tr>
                        <td>{{ $video->title }}</td>
                        <td>
                            <a href="{{ $video->url }}" target="_blank" class="text-primary">
                                <i class="bi bi-link-45deg"></i>
                            </a>
                        </td>
                        <td>{{ $video->duration }}</td>
                        <td>{{ $video->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить видео?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Видео не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $videos->links() }}
    </div>
</div>
@endsection
