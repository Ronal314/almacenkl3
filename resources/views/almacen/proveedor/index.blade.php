@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Proveedores</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-white my-auto fw-bold">LISTADO DE PROVEEDORES</h4>
                <a href="{{ route('proveedores.create') }}" class="btn btn-labeled btn-success fw-bold">
                    <span class="btn-label"><i class="bi bi-plus-lg"></i></span>Crear Proveedor
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Razón Social</th>
                        <th>Nombre</th>
                        <th>Nº NIT</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $item)
                        <tr>
                            <td class="text-center">{{ $item->id_proveedor }}</td>
                            <td class="ps-2">{{ $item->razon_social }}</td>
                            <td class="ps-2">{{ $item->nombre }}</td>
                            <td class="pe-2">{{ $item->nit }}</td>
                            <td class="ps-2">{{ $item->direccion }}</td>
                            <td class="pe-2">{{ $item->telefono }}</td>
                            <td class="text-center">
                                <button class="btn"
                                    onclick="confirmToggleEstado({{ $item->id_proveedor }}, {{ $item->estado }}, '{{ $item->nombre }}', '{{ route('proveedores.toggle', ['id' => $item->id_proveedor]) }}')">
                                    <span class="badge {{ $item->estado == 1 ? 'text-bg-success' : 'text-bg-danger' }}">
                                        {{ $item->estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('proveedores.edit', $item->id_proveedor) }}"
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
