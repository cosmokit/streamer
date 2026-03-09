@extends('admin.layout')

@section('title', 'Добавить видео')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Добавить видео</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.videos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">Ссылка на YouTube</label>
                <input type="url" class="form-control @error('url') is-invalid @enderror" 
                       id="url" name="url" value="{{ old('url') }}" 
                       placeholder="https://www.youtube.com/watch?v=..." required>
                @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Длительность</label>
                <input type="text" class="form-control @error('duration') is-invalid @enderror" 
                       id="duration" name="duration" value="{{ old('duration') }}" 
                       placeholder="HH:MM:SS или H:MM:SS" required>
                @error('duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Формат: HH:MM:SS (например, 1:23:45 или 10:15:30)</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Сохранить
            </button>
        </form>
    </div>
</div>
@endsection
