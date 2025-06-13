<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Salida</title>
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
        }

        .subtitle th {
            width: 10%;
            vertical-align: top;
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
            padding-right: 15px;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr class="text-center-top">
            <td width="20%">
                <img src="{{ $logoPath }}" alt="Logo de la entidad"><br>
                <p> POLICIA BOLIVIANA <br> COMANDO DEPARTAMENTAL <br> LA PAZ - BOLIVIA </p>
            </td>
            <td width="60%">
                <h2>ENTREGA DE PRODUCTOS</h2>
                <span>Montos expresados en Bolivianos</span>
            </td>
            <td width="20%">
                <div class="footer">
                    <span class="page-number"></span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Subtítulos -->
    <table class="subtitle">
        <tr>
            <th>Entidad:</th>
            <td>COMANDO DEPARTAMENTAL LA PAZ</td>
            <th>Destino:</th>
            <td>{{ $salida->nombre_unidad }}</td>
        </tr>
        <tr>
            <th>Fondo:</th>
            <td>Tesoro General de la Nación</td>
            <th>Tipo:</th>
            <td>Egreso (Pedido)</td>

        </tr>
        <tr>
            <th>Almacén:</th>
            <td>ALMACÉN COMANDO DEPARTAMENTAL</td>
            <th>Nº Pedido:</th>
            <td>{{ $salida->n_pedido }}</td>
        </tr>
        <tr>
            <th>Nº Egreso:</th>
            <td>{{ $salida->id_salida }}</td>
            <th>Fecha:</th>
            <td>{{ $fecha }}</td>
        </tr>
        <tr>
            <th>Glosa:</th>
            <td colspan="3">
                ENTREGA DE
                @foreach ($categorias as $categoria)
                    {{ $loop->first ? '' : ', ' }}{{ $categoria }}
                @endforeach
                SEGÚN STOCK DE ALMACENES. SE FIRMA EL PRESENTE ACTA EN CONSTANCIA DE LA
                ENTREGA, EN CUMPLIMIENTO DE LA HOJA DE RUTA {{ $salida->n_hoja_ruta }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Unidad</th>
                <th>Lote</th>
                <th>Cantidad</th>
                @if ($mostrarCostos)
                    <th>Costo <br> Unitario</th>
                    <th>Costo <br> Total</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $categoriaActual = null;
                $totalCantidad = 0;
                $totalCostoUnitario = 0;
                $totalCostoTotal = 0;
            @endphp

            @foreach ($detalles as $item)
                @if ($categoriaActual !== $item->categoria)
                    @php
                        $categoriaActual = $item->categoria;
                        $codigoCategoria = $item->codigo_categoria;
                    @endphp
                    <tr>
                        <td><strong>{{ $codigoCategoria }}</strong></td>
                        <td colspan="4"><strong>{{ $categoriaActual }}</strong></td>
                        @if ($mostrarCostos)
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endif
                <tr>
                    <td>{{ $item->codigo_producto }}</td>
                    <td>{{ $item->producto }}</td>
                    <td>{{ $item->unidad }}</td>
                    <td>{{ $item->lote }}</td>
                    <td class="text-right">{{ $item->cantidad }}</td>
                    @if ($mostrarCostos)
                        <td class="text-right">{{ number_format($item->costo_u, 2) }}</td>
                        <td class="text-right">{{ number_format($item->cantidad * $item->costo_u, 2) }}</td>
                    @endif
                </tr>
                @php
                    $totalCantidad += $item->cantidad;
                    $totalCostoUnitario += $item->costo_u;
                    $totalCostoTotal += $item->cantidad * $item->costo_u;
                @endphp
            @endforeach
        </tbody>
        <tfoot class="table">
            <tr>
                <th colspan="4">TOTAL GENERAL</th>
                <th class="text-right">{{ $totalCantidad }}</th>
                @if ($mostrarCostos)
                    <th class="text-right">{{ number_format($totalCostoUnitario, 2) }}</th>
                    <th class="text-right">{{ number_format($totalCostoTotal, 2) }}</th>
                @endif
            </tr>
        </tfoot>
    </table>

    <table class="signature">
        <tr>
            <td>Entrege Conforme</td>
            <td>Recibi Conforme</td>
            <td>Vo.Bo.</td>
            <td>Jefe Administrativo</td>
        </tr>
    </table>
</body>

</html>
