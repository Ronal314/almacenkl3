@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Movimiento Almacén</li>
@endsection
@section('contenido')
    {{-- 
    /**
     * Vista para el reporte de Movimientos de Almacén
     * 
     * Esta vista muestra los movimientos de productos en almacén en un rango de fechas.
     * Incluye saldos iniciales, ingresos, salidas y saldos finales de cada producto.
     * Permite generar un reporte en PDF con los resultados.
     * 
     * La estructura incluye:
     * - Filtros de rango de fechas (inicio y fin)
     * - Tabla de resultados con movimientos por producto y lote
     * - Total general de todos los movimientos
     */
    --}}
    <section class="card shadow-lg w-100 d-flex flex-column">
        <div class="card-header bg-gradient-green">
            <h4 class="text-white my-auto fw-bold">MOVIMIENTO DE ALMACÉN</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('movimientos') }}" method="GET">
                @csrf
                <div class="row">
                    <!-- Fecha Inicial -->
                    <div class="col-md-4">
                        <div class="flatpickr">
                            <div class="form-floating">
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                    id="dateFechaInicio" name="fecha_inicio" placeholder=""
                                    value="{{ old('fecha_inicio', request('fecha_inicio')) }}" data-input>
                                <label for="dateFechaInicio">Fecha Inicio</label>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Fecha Final -->
                    <div class="col-md-4">
                        <div class="flatpickr">
                            <div class="form-floating">
                                <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                    id="dateFechaFin" name="fecha_fin" placeholder=""
                                    value="{{ old('fecha_fin', request('fecha_fin')) }}" data-input>
                                <label for="dateFechaFin">Fecha Fin</label>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-around">
                        <button type="submit" class="btn btn-primary btn-labeled">
                            <span class="btn-label"><i class="bi bi-search"></i></span>Buscar
                        </button>
                        @if ($totalGeneral->Saldo_Final ?? 0 > 0)
                            <a href="{{ route('movimientos.imprimir', ['fecha_inicio' => request('fecha_inicio'), 'fecha_fin' => request('fecha_fin')]) }}"
                                target="_blank" class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i class="bi bi-file-pdf-fill"></i></span>Imprimir
                            </a>
                        @endif
                    </div>
                </div>
            </form>
            <div class="table-responsive overflow-auto mt-3">
                {{-- 
                /**
                 * Tabla de resultados de movimientos de almacén
                 * 
                 * Muestra los movimientos de productos en el rango de fechas seleccionado:
                 * - Saldo inicial al inicio del período
                 * - Ingresos durante el período
                 * - Salidas durante el período
                 * - Saldo final al cierre del período
                 * 
                 * La tabla incluye:
                 * - Código de producto
                 * - Nombre del producto
                 * - Número de lote
                 * - Fecha de movimiento
                 * - Cantidades y valores para cada tipo de movimiento
                 */
                --}}
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr class="text-center align-middle">
                            <th rowspan="2">Código</th>
                            <th rowspan="2">Producto</th>
                            <th rowspan="2">Lote</th>
                            <th rowspan="2">Fecha</th>
                            <th colspan="2">Saldo Inicial</th>
                            <th colspan="2">Ingresos</th>
                            <th colspan="2">Salidas</th>
                            <th colspan="2">Saldo Final</th>
                        </tr>
                        <tr class="text-center">
                            <th>Cant.</th>
                            <th>Valor</th>
                            <th>Cant.</th>
                            <th>Valor</th>
                            <th>Cant.</th>
                            <th>Valor</th>
                            <th>Cant.</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($productos) && count($productos) > 0)
                            @foreach ($productos as $item)
                                <tr>
                                    <td class="ps-2">
                                        <p class="my-1">{{ $item->Codigo }}</p>
                                    </td>
                                    <td class="ps-2">{{ $item->Producto }}</td>
                                    <td class="ps-2">{{ $item->Lote }}</td>
                                    <td class="ps-2">{{ $item->Fecha_Movimiento }}</td>
                                    <td class="text-end pe-2">{{ $item->Saldo_Inicial }}</td>
                                    <td class="text-end pe-2">{{ $item->Costo_Inicial }}</td>
                                    <td class="text-end pe-2">{{ $item->Ingresos_Cantidad }}</td>
                                    <td class="text-end pe-2">{{ $item->Ingresos_Costo }}</td>
                                    <td class="text-end pe-2">{{ $item->Salidas_Cantidad }}</td>
                                    <td class="text-end pe-2">{{ $item->Salidas_Costo }}</td>
                                    <td class="text-end pe-2">{{ $item->Saldo_Final }}</td>
                                    <td class="text-end pe-2">{{ $item->Costo_Final }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    @if (isset($productos) && count($productos) > 0)
                        <tfoot>
                            <tr class="fw-bold text-center">
                                <th colspan="4">TOTAL GENERAL</th>
                                <th class="text-end pe-2">{{ $totalGeneral->Saldo_Inicial ?? 0 }}</th>
                                <th class="text-end pe-2">{{ number_format($totalGeneral->Costo_Inicial ?? 0, 2) }}</th>
                                <th class="text-end pe-2">{{ $totalGeneral->Ingresos_Cantidad ?? 0 }}</th>
                                <th class="text-end pe-2">{{ number_format($totalGeneral->Ingresos_Costo ?? 0, 2) }}</th>
                                <th class="text-end pe-2">{{ $totalGeneral->Salidas_Cantidad ?? 0 }}</th>
                                <th class="text-end pe-2">{{ number_format($totalGeneral->Salidas_Costo ?? 0, 2) }}</th>
                                <th class="text-end pe-2">{{ $totalGeneral->Saldo_Final ?? 0 }}</th>
                                <th class="text-end pe-2">{{ number_format($totalGeneral->Costo_Final ?? 0, 2) }}</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        /**
         * Inicialización del documento cuando está listo
         * Configura los selectores de fecha y muestra alertas si es necesario
         * 
         * @event document.DOMContentLoaded
         */
        document.addEventListener('DOMContentLoaded', function() {
            /**
             * Inicializa los selectores de fecha con flatpickr
             * Configura el formato de fecha, idioma y restricciones de rango
             */
            flatpickr("#dateFechaInicio, #dateFechaFin", {
                dateFormat: "Y-m-d", // Formato año-mes-día
                locale: "es", // Idioma en español
                maxDate: "today", // Fecha máxima: hoy (deshabilita fechas futuras)
                minDate: "2025-01-01" // Fecha mínima permitida
            });

            /**
             * Muestra una alerta cuando se ha realizado una búsqueda pero no hay resultados
             * Utiliza SweetAlert2 para mostrar un mensaje informativo al usuario
             */
            @if (request()->has('fecha_inicio') && request()->has('fecha_fin') && (!isset($productos) || count($productos) === 0))
                Swal.fire({
                    icon: 'info', // Icono de información
                    title: 'Sin productos', // Título de la alerta
                    text: 'No se encontraron movimientos para el rango de fechas seleccionado.', // Mensaje detallado
                    customClass: {
                        confirmButton: 'btn btn-primary' // Clase CSS para el botón
                    },
                    buttonsStyling: false, // Deshabilita estilos predeterminados
                    confirmButtonText: 'Aceptar', // Texto del botón
                });
            @endif
        });
    </script>
@endpush
