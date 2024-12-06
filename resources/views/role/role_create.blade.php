@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rol Ekle</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="roleName" class="form-label">Rol Adı</label>
            <input type="text" class="form-control" id="roleName" name="name" required>
        </div>
        <div class="mb-3">
            <label for="copyFromRole" class="form-label">Kopyala Rol</label>
            <select class="form-select" id="copyFromRole" name="copy_role_id">
                <option value="">Seçiniz</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">İzinler</label>
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="permission-{{ $permission->name }}" name="permissions[]" value="{{ $permission->name }}">
                    <label class="form-check-label" for="permission-{{ $permission->name }}">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Ekle</button>
    </form>
</div>
@endsection
