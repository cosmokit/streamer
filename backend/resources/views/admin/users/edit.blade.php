@extends('admin.layout')

@section('title', 'Редактировать пользователя')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-pencil me-2"></i>Редактировать пользователя</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Новый пароль (оставьте пустым, чтобы не менять)</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_admin" class="form-check-input" id="is_admin" 
                       value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_admin">
                    Администратор
                </label>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_banned" class="form-check-input" id="is_banned" 
                       value="1" {{ old('is_banned', $user->is_banned) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_banned">
                    Заблокирован
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Причина блокировки</label>
                <textarea name="banned_reason" class="form-control" rows="3">{{ old('banned_reason', $user->banned_reason) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
