<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Saldo de Almacén</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header img {
            width: 50px;
            height: auto;
        }

        .table td {
            font-size: 8pt;
            border: solid 1px black;
            text-align: left;
            padding-left: 10px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 8pt;
            border: solid 1px black;

        }

        .text-center-top {
            text-align: center;
            vertical-align: top;
        }

        .subtitle th,
        .subtitle td {
            text-align: left;
            font-size: 8pt;
            vertical-align: top;
        }

        .subtitle th {
            width: 15%;
        }

        p {
            font-size: 5pt;
            line-height: 1;
        }

        .signature {
            text-align: center;
            position: fixed;
            bottom: 2%;
            width: 100%;
        }

        .footer span {
            text-align: right;
            vertical-align: top;
        }

        .page-number:before {
            content: "Página " counter(page);
        }

        h2 {
            margin: 0;
        }

        .text-right {
            text-align: right !important;
            padding-right: 10px;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr class="text-center-top">
            <td width="20%">
                <img src="{{ $logoPath }}" alt="Logo de la entidad"><br>
                <p>POLICIA BOLIVIANA <br> COMANDO DEPARTAMENTAL <br> LA PAZ - BOLIVIA </p>
            </td>
            <td width="60%" class="text-center-top">
                <h2>SALDO DE ALMACÉN</h2>
                <span>Montos expresados en Bolivianos</span><br>
                <span> Al: {{ $fecha_fin }}</span>
            </td>
            <td width="20%"> <!-- Pie de página -->
                <div class="footer">
                    <span class="page-number"></span>
                </div>
            </td>
        </tr>
    </table>
    <!-- Subtítulos -->
    <table class="subtitle">
        <tr>
            <th>Almacén:</th>
            <td>ALMACÉN COMANDO DEPARTAMENTAL LA PAZ</td>
            <th>Fecha de Impresión:</th>
            <td>{{ $fecha_impresion }}</td>
        <tr>
        <tr>
            <th>Fondo:</th>
            <td>Tesoro General de la Nación</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Unidad</th>
                <th>Lote</th>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Cantidad <br> Total</th>
                <th>Valor <br> Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categoriaActual = null;
                $totalProducto = [];
                foreach ($resultados['totales_por_producto'] as $total) {
                    $totalProducto[$total->codigo_producto] = $total;
                }

                $productoRowspan = [];
                foreach ($resultados['detalles'] as $detalle) {
                    $productoRowspan[$detalle->codigo_producto] = ($productoRowspan[$detalle->codigo_producto] ?? 0) + 1;
                }
            @endphp

            @foreach ($resultados['detalles'] as $index => $detalle)
                @if ($categoriaActual !== $detalle->categoria)
                    @php
                        $categoriaActual = $detalle->categoria;
                        $totalCategoria = collect($resultados['totales_por_categoria'])->firstWhere('categoria', $categoriaActual);
                        $codigoCategoria = collect($resultados['totales_por_categoria'])->firstWhere('categoria', $categoriaActual);
                    @endphp
                    <tr>
                        <td><strong>{{ $codigoCategoria ? $codigoCategoria->codigo_categoria : '' }}</strong></td>
                        <td colspan="5"><strong>{{ $categoriaActual }}</strong></td>
                        <td class="text-right"><strong>{{ $totalCategoria ? $totalCategoria->total_cantidad_actual : '0' }}</strong></td>
                        <td class="text-right"><strong>{{ $totalCategoria ? $totalCategoria->total_valor_actual : '0.00' }}</strong></td>
                    </tr>
                @endif

                @php
                    $esPrimeraFilaProducto = $index == 0 || $resultados['detalles'][$index - 1]->codigo_producto !== $detalle->codigo_producto;
                    $totalProductoActual = $totalProducto[$detalle->codigo_producto] ?? null;
                @endphp

                <tr>
                    <td>{{ $detalle->codigo_producto }}</td>
                    <td>{{ $detalle->producto }}</td>
                    <td>{{ $detalle->unidad }}</td>
                    <td>{{ $detalle->lote }}</td>
                    <td class="text-right">{{ $detalle->cantidad_actual }}</td>
                    <td class="text-right">{{ $detalle->costo_total_lote }}</td>

                    @if ($esPrimeraFilaProducto)
                        <td class="text-right" rowspan="{{ $productoRowspan[$detalle->codigo_producto] ?? 1 }}">
                            {{ $totalProductoActual->total_cantidad_actual ?? '0' }}
                        </td>
                        <td class="text-right" rowspan="{{ $productoRowspan[$detalle->codigo_producto] ?? 1 }}">
                            {{ $totalProductoActual->total_valor_actual ?? '0.00' }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot class="table">
            <tr>
                <th colspan="6">TOTAL GENERAL</th>
                <th class="text-right">{{ $resultados['total_general']->total_cantidad_actual }}</th>
                <th class="text-right">{{ number_format($resultados['total_general']->total_valor_actual, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
