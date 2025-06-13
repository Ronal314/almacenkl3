@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center text-green fw-bold fs-3 mb-3">INGRESAR AL SISTEMA</div>
                <div class="card shadow-lg">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-12">
                                    <input type="text" class="form-control w-100 fs-5 @error('ci') is-invalid @enderror" name="ci" id="txtCi"
                                        value="{{ old('ci') }}" autocomplete="off" placeholder="">
                                    <label for="txtCi" class="ms-2 fs-6">Usuario</label>

                                    @error('ci')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="form-floating col-12">
                                    <input type="password" class="form-control w-100 fs-5 @error('password') is-invalid @enderror" name="password"
                                        id="txtPassword" placeholder="">
                                    <label for="txtPassword" class="ms-2 fs-6">Contraseña</label>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            Mantener sesión activa
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100 btn-lg ">
                                        <span class="fw-bold fs-3">
                                            Iniciar Sesión
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
