@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rol Düzenle: {{ $role->name }}</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="roleName" class="form-label">Rol Adı</label>
            <input type="text" class="form-control" id="roleName" name="name" value="{{ $role->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">İzinler</label>
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" 
                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>
@endsection
