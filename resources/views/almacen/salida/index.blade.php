@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Salidas</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-white my-auto fw-bold">LISTADO DE SALIDAS</h4>
                <a href="{{ route('salidas.create') }}" class="btn btn-labeled btn-success fw-bold">
                    <span class="btn-label"><i class="bi bi-plus-lg"></i></span>Registrar Salida
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Unidad</th>
                        <th>Fecha</th>
                        <th># Hoja Ruta</th>
                        <th># Pedido</th>
                        <th>Total</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salidas as $item)
                        <tr>
                            <td class="text-center">{{ $item->id_salida }}</td>
                            <td class="ps-2">{{ $item->nombre_unidad }}</td>
                            <td class="text-end pe-2">{{ $item->fecha_hora }}</td>
                            <td class="text-end pe-2">{{ $item->n_hoja_ruta }}</td>
                            <td class="text-end pe-2">{{ $item->n_pedido }}</td>
                            <td class="text-end pe-2">{{ $item->total }}</td>
                            <td class="text-center">
                                <a href="{{ route('salidas.show', $item->id_salida) }}" class="btn btn-warning btn-labeled btn-small my-1">
                                    <span class="btn-label"><i class="bi bi-eye-fill"></i></span>Detalles
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
    <script>
        var successMessage = "{{ session('success') }}";
        var errorMessage = "{{ session('error') }}";
    </script>
@endpush
