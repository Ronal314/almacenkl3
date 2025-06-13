@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Ingresos</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-white my-auto fw-bold">LISTADO DE INGRESOS</h4>
                <a href="{{ route('ingresos.create') }}" class="btn btn-labeled btn-success fw-bold">
                    <span class="btn-label"><i class="bi bi-plus-lg"></i></span>Registrar Ingreso
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Proveedor</th>
                        <th># Factura</th>
                        <th># Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingresos as $item)
                        <tr>
                            <td class="text-center">{{ $item->id_ingreso }}</td>
                            <td class="ps-2">{{ $item->nombre_proveedor }}</td>
                            <td class="text-end pe-2">{{ $item->n_factura }}</td>
                            <td class="text-end pe-2">{{ $item->n_pedido }}</td>
                            <td class="text-end pe-2">{{ $item->fecha_hora }}</td>
                            <td class="text-end pe-2">{{ $item->total }}</td>
                            <td class="text-center">
                                <a href="{{ route('ingresos.show', $item->id_ingreso) }}" class="btn btn-warning btn-labeled btn-small my-1">
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
