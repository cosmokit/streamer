@extends('admin.layout')

@section('title', 'Редактировать видео')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Редактировать видео</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.videos.update', $video) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title', $video->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">Ссылка на YouTube</label>
                <input type="url" class="form-control @error('url') is-invalid @enderror" 
                       id="url" name="url" value="{{ old('url', $video->url) }}" required>
                @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Длительность</label>
                <input type="text" class="form-control @error('duration') is-invalid @enderror" 
                       id="duration" name="duration" value="{{ old('duration', $video->duration) }}" required>
                @error('duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Формат: HH:MM:SS</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Сохранить
            </button>
        </form>
    </div>
</div>
@endsection
