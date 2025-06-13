@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Saldo Almacén</li>
@endsection
@section('contenido')
    {{-- 
    /**
     * Vista para el reporte de Saldo de Almacén
     * 
     * Esta vista muestra el saldo actual de productos en almacén filtrado por fecha y categoría.
     * Permite generar un reporte en PDF con los resultados.
     * 
     * La estructura incluye:
     * - Filtros de fecha y categoría
     * - Tabla de resultados agrupada por categoría y producto
     * - Subtotales por categoría y producto
     * - Total general
     */
    --}}
    <section class="card shadow-lg w-100 d-flex flex-column">
        <div class="card-header bg-gradient-green">
            <h4 class="text-white m-0 fw-bold">SALDO ALMACÉN</h4>
        </div>
        <div class="card-body">
            <!-- Formulario de Filtro -->
            <form action="{{ route('saldo') }}" method="GET">
                @csrf
                <!-- Fecha Final -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="flatpickr">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('fecha_fin') is-invalid @enderror"
                                    id="dateFechaFin" name="fecha_fin" placeholder="fecha_fin"
                                    value="{{ old('fecha_fin', request('fecha_fin')) }}" data-input>
                                <label for="dateFechaFin">Fecha Fin</label>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Categoría -->
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select @error('id_categoria') is-invalid @enderror" id="selectCategoria"
                                name="id_categoria">
                                <option value="">Todas las Categorías</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}"
                                        {{ old('id_categoria', request('id_categoria')) == $categoria->id_categoria ? 'selected' : '' }}>
                                        {{ $categoria->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="selectCategoria">Categoría</label>
                            @error('id_categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-around">
                        <button type="submit" class="btn btn-primary btn-labeled">
                            <span class="btn-label"><i class="bi bi-search"></i></span>Buscar
                        </button>
                        @if ($resultados['total_general']->total_cantidad_actual ?? 0 > 0)
                            <a id="imprimirButton"
                                href="{{ route('saldo.imprimir', ['fecha_fin' => request('fecha_fin'), 'categoria_id' => request('categoria_id')]) }}"
                                target="_blank" class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i class="bi bi-file-pdf-fill"></i></span>Imprimir
                            </a>
                        @endif
                    </div>
                </div>
            </form> <!-- Resultados -->
            <div class="table-responsive overflow-auto mt-3">
                {{-- 
                /**
                 * Tabla de resultados de saldos de almacén
                 * 
                 * Muestra los productos disponibles en almacén con estructura jerárquica:
                 * - Agrupados por categoría
                 * - Subtotales por producto (cuando hay múltiples lotes)
                 * - Detalle por lote
                 * 
                 * La tabla incluye:
                 * - Código de producto
                 * - Nombre del producto
                 * - Unidad de medida
                 * - Número de lote
                 * - Cantidad disponible por lote
                 * - Valor por lote
                 * - Cantidad total por producto
                 * - Valor total por producto
                 */
                --}}
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr class="text-center align-middle">
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
                    @if (isset($resultados))
                        @if (isset($resultados['totales_por_producto']) &&
                                isset($resultados['totales_por_categoria']) &&
                                count($resultados['totales_por_producto']) > 0)
                            <tbody>
                                @php
                                    /**
                                     * Prepara los datos para la tabla de resultados
                                     *
                                     * Inicializa variables para controlar la visualización de categorías y productos
                                     * Crea un mapa de totales por producto para facilitar el acceso a los subtotales
                                     */
                                    $categoriaActual = null;
                                    $totalProducto = [];
                                    foreach ($resultados['totales_por_producto'] as $total) {
                                        $totalProducto[$total->codigo_producto] = $total;
                                    }
                                @endphp
                        @endif

                        @if (isset($resultados['detalles']))
                            @php
                                /**
                                 * Calcula el número de filas que debe ocupar cada producto
                                 * para aplicar correctamente el atributo rowspan en las celdas de totales
                                 */
                                $productoRowspan = [];
                                foreach ($resultados['detalles'] as $detalle) {
                                    $productoRowspan[$detalle->codigo_producto] =
                                        ($productoRowspan[$detalle->codigo_producto] ?? 0) + 1;
                                }
                            @endphp @foreach ($resultados['detalles'] as $index => $detalle)
                                @if ($categoriaActual !== $detalle->categoria)
                                    @php
                                        /**
                                         * Procesa un cambio de categoría en la lista de productos
                                         *
                                         * Cuando se detecta una nueva categoría:
                                         * 1. Actualiza la variable de control
                                         * 2. Obtiene los totales para la categoría actual
                                         * 3. Obtiene el código de la categoría
                                         */
                                        $categoriaActual = $detalle->categoria;
                                        $totalCategoria = collect($resultados['totales_por_categoria'])->firstWhere(
                                            'categoria',
                                            $categoriaActual,
                                        );
                                        $codigoCategoria = collect($resultados['totales_por_categoria'])->firstWhere(
                                            'categoria',
                                            $categoriaActual,
                                        );
                                    @endphp
                                    {{-- Fila de encabezado de categoría con subtotales --}}
                                    <tr class="fw-bold">
                                        <td class="ps-2">
                                            <p class="my-1">
                                                {{ $codigoCategoria ? $codigoCategoria->codigo_categoria : '' }}</p>
                                        </td>
                                        <td colspan="5" class="ps-2">{{ $categoriaActual }}</td>
                                        <td class="text-end pe-4">
                                            {{ $totalCategoria ? $totalCategoria->total_cantidad_actual : '0' }}
                                        </td>
                                        <td class="text-end pe-4">
                                            {{ $totalCategoria ? $totalCategoria->total_valor_actual : '0.00' }}
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    /**
                                     * Determina si esta es la primera fila de un producto específico
                                     *
                                     * Esto es necesario para aplicar el rowspan solo en la primera fila
                                     * y mostrar correctamente los totales por producto
                                     */
                                    $esPrimeraFilaProducto =
                                        $index == 0 ||
                                        $resultados['detalles'][$index - 1]->codigo_producto !==
                                            $detalle->codigo_producto;
                                    $totalProductoActual = $totalProducto[$detalle->codigo_producto] ?? null;
                                @endphp

                                <tr>
                                    <td class="ps-2">
                                        <p class="my-1">{{ $detalle->codigo_producto }}</p>
                                    </td>
                                    <td class="ps-2">{{ $detalle->producto }}</td>
                                    <td class="ps-4">{{ $detalle->unidad }}</td>
                                    <td class="ps-4">{{ $detalle->lote }}</td>
                                    <td class="text-end pe-4">{{ $detalle->cantidad_actual }}</td>
                                    <td class="text-end pe-4">{{ $detalle->costo_total_lote }}</td>

                                    @if ($esPrimeraFilaProducto)
                                        <td rowspan="{{ $productoRowspan[$detalle->codigo_producto] ?? 1 }}"
                                            class="text-end pe-4">
                                            {{ $totalProductoActual->total_cantidad_actual ?? '0' }}
                                        </td>
                                        <td rowspan="{{ $productoRowspan[$detalle->codigo_producto] ?? 1 }}"
                                            class="text-end pe-4">
                                            {{ $totalProductoActual->total_valor_actual ?? '0.00' }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    </tbody>
                    @if (isset($resultados['detalles']) && count($resultados['detalles']) > 0)
                        <tfoot>
                            <tr class="fw-bold">
                                <th colspan="6" class="text-center">TOTAL GENERAL</th>
                                <th class="text-end pe-4">{{ $resultados['total_general']->total_cantidad_actual ?? '0' }}
                                </th>
                                <th class="text-end pe-4">Bs:
                                    {{ number_format($resultados['total_general']->total_valor_actual ?? 0, 2) }}</th>
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
         * Configura el selector de fecha y muestra alertas si es necesario
         * 
         * @event document.DOMContentLoaded
         */
        document.addEventListener('DOMContentLoaded', function() {
            /**
             * Inicializa el selector de fecha con flatpickr
             * Configura el formato de fecha, idioma y restricciones de rango
             */
            flatpickr("#dateFechaFin", {
                dateFormat: "Y-m-d", // Formato año-mes-día
                locale: "es", // Idioma en español
                minDate: "2025-01-01", // Fecha mínima permitida
                maxDate: "today" // Fecha máxima: hoy (deshabilita fechas futuras)
            });

            /**
             * Muestra una alerta cuando se ha realizado una búsqueda pero no hay resultados
             * Utiliza SweetAlert2 para mostrar un mensaje informativo al usuario
             */
            @if (request()->has('fecha_fin') && (!isset($resultados) || count($resultados['detalles']) === 0))
                Swal.fire({
                    icon: 'info', // Icono de información
                    title: 'Sin resultados', // Título de la alerta
                    text: 'No se encontraron saldos para la fecha seleccionada.', // Mensaje detallado
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
