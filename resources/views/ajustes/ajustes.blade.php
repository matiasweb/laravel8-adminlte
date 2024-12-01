@extends('adminlte::page')

@section('title', 'Ajustes > General')

@section('content_header')
<h1>Ajustes generales</h1>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="p-4 sm:p-8 bg-light shadow sm:rounded-lg mb-3">
            <h2>Mi perfil</h2>
            <form method="post" action="{{ route('ajustes.perfil') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}"
                        required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Actualizar mis datos</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="p-4 sm:p-8 bg-light shadow sm:rounded-lg mb-3">
            <h2>Preferencias</h2>
            <form method="post" action="{{ route('ajustes.preferencias') }}">
                @csrf
                <div class="mb-3">
                    <label for="theme" class="form-label">Tema</label>
                    <select name="theme" id="theme" class="form-control">
                        <option value="light" {{ ($preferences['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light
                        </option>
                        <option value="dark" {{ ($preferences['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Guardar preferencias</button>
            </form>
        </div>
    </div>
</div>
@stop