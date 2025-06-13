@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-white my-auto">LISTADO DE USUARIOS</h4>
                <form action="{{ route('usuarios.index') }}" method="get">
                    @csrf
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="buscar" placeholder="Buscar usuarios" value="{{ $buscar }}">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <a href="{{ route('usuarios.create') }}" class="btn btn-labeled btn-success">
                <span class="btn-label"><i class="bi bi-plus-circle-fill"></i></span>
                Crear Usuario
            </a>

            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr class="text-center">
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Ci</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $item)
                            <tr class="text-center">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->ci }}</td>
                                <td>
                                    <a href="{{ route('usuarios.edit', $item->id) }}" class="btn btn-labeled btn-warning btn-small">
                                        <span class="btn-label"><i class="bi bi-pen-fill"></i></span>Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-auto">
            {{ $usuarios->links() }}
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        var successMessage = "{{ session('success') }}";
        var errorMessage = "{{ session('error') }}";
    </script>
    <script src="{{ asset('js/notifications.js') }}"></script>
@endpush
