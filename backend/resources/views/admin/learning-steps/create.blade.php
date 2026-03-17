@extends('admin.layout')

@section('title', 'Создать Шаг')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.learning-steps.index') }}" class="btn btn-light shadow-sm" style="font-weight: 600;">
        <i class="bi bi-arrow-left"></i> Назад
    </a>
</div>

<div class="mb-4">
    <h2><i class="bi bi-plus-circle me-2"></i>Создать Шаг Обучения</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.learning-steps.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Заголовок <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Описание <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Внешний URL <span class="text-danger">*</span></label>
                <input type="url" name="external_url" class="form-control @error('external_url') is-invalid @enderror" 
                       value="{{ old('external_url') }}" placeholder="https://youtube.com/..." required>
                @error('external_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Порядок <span class="text-danger">*</span></label>
                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" 
                       value="{{ old('order', 0) }}" min="0" required>
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                       id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Активен</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Создать
                </button>
                <a href="{{ route('admin.learning-steps.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
