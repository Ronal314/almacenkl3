@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Cambiar Contraseña</li>
@endsection

@section('contenido')
    <section class="card shadow-lg col-md-6 mb-auto">
        <div class="card-header bg-gradient-green">
            <h3 class="text-white m-0">Cambiar Contraseña</h3>
        </div>
        <div class="card-body">
            <form id="changePasswordForm" action="{{ route('usuarios.update-password') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="currentPassword">Contraseña Actual: <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password"
                            id="currentPassword">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label for="newPassword">Nueva Contraseña: <span class="text-danger">*</span></label>

                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="newPassword">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="confirmPassword">Confirmar Nueva Contraseña: <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password_confirmation"
                            id="confirmPassword">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-auto d-flex justify-content-between">
                    <a class="btn btn-danger btn-labeled" onclick="history.back()">
                        <span class="btn-label"><i class="bi bi-x-circle-fill"></i></span>Cancelar</a>
                    <button type="submit" class="btn btn-success btn-labeled">
                        <span class="btn-label"><i class="bi bi-floppy2-fill"></i></span>Guardar</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = "{{ session('success') }}";
            var errorMessage = "{{ session('error') }}";

            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: successMessage,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0B5ED7'
                });
            }

            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0B5ED7'
                });
            }
        });
    </script>
@endpush
