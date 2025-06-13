@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('salidas.index') }}" class="link">Salidas</a></li>
    <li class="breadcrumb-item active">Nueva Salida</li>
@endsection
@section('contenido')
    <section class="card shadow-lg w-100">
        <form id="salidaForm" action="{{ route('salidas.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="selectUnidad">Unidad: <span class="text-danger">*</span></label>
                        <select class="form-control @error('id_unidad') is-invalid @enderror" name="id_unidad"
                            id="selectUnidad">
                            <option value=""></option>
                            @foreach ($unidades as $item)
                                <option value="{{ $item->id_unidad }}"
                                    {{ old('id_unidad') == $item->id_unidad ? 'selected' : '' }}>
                                    {{ $item->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_unidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-6 col-md-3">
                        <label for="numberHoraRuta">N° de Hoja de Ruta: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('n_hoja_ruta') is-invalid @enderror"
                            name="n_hoja_ruta" id="numberHoraRuta" value="{{ old('n_hoja_ruta') }}">
                        @error('n_hoja_ruta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-6 col-md-3">
                        <label for="numberPedido">N° de Pedido: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('n_pedido') is-invalid @enderror" name="n_pedido"
                            value="{{ old('n_pedido') }}" id="numberPedido">
                        @error('n_pedido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="selectProducto">Producto: <span class="text-danger">*</span></label>
                        <select class="form-control" name="id_producto" id="selectProducto">
                            <option value=""></option>
                            @foreach ($productos as $item)
                                <option value="{{ $item->id_producto }}" data-stock="{{ $item->stock_total }}"
                                    data-unidad="{{ $item->unidad }}">
                                    {{ $item->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="numberCantidad">Cantidad: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="cantidad" id="numberCantidad" min="1">
                    </div>
                    <div class="form-group col-4 col-md-2">
                        <label for="numberStock">Stock:</label>
                        <input type="number" class="form-control bg-success-subtle text-success-emphasis" name="cantidad"
                            id="numberStock" min="1" readonly>
                    </div>
                    <div class="form-group col-4 col-md-2 d-flex justify-content-end">
                        <button type="button" id="btnAgregar" class="btn btn-primary btn-labeled mt-auto">
                            <span class="btn-label"><i class="bi bi-cart-plus-fill"></i></span>Agregar</button>
                    </div>
                </div>
                <!-- Tabla Responsiva -->
                <div class="table-responsive">
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
                                    <tr id="filaSalida{{ $index }}">
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-small"
                                                onclick="eliminar({{ $index }})"><i
                                                    class="bi bi-x-circle-fill"></i>Quitar</button>
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
                                <th class="text-end ps-2">
                                    <span id="totalCosto">Bs. 0.00</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-auto d-flex justify-content-between">
                    <a href="{{ route('salidas.index') }}" class="btn btn-danger btn-labeled">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar</a>
                    <button type="button" id="btnGuardar" class="btn btn-success btn-labeled d-none"
                        onclick="confirmSubmit('salidaForm', { 
                            'Unidad': 'document.getElementById(\'selectUnidad\').options[document.getElementById(\'selectUnidad\').selectedIndex].text', 
                            'N° de Hoja de Ruta': 'numberHoraRuta', 
                            'N° de Pedido': 'numberPedido',
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
             * Variable para el total general de la salida
             * @type {number}
             */
            let total = 0;

            /**
             * Inicializa los selectores con TomSelect para mejorar la experiencia de usuario
             * Configura los selectores de unidad y producto con búsqueda mejorada
             * 
             * @function
             * @returns {void}
             */
            function initializeSelects() {
                new TomSelect('#selectUnidad', {
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
             * - Cambio en el selector de producto para mostrar stock
             * - Botones de eliminar producto
             * 
             * @function
             * @returns {void}
             */
            function bindEventListeners() {
                $('#btnAgregar').click(agregar);
                $('#selectProducto').on('change', mostrarValores);
                $(document).on('click', '.btn-danger', function() {
                    const index = $(this).data('index');
                    eliminar(index);
                });
            }

            /**
             * Muestra el stock disponible del producto seleccionado
             * Resta del stock total cualquier cantidad del mismo producto ya agregado al detalle
             * 
             * @function
             * @returns {void}
             */
            function mostrarValores() {
                const selectedOption = $('#selectProducto option:selected');
                let stock = selectedOption.data('stock');
                const idProducto = $('#selectProducto').val();

                $('#tableBodyDetalles tr').each(function() {
                    const idProductoFila = $(this).find('input[name="id_producto[]"]').val();
                    if (idProductoFila == idProducto) {
                        const cantidadFila = parseFloat($(this).find('input[name="cantidad[]"]').val());
                        stock -= cantidadFila;
                    }
                });

                $('#numberCantidad').attr('max', stock);
                $('#numberStock').val(stock);
            }

            /**
             * Agrega un producto a la lista de salida
             * Obtiene los lotes disponibles del producto y distribuye la cantidad solicitada
             * entre ellos usando el método FIFO (primero en entrar, primero en salir)
             * 
             * @function
             * @returns {void}
             */
            function agregar() {
                const idProducto = $('#selectProducto').val();
                const producto = $('#selectProducto option:selected').text();
                const cantidad = parseFloat($('#numberCantidad').val());
                const stock = parseFloat($('#numberStock').val());
                const unidad = $('#selectProducto option:selected').data('unidad');

                if (!validateInputs(idProducto, cantidad, stock)) return;

                $.get(`/productos/${idProducto}/lotes`, function(data) {
                    let cantidadRestante = cantidad;
                    let lotesHtml = '';

                    data.forEach(function(lote) {
                        if (cantidadRestante <= 0) return;

                        const cantidadADeducir = Math.min(cantidadRestante, lote.cantidad_disponible);
                        const subTotalProducto = cantidadADeducir * lote.costo_u;

                        lotesHtml += `
                            <tr id="filaSalida${subTotal.length}">
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-small" data-index="${subTotal.length}"><i class="bi bi-x-circle-fill"></i> Quitar</button>
                                </td>
                                <td class="ps-2"><input type="hidden" name="id_producto[]" value="${idProducto}">${producto}</td>
                                <td class="ps-2"><input type="hidden" name="unidad[]" value="${unidad}">${unidad}</td>
                                <td class="ps-4"><input type="hidden" name="lote[]" value="${lote.lote}">${lote.lote}</td>
                                <td class="text-end pe-2"><input type="hidden" name="cantidad[]" value="${cantidadADeducir}">${cantidadADeducir}</td>
                                <td class="text-end pe-2"><input type="hidden" name="costo_u[]" value="${lote.costo_u}">${lote.costo_u}</td>
                                <td class="text-end pe-2">${subTotalProducto.toFixed(2)}</td>
                            </tr>`;
                        subTotal.push(subTotalProducto);
                        total += subTotalProducto;
                        cantidadRestante -= cantidadADeducir;
                    });

                    $('#tableBodyDetalles').append(lotesHtml);
                    limpiar();
                    recalcularTotal();
                    evaluar();
                });
            }

            /**
             * Valida los inputs del formulario antes de agregar un producto
             * 
             * @function
             * @param {string} idProducto - ID del producto seleccionado
             * @param {number} cantidad - Cantidad del producto a retirar
             * @param {number} stock - Stock disponible del producto
             * @returns {boolean} - True si los inputs son válidos, false en caso contrario
             */
            function validateInputs(idProducto, cantidad, stock) {
                if (!idProducto) return showAlert('Debe seleccionar un producto.');
                if (isNaN(cantidad) || cantidad <= 0) return showAlert('Debe ingresar una cantidad válida.');
                if (cantidad > stock) return showAlert('La cantidad ingresada no puede ser mayor que el stock disponible.');
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
                $('#numberStock').val('');
                document.querySelector('#selectProducto').tomselect.clear();
            }

            /**
             * Elimina un producto de la lista de salida
             * 
             * @function
             * @param {number} index - Índice del producto a eliminar
             * @returns {void}
             */
            function eliminar(index) {
                $(`#filaSalida${index}`).remove();
                recalcularTotal();
                evaluar();
            }

            /**
             * Recalcula los totales de la salida
             * Actualiza el total de cantidad, costo unitario y costo total
             * 
             * @function
             * @returns {void}
             */
            function recalcularTotal() {
                total = 0;
                let totalCantidad = 0;
                let totalCostoUnidad = 0;

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
