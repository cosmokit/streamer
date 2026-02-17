@extends('admin.layout')

@section('title', 'Редактировать шаблон')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-pencil me-2"></i>Редактировать шаблон</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.templates.update', $template) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Категория</label>
                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" 
                       value="{{ old('category', $template->category) }}" required>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $template->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" required>{{ old('description', $template->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Ссылка для скачивания</label>
                <input type="url" name="download_url" class="form-control @error('download_url') is-invalid @enderror" 
                       value="{{ old('download_url', $template->download_url) }}" required>
                @error('download_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить
                </button>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
