@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Categorías</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-white my-auto fw-bold">LISTADO DE CATEGORÍAS</h4>
                <a href="{{ route('categorias.create') }}" class="btn btn-labeled btn-success fw-bold">
                    <span class="btn-label"><i class="bi bi-plus-lg"></i></span>Crear Categoría
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $item)
                        <tr>
                            <td class="text-center">{{ $item->id_categoria }}</td>
                            <td class="ps-4">{{ $item->codigo }}</td>
                            <td class="ps-4">{{ $item->descripcion }}</td>
                            <td class="text-center">
                                <button class="btn"
                                    onclick="confirmToggleEstado({{ $item->id_categoria }}, {{ $item->estado }}, '{{ $item->descripcion }}', '{{ route('categorias.toggle', ['id' => $item->id_categoria]) }}')">
                                    <span class="badge {{ $item->estado == 1 ? 'text-bg-success' : 'text-bg-danger' }}">
                                        {{ $item->estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('categorias.edit', $item->id_categoria) }}"
                                    class="btn btn-labeled btn-warning btn-small">
                                    <span class="btn-label"><i class="bi bi-pen-fill"></i></span>Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
@push('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        var successMessage = "{{ session('success') }}";
        var errorMessage = "{{ session('error') }}";
    </script>
@endpush
