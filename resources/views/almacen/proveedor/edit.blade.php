@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}" class="link">Proveedores</a></li>
    <li class="breadcrumb-item active">Editar Proveedor</li>
@endsection
@section('contenido')
    <section class="card shadow-lg col-md-8 mb-auto">
        <div class="card-header bg-gradient-green">
            <h3 class="text-white m-0 fw-bold">Editar Proveedor</h3>
        </div>
        <div class="card-body">
            <form id="proveedorForm" action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-sm-7">
                        <label for="txtRazonSocial">Razón Social: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('razon_social') is-invalid @enderror"
                            name="razon_social" id="txtRazonSocial" value="{{ $proveedor->razon_social }}">
                        @error('razon_social')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="numberNit">N° de Nit: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('nit') is-invalid @enderror" name="nit"
                            id="numberNit" value="{{ $proveedor->nit }}">
                        @error('nit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-7">
                        <label for="txtNombre">Nombre Proveedor: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                            id="txtNombre" value="{{ $proveedor->nombre }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="numberTelefono">Teléfono:</label>
                        <input type="telefono" class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                            id="numberTelefono" value="{{ $proveedor->telefono }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="txtDireccion">Dirección: <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control @error('direccion') is-invalid @enderror" rows="3" name="direccion"
                            id="txtDireccion">{{ $proveedor->direccion }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-danger btn-labeled">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar</a>
                    <button type="button" class="btn btn-success btn-labeled"
                        onclick="confirmSubmit('proveedorForm', { 'Razón Social': 'txtRazonSocial', 'N° de Nit': 'numberNit', 'Nombre Proveedor': 'txtNombre', 'Teléfono': 'numberTelefono', 'Dirección': 'txtDireccion' })">
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
