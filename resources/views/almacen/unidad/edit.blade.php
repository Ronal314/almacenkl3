@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('unidades.index') }}" class="link">Unidades</a></li>
    <li class="breadcrumb-item active">Editar Unidad</li>
@endsection
@section('contenido')
    <section class="card shadow-lg col-sm-8 mb-auto">
        <div class="card-header bg-gradient-green">
            <h3 class="text-white m-0 fw-bold">Editar Unidad</h3>
        </div>
        <div class="card-body">
            <form id="unidadForm" action="{{ route('unidades.update', $unidad->id_unidad) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="txtNombre">Nombre Unidad: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                            id="txtNombre" value="{{ $unidad->nombre }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="txtDireccion">Dirección:</label>
                        <textarea class="form-control @error('direccion') is-invalid @enderror" type="text" rows="3" name="direccion"
                            id="txtDireccion">{{ $unidad->direccion }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('unidades.index') }}" class="btn btn-danger btn-labeled">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar</a>
                    <button type="button" class="btn btn-success btn-labeled"
                        onclick="confirmSubmit('unidadForm', { 'Nombre Unidad': 'txtNombre', 'Dirección': 'txtDireccion' })">
                        <span class="btn-label"><i class="bi bi-floppy2-fill"></i></span>Guardar
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/unsaved.js') }}"></script>
@endpush
