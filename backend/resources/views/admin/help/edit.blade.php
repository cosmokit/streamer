@extends('admin.layout')

@section('title', 'Редактировать статью')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-pencil me-2"></i>Редактировать статью</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.help.update', $help) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Тег</label>
                <input type="text" name="tag" class="form-control @error('tag') is-invalid @enderror" 
                       value="{{ old('tag', $help->tag) }}" required>
                @error('tag')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Заголовок</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $help->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Текст</label>
                <textarea name="body" class="form-control @error('body') is-invalid @enderror" 
                          rows="6" required>{{ old('body', $help->body) }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Порядок сортировки</label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                       value="{{ old('sort_order', $help->sort_order) }}">
                @error('sort_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить
                </button>
                <a href="{{ route('admin.help.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
