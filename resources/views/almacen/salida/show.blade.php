@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('salidas.index') }}" class="link">Salidas</a></li>
    <li class="breadcrumb-item active">Detalles Salida</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <div class="row">
                <div class="col-6">
                    <h5 class="card-title text-white my-auto fw-bold">DETALLES DE LA SALIDA</h5>
                </div>
            </div>
        </div>
        <!-- Cuerpo del card -->
        <div class="card-body d-flex flex-column" style="height: calc(100vh - 120px);">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex">
                        <strong>Destino:</strong><span class="ms-2">{{ $salida->nombre_unidad }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Nº Hoja de Ruta:</strong><span class="ms-2">{{ $salida->n_hoja_ruta }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Nº Pedido:</strong><span class="ms-2">{{ $salida->n_pedido }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex">
                        <strong>Nº Egreso:</strong><span class="ms-2">{{ $salida->id_salida }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Usuario:</strong><span class="ms-2">{{ $salida->nombre_usuario }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Fecha:</strong><span class="ms-2">{{ $salida->fecha_hora }}</span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-between">
                    <a href="{{ route('reporte-salida', ['id' => $salida->id_salida, 'mostrarCostos' => true]) }}" target="_blank"
                        class="btn btn-labeled btn-danger my-auto">
                        <span class="btn-label"><i class="bi bi-file-pdf-fill"></i></span>Con Valorada
                    </a>
                    <a href="{{ route('reporte-salida', ['id' => $salida->id_salida, 'mostrarCostos' => false]) }}" target="_blank"
                        class="btn btn-labeled btn-danger my-auto">
                        <span class="btn-label"><i class="bi bi-file-pdf-fill"></i></span>Sin Valorada
                    </a>
                </div>
            </div>
            <!-- Tabla Responsiva -->
            <div class="table-responsive overflow-auto flex-grow-1 mt-3">
                <table class="table table-hover table-bordered align-middle" id="tableDetalles">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Costo <br> Unitario</th>
                            <th>Costo <br> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $categoriaActual = null;
                            $totalCantidad = 0;
                            $totalCostoUnitario = 0;
                        @endphp
                        @foreach ($detalles as $item)
                            @if ($categoriaActual !== $item->categoria)
                                @php
                                    $categoriaActual = $item->categoria;
                                    $codigoCategoria = $item->codigo_categoria;
                                @endphp
                                <tr>
                                    <td class="ps-2">
                                        <p class="my-1 fw-bold">{{ $codigoCategoria }}</p>
                                    </td>
                                    <td colspan="6" class="ps-2 fw-bold">{{ $categoriaActual }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="ps-2">{{ $item->codigo_producto }}</td>
                                <td class="ps-2">{{ $item->producto }}</td>
                                <td class="ps-2">{{ $item->unidad }}</td>
                                <td class="ps-2">{{ $item->lote }}</td>
                                <td class="text-end pe-4">{{ $item->cantidad }}</td>
                                <td class="text-end pe-4">{{ number_format($item->costo_u, 2) }}</td>
                                <td class="text-end pe-4">
                                    <p class="my-1">{{ number_format($item->cantidad * $item->costo_u, 2) }}</p>
                                </td>
                            </tr>
                            @php
                                $totalCantidad += $item->cantidad;
                                $totalCostoUnitario += $item->costo_u;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-center">TOTAL GENERAL</th>
                            <th class="text-end pe-4">{{ $totalCantidad }}</th>
                            <th class="text-end pe-4">Bs: {{ number_format($totalCostoUnitario, 2) }}</th>
                            <th class="text-end pe-4">Bs: {{ number_format($salida->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
@endsection
