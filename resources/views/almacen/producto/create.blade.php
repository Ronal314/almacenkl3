@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}" class="link">Productos</a></li>
    <li class="breadcrumb-item active">Crear Producto</li>
@endsection
@section('contenido')
    <section class="card shadow-lg col-md-8 mb-auto">
        <div class="card-header bg-gradient-green">
            <h3 class="text-white m-0 fw-bold">Crear Producto</h3>
        </div>
        <div class="card-body">
            <form id="productoForm" action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-8">
                        <label for="selectCategoria">Categoría: <span class="text-danger">*</span></label>
                        <select class="form-control @error('id_categoria') is-invalid @enderror" name="id_categoria"
                            id="selectCategoria">
                            <option value=""></option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}"
                                    {{ old('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->descripcion }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtCodigo">Código: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" name="codigo"
                            id="txtCodigo" value="{{ old('codigo') }}" readonly>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="txtDescripcion">Descripción: <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="txtDescripcion"
                            rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="selectUnidad">Unidad: <span class="text-danger">*</span></label>
                        <select class="form-control @error('unidad') is-invalid @enderror" name="unidad" id="selectUnidad">
                            <option value=""></option>
                            <option value="Pieza" {{ old('unidad') == 'Pieza' ? 'selected' : '' }}>PIEZA</option>
                            <option value="Paquete" {{ old('unidad') == 'Paquete' ? 'selected' : '' }}>PAQUETE</option>
                            <option value="Caja" {{ old('unidad') == 'Caja' ? 'selected' : '' }}>CAJA</option>
                            <option value="Rollo" {{ old('unidad') == 'Rollo' ? 'selected' : '' }}>ROLLO</option>
                            <option value="Juego" {{ old('unidad') == 'Juego' ? 'selected' : '' }}>JUEGO</option>
                            <option value="Bolsa" {{ old('unidad') == 'Bolsa' ? 'selected' : '' }}>BOLSA</option>
                            <option value="Unidad" {{ old('unidad') == 'Unidad' ? 'selected' : '' }}>UNIDAD</option>
                            <option value="Botella" {{ old('unidad') == 'Botella' ? 'selected' : '' }}>BOTELLA</option>
                        </select>
                        @error('unidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('productos.index') }}" class="btn btn-danger btn-labeled">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar
                    </a>
                    <button type="button" class="btn btn-success btn-labeled"
                        onclick="confirmSubmit('productoForm', { 'Categoría': 'selectCategoria', 'Código': 'txtCodigo', 'Descripción': 'txtDescripcion', 'Unidad': 'selectUnidad' })">
                        <span class="btn-label"><i class="bi bi-floppy2-fill"></i></span>Guardar
                    </button>
                </div>
        </div>
        </form>
    </section>

    @push('scripts')
        <script src="{{ asset('js/jquery-3.7.1.js') }}"></script>
        <script>
            $(document).ready(function() {
                let selectCategoria = new TomSelect('#selectCategoria', {
                    create: false,
                    render: {
                        no_results: function(data) {
                            return '<div class="no-results">No se encontraron resultados</div>';
                        }
                    }
                });

                let selectUnidad = new TomSelect('#selectUnidad', {
                    create: true,
                    render: {
                        no_results: function(data) {
                            return '<div class="no-results">No se encontraron resultados</div>';
                        },
                        option_create: function(data) {
                            return '<div class="create">Agregar: <strong>' + data.input + '</strong></div>';
                        }
                    }
                });

                $('#selectCategoria').on('change', function() {
                    const categoriaId = this.value;
                    if (categoriaId) {
                        // console.log(`Fetching code for category ID: ${categoriaId}`);
                        fetch(`/almacen/productos/generar-codigo/${categoriaId}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                document.getElementById('txtCodigo').value = data.codigo;
                            })
                            .catch(error => console.error('Error:', error));
                    } else {
                        document.getElementById('txtCodigo').value = '';
                    }
                });
            });
        </script>
        <script src="{{ asset('js/unsaved.js') }}"></script>
    @endpush
@endsection
