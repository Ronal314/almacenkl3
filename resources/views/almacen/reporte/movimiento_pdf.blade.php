<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Movimientos</title>
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
                <h2>MOVIMIENTO DE ALMACENES</h2>
                <span>Montos expresados en Bolivianos</span><br>
                <span> Del: {{ $fecha_inicio }} Al: {{ $fecha_fin }}</span>
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
            <th>Fondo:</th>
            <td>Tesoro General de la Nación</td>
        <tr>

        </tr>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th rowspan="2">Código</th>
                <th rowspan="2">Producto</th>
                <th rowspan="2">Lote</th>
                <th rowspan="2">Fecha</th>
                <th colspan="2">Saldo Inicial</th>
                <th colspan="2">Ingresos</th>
                <th colspan="2">Salidas</th>
                <th colspan="2">Saldo Final</th>
            </tr>
            <tr>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Cantidad</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $item)
                <tr>
                    <td>{{ $item->Codigo }}</td>
                    <td>{{ $item->Producto }}</td>
                    <td>{{ $item->Lote }}</td>
                    <td>{{ $item->Fecha_Movimiento }}</td>
                    <td class="text-right">{{ $item->Saldo_Inicial }}</td>
                    <td class="text-right">{{ $item->Costo_Inicial }}</td>
                    <td class="text-right">{{ $item->Ingresos_Cantidad }}</td>
                    <td class="text-right">{{ $item->Ingresos_Costo }}</td>
                    <td class="text-right">{{ $item->Salidas_Cantidad }}</td>
                    <td class="text-right">{{ $item->Salidas_Costo }}</td>
                    <td class="text-right">{{ $item->Saldo_Final }}</td>
                    <td class="text-right">{{ $item->Costo_Final }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="table">
            <tr>
                <th colspan="4">TOTAL GENERAL</th>
                <th class="text-right">{{ $totalGeneral->Saldo_Inicial }}</th>
                <th class="text-right">{{ number_format($totalGeneral->Costo_Inicial, 2) }}</th>
                <th class="text-right">{{ $totalGeneral->Ingresos_Cantidad }}</th>
                <th class="text-right">{{ number_format($totalGeneral->Ingresos_Costo, 2) }}</th>
                <th class="text-right">{{ $totalGeneral->Salidas_Cantidad }}</th>
                <th class="text-right">{{ number_format($totalGeneral->Salidas_Costo, 2) }}</th>
                <th class="text-right">{{ $totalGeneral->Saldo_Final }}</th>
                <th class="text-right">{{ number_format($totalGeneral->Costo_Final, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
