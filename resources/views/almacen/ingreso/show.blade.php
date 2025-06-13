@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ingresos.index') }}" class="link">Ingresos</a></li>
    <li class="breadcrumb-item active">Detalles Ingreso</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <div class="card-header bg-gradient-green">
            <h5 class="text-white my-auto fw-bold">DETALLES DEL INGRESO</h5>
        </div>
        <!-- Cuerpo del card -->
        <div class="card-body d-flex flex-column">
            <div class="row">
                <div class="col-md-5">
                    <div class="d-flex">
                        <strong>Proveedor:</strong><span class="ms-2">{{ $ingreso->nombre_proveedor }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Nº Factura:</strong><span class="ms-2">{{ $ingreso->n_factura }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Nº Pedido:</strong><span class="ms-2">{{ $ingreso->n_pedido }}</span>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex">
                        <strong>Nº Ingreso:</strong><span class="ms-2">{{ $ingreso->id_ingreso }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Usuario:</strong><span class="ms-2">{{ $ingreso->nombre_usuario }}</span>
                    </div>
                    <div class="d-flex">
                        <strong>Fecha:</strong><span class="ms-2">{{ $ingreso->fecha_hora }}</span>
                    </div>
                </div>
            </div>
            <!-- Tabla Responsiva -->
            <div class="table-responsive overflow-auto flex-grow-1 mt-3">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Lote</th>
                            <th>Cantidad <br> Original</th>
                            <th>Cantidad <br> Disponible</th>
                            <th>Costo <br> Unitario</th>
                            <th>Costo <br> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $categoriaActual = null;
                            $totalCantidadOriginal = 0;
                            $totalCantidadDisponible = 0;
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
                                    <td colspan="7" class="ps-2 fw-bold">{{ $categoriaActual }}</strong></td>
                                </tr>
                            @endif
                            <tr>
                                <td class="ps-2">{{ $item->codigo_producto }}</td>
                                <td class="ps-2">{{ $item->producto }}</td>
                                <td class="ps-2">{{ $item->unidad }}</td>
                                <td class="ps-2">{{ $item->lote }}</td>
                                <td class="text-end pe-4">{{ $item->cantidad_original }}</td>
                                <td class="text-end pe-4">{{ $item->cantidad_disponible }}</td>
                                <td class="text-end pe-4">{{ number_format($item->costo_u, 2) }}</td>
                                <td class="text-end pe-4">
                                    <p class="my-1">{{ number_format($item->cantidad_original * $item->costo_u, 2) }}</p>
                                </td>
                            </tr>
                            @php
                                $totalCantidadOriginal += $item->cantidad_original;
                                $totalCantidadDisponible += $item->cantidad_disponible;
                                $totalCostoUnitario += $item->costo_u;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-center">TOTAL GENERAL</th>
                            <th class="text-end pe-4">{{ $totalCantidadOriginal }}</th>
                            <th class="text-end pe-4">{{ $totalCantidadDisponible }}</th>
                            <th class="text-end pe-4">Bs: {{ number_format($totalCostoUnitario, 2) }}</th>
                            <th class="text-end pe-4">
                                Bs: {{ number_format($ingreso->total, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
@endsection
