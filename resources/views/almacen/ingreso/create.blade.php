@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ingresos.index') }}" class="link">Ingresos</a></li>
    <li class="breadcrumb-item active">Nuevo Ingreso</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <form id="ingresoForm" action="{{ route('ingresos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="selectProveedor">Proveedor: <span class="text-danger">*</span></label>
                        <select class="form-control @error('id_proveedor') is-invalid @enderror" name="id_proveedor"
                            id="selectProveedor">
                            <option value=""></option>
                            @foreach ($proveedores as $item)
                                <option value="{{ $item->id_proveedor }}"
                                    {{ old('id_proveedor') == $item->id_proveedor ? 'selected' : '' }}>
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_proveedor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="numberFactura">Nº de Factura: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('n_factura') is-invalid @enderror" name="n_factura"
                            id="numberFactura" value="{{ old('n_factura') }}">
                        @error('n_factura')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="numberPedido">Nº de Pedido: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('n_pedido') is-invalid @enderror" name="n_pedido"
                            id="numberPedido" value="{{ old('n_pedido') }}">
                        @error('n_pedido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="txtLote">Lote:</label>
                        <input type="text" class="form-control" name="lote" id="txtLote"
                            value="{{ $siguienteLote }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="selectProducto">Producto:</label>
                        <select class="form-control" name="id_producto" id="selectProducto">
                            <option value=""></option>
                            @foreach ($productos as $item)
                                <option value="{{ $item->id_producto }}" data-unidad="{{ $item->unidad }}">
                                    {{ $item->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="numberCantidad">Cantidad:</label>
                        <input type="number" class="form-control" name="cantidad" id="numberCantidad" min="1">
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="numberCostoU">Costo por Unidad:</label>
                        <div class="input-group">
                            <span class="input-group-text">Bs.</span>
                            <input type="number" class="form-control" name="costo_u" id="numberCostoU" min="0.00"
                                step="0.01">
                        </div>
                    </div>
                    <div class="form-group col-4 col-md-2 d-flex justify-content-end">
                        <button type="button" id="btnAgregar" class="btn btn-primary btn-labeled mt-auto">
                            <span class="btn-label"><i class="bi bi-cart-plus-fill"></i></span>Agregar</button>
                    </div>
                </div>
                <div class="table-responsive overflow-auto">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center align-middle">
                                <th>Opciones</th>
                                <th>Producto</th>
                                <th>Unidad</th>
                                <th>Lote</th>
                                <th>Cantidad</th>
                                <th>Costo <br> Unidad</th>
                                <th>Costo <br> Total</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyDetalles" class="align-middle">
                            @if (!empty($productosOld))
                                @foreach ($productosOld as $index => $producto)
                                    <tr id="filaIngreso{{ $index }}">
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-small"
                                                onclick="eliminar({{ $index }})"><i class="bi bi-x-circle-fill"></i>
                                                Quitar</button>
                                        </td>
                                        <td class="ps-2">
                                            <input type="hidden" name="id_producto[]"
                                                value="{{ $producto['id_producto'] }}">{{ $producto['producto'] }}
                                        </td>
                                        <td class="ps-2">
                                            <input type="hidden" name="unidad[]"
                                                value="{{ $producto['unidad'] }}">{{ $producto['unidad'] }}
                                        </td>
                                        <td class="ps-4">
                                            <input type="hidden" name="lote[]"
                                                value="{{ $producto['lote'] }}">{{ $producto['lote'] }}
                                        </td>
                                        <td class="text-end pe-2">
                                            <input type="hidden" name="cantidad[]"
                                                value="{{ $producto['cantidad'] }}">{{ $producto['cantidad'] }}
                                        </td>
                                        <td class="text-end pe-2">
                                            <input type="hidden" name="costo_u[]"
                                                value="{{ number_format($producto['costo_u'], 2, '.', '') }}">
                                            {{ number_format($producto['costo_u'], 2, '.', '') }}
                                        </td>
                                        <td class="text-end pe-2">
                                            {{ number_format($producto['cantidad'] * $producto['costo_u'], 2, '.', '') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot id="tableFooter">
                            <tr>
                                <th colspan="4" class="text-center">TOTAL GENERAL</th>
                                <th class="text-end pe-2">
                                    <span id="totalCantidad">0</span>
                                </th>
                                <th class="text-end pe-2">
                                    <span id="totalCostoUnidad">Bs. 0.00</span>
                                </th>
                                <th class="text-end pe-2">
                                    <span id="totalCosto">Bs. 0.00</span>
                                </th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
                <div class="mt-auto d-flex justify-content-between align-items-end">
                    <a href="{{ route('ingresos.index') }}" class="btn btn-danger btn-labeled">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar</a>
                    <button type="button" id="btnGuardar" class="btn btn-success btn-labeled d-none"
                        onclick="confirmSubmit('ingresoForm', { 
                            'Proveedor': 'document.getElementById(\'selectProveedor\').options[document.getElementById(\'selectProveedor\').selectedIndex].text', 
                            'Nº de Factura': 'numberFactura', 
                            'Nº de Pedido': 'numberPedido', 
                            'Lote': 'txtLote',
                            'Total de Productos': 'document.getElementById(\'totalCantidad\').innerText',
                            'Costo Total': 'document.getElementById(\'totalCosto\').innerText'
                        })">
                        <span class="btn-label"><i class="bi bi-floppy2-fill"></i></span>Guardar
                    </button>
                </div>
            </div>
        </form>
    </section> @push('scripts')
        <script src="{{ asset('js/jquery-3.7.1.js') }}"></script>
        <script>
            /**
             * Inicialización del documento cuando está listo
             * Configura los selectores, enlaza eventos y calcula totales iniciales
             * 
             * @event document.ready
             */
            $(document).ready(function() {
                initializeSelects();
                bindEventListeners();
                if ($('#tableBodyDetalles tr').length > 0) recalcularTotal();
                evaluar();
            });

            /**
             * Arreglo para almacenar los subtotales de cada línea de producto
             * @type {Array<number>}
             */
            const subTotal = [];

            /**
             * Variable para el total general del ingreso
             * @type {number}
             */
            let total = 0;

            /**
             * Inicializa los selectores con TomSelect para mejorar la experiencia de usuario
             * Configura los selectores de proveedor y producto con búsqueda mejorada
             * 
             * @function
             * @returns {void}
             */
            function initializeSelects() {
                new TomSelect('#selectProveedor', {
                    create: false,
                    render: {
                        no_results: () => '<div class="no-results">No se encontraron resultados</div>'
                    }
                });
                new TomSelect('#selectProducto', {
                    create: false,
                    render: {
                        no_results: () => '<div class="no-results">No se encontraron resultados</div>'
                    }
                });
            }

            /**
             * Enlaza todos los event listeners necesarios para la funcionalidad del formulario
             * - Botón agregar producto
             * - Botones de eliminar producto
             * 
             * @function
             * @returns {void}
             */
            function bindEventListeners() {
                $('#btnAgregar').click(agregar);
                $(document).on('click', '.btn-danger', function() {
                    const index = $(this).data('index');
                    eliminar(index);
                });
            }

            /**
             * Agrega un producto a la lista de ingreso
             * Calcula el subtotal y agrega una nueva fila a la tabla
             * 
             * @function
             * @returns {void}
             */
            function agregar() {
                const idProducto = $('#selectProducto').val();
                const producto = $('#selectProducto option:selected').text();
                const cantidad = parseFloat($('#numberCantidad').val());
                const costo_u = parseFloat($('#numberCostoU').val());
                const lote = $('#txtLote').val();
                const unidad = $('#selectProducto option:selected').data('unidad');

                if (!validateInputs(idProducto, cantidad, costo_u)) return;

                const index = subTotal.length;
                subTotal[index] = cantidad * costo_u;
                total += subTotal[index];

                const fila = `
                    <tr id="filaIngreso${index}">
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-small" data-index="${index}"><i class="bi bi-x-circle-fill"></i> Quitar</button>
                        </td>
                        <td class="ps-2"><input type="hidden" name="id_producto[]" value="${idProducto}">${producto}</td>
                        <td class="ps-2"><input type="hidden" name="unidad[]" value="${unidad}">${unidad}</td>
                        <td class="ps-4"><input type="hidden" name="lote[]" value="${lote}">${lote}</td>
                        <td class="text-end pe-2"><input type="hidden" name="cantidad[]" value="${cantidad}">${cantidad}</td>
                        <td class="text-end pe-2"><input type="hidden" name="costo_u[]" value="${costo_u.toFixed(2)}">${costo_u.toFixed(2)}</td>
                        <td class="text-end pe-2">${subTotal[index].toFixed(2)}</td>
                    </tr>`;
                $('#tableBodyDetalles').append(fila);

                limpiar();
                recalcularTotal();
                evaluar();
            }

            /**
             * Valida los inputs del formulario antes de agregar un producto
             * 
             * @function
             * @param {string} idProducto - ID del producto seleccionado
             * @param {number} cantidad - Cantidad del producto a ingresar
             * @param {number} costo_u - Costo unitario del producto
             * @returns {boolean} - True si los inputs son válidos, false en caso contrario
             */
            function validateInputs(idProducto, cantidad, costo_u) {
                if (!idProducto) return showAlert('Debe seleccionar un producto.');
                if (isNaN(cantidad) || cantidad <= 0) return showAlert('Debe ingresar una cantidad válida.');
                if (isNaN(costo_u) || costo_u <= 0) return showAlert('Debe ingresar un costo válido.');
                return true;
            }

            /**
             * Muestra una alerta personalizada usando SweetAlert2
             * 
             * @function
             * @param {string} message - Mensaje a mostrar en la alerta
             * @returns {boolean} - Siempre retorna false para usar en validaciones
             */
            function showAlert(message) {
                Swal.fire({
                    icon: 'warning',
                    title: message,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }

            /**
             * Limpia los campos del formulario después de agregar un producto
             * 
             * @function
             * @returns {void}
             */
            function limpiar() {
                $('#numberCantidad').val('');
                $('#numberCostoU').val('');
                document.querySelector('#selectProducto').tomselect.clear();
            }

            /**
             * Elimina un producto de la lista de ingreso
             * 
             * @function
             * @param {number} index - Índice del producto a eliminar
             * @returns {void}
             */
            function eliminar(index) {
                $(`#filaIngreso${index}`).remove();
                recalcularTotal();
                evaluar();
            }

            /**
             * Recalcula los totales del ingreso
             * Actualiza el total de cantidad, costo unitario y costo total
             * 
             * @function
             * @returns {void}
             */
            function recalcularTotal() {
                total = 0;
                let totalCantidad = 0,
                    totalCostoUnidad = 0;

                $('#tableBodyDetalles tr').each(function() {
                    const cantidad = parseFloat($(this).find('td:eq(4)').text());
                    const costoUnidad = parseFloat($(this).find('td:eq(5)').text());
                    const costoTotal = parseFloat($(this).find('td:eq(6)').text());

                    totalCantidad += cantidad;
                    totalCostoUnidad += costoUnidad;
                    total += costoTotal;
                });

                $('#totalCantidad').html(totalCantidad);
                $('#totalCostoUnidad').html(`Bs. ${totalCostoUnidad.toFixed(2)}`);
                $('#totalCosto').html(`Bs. ${total.toFixed(2)}`);
            }

            /**
             * Evalúa si hay productos en la lista para mostrar u ocultar elementos
             * Muestra/oculta el footer de la tabla y el botón de guardar
             * 
             * @function
             * @returns {void}
             */
            function evaluar() {
                if (total > 0) {
                    $('#tableFooter').show();
                    $('#btnGuardar').removeClass('d-none');
                } else {
                    $('#tableFooter').hide();
                    $('#btnGuardar').addClass('d-none');
                }
            }
        </script>
        <script src="{{ asset('js/unsaved.js') }}"></script>
    @endpush
@endsection
