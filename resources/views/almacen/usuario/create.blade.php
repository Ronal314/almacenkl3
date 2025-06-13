@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Crear Usuario</li>
@endsection
@section('contenido')
    <section class="card shadow-lg col-md-6 mb-auto">
        <div class="card-header bg-gradient-green">
            <h3 class="text-white my-auto">Crear Usuario</h3>
        </div>
        <form action="{{ route('usuarios.store') }}" method="POST">
            <div class="card-body">
                @csrf
                <div class="row">
                    <div class="form-group col-7">
                        <label for="txtNombre">Nombre del usuario: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="txtNombre"
                            value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-5">
                        <label for="txtCi">CI: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ci" id="txtCi"
                            value="{{ old('ci') }}">
                        @if ($errors->has('ci'))
                            <div class="text-danger">{{ $errors->first('ci') }}</div>
                        @endif
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <button type="reset" class="btn btn-secondary btn-labeled" onclick="history.back()">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar</button>
                    <button type="submit" class="btn btn-success btn-labeled">
                        <span class="btn-label"><i class="bi bi-floppy2-fill"></i></span>Guardar</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/unsaved.js') }}"></script>
@endpush
