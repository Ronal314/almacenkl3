<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Almacén</title>
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <aside class="d-flex flex-column bg-green text-white d-none d-lg-block col-lg-2 p-2 position-fixed h-100">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('img/logo-para-pdf.jpg') }}" alt="Logo" width="40" height="40"
                        class="rounded-circle bg-green-light p-1">
                    <span class="fw-bold fs-2 ms-2">ALMACÉN</span>
                </div>
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item mt-2">
                        <div class="card-transparent text-start">
                            <a href="{{ route('dashboard') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-house-fill"></i> Inicio</a>
                        </div>
                    </li>
                    <li class="nav-item my-2">
                        <span class="text-green-light">Gestión de Inventario</span>
                        <div class="card-transparent mt-2 text-start">
                            <a href="{{ route('categorias.index') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-tags-fill"></i> Categorías</a>
                            <a href="{{ route('productos.index') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-cart-fill"></i> Productos</a>
                        </div>
                    </li>
                    <li class="nav-item my-2">
                        <span class="text-green-light">Gestión de Ingresos</span>
                        <div class="card-transparent mt-2 text-start">
                            <a href="{{ route('proveedores.index') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-person-lines-fill"></i> Proveedores</a>
                            <a href="{{ route('ingresos.index') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-file-earmark-plus"></i> Registrar Ingreso</a>
                        </div>
                    </li>
                    <li class="nav-item my-2">
                        <span class="text-green-light">Gestión de Salidas</span>
                        <div class="card-transparent mt-2 text-start">
                            <a href="{{ route('unidades.index') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-building-fill"></i> Unidades</a>
                            <a href="{{ route('salidas.index') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-file-earmark-minus"></i> Registrar Salida</a>
                        </div>
                    </li>
                    <li class="nav-item my-2">
                        <span class="text-green-light">Reportes</span>
                        <div class="card-transparent mt-2 text-start">
                            <a href="{{ route('saldo') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-file-earmark-bar-graph"></i> Saldo Almacén</a>
                            <a href="{{ route('movimientos') }}" class="nav-link text-white bg-green-hover">
                                <i class="bi bi-arrow-repeat"></i> Movimiento Almacén</a>
                        </div>
                    </li>
                </ul>
            </aside>
            {{-- movil --}}
            <aside class="offcanvas offcanvas-start bg-green text-white p-2" tabindex="-1" id="offCanvasMenu">
                <div class="offcanvas-header d-flex flex-row justify-content-between">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('img/logo-para-pdf.jpg') }}" alt="Logo" width="40" height="40"
                            class="rounded-circle bg-green-light p-1">
                        <span class="offcanvas-title fw-bold fs-2 ms-2" id="offCanvasMenuLabel">ALMACÉN</span>
                    </div>
                    <button class="btn text-white" data-bs-dismiss="offcanvas"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav flex-column flex-grow-1">
                        <li class="nav-item">
                            <div class="card-transparent text-start">
                                <a href="{{ route('dashboard') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-house-fill"></i> Inicio</a>
                            </div>
                        </li>
                        <li class="nav-item my-2">
                            <span class="text-green-light">Gestión de Inventario</span>
                            <div class="card-transparent mt-2 text-start">
                                <a href="{{ route('categorias.index') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-tags-fill"></i> Categorías</a>
                                <a href="{{ route('productos.index') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-cart-fill"></i> Productos</a>
                            </div>
                        </li>
                        <li class="nav-item my-2">
                            <span class="text-green-light">Gestión de Ingresos</span>
                            <div class="card-transparent mt-2 text-start">
                                <a href="{{ route('proveedores.index') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-person-lines-fill"></i> Proveedores</a>
                                <a href="{{ route('ingresos.index') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-file-earmark-plus"></i> Registrar Ingreso</a>
                            </div>
                        </li>
                        <li class="nav-item my-2">
                            <span class="text-green-light">Gestión de Salidas</span>
                            <div class="card-transparent mt-2 text-start">
                                <a href="{{ route('unidades.index') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-building-fill"></i> Unidades</a>
                                <a href="{{ route('salidas.index') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-file-earmark-minus"></i> Registrar Salida</a>
                            </div>
                        </li>
                        <li class="nav-item my-2">
                            <span class="text-green-light">Reportes</span>
                            <div class="card-transparent mt-2 text-start">
                                <a href="{{ route('saldo') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Saldo Almacén</a>
                                <a href="{{ route('movimientos') }}" class="nav-link text-white bg-green-hover">
                                    <i class="bi bi-arrow-repeat"></i> Movimiento Almacén</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>
            <!-- Content -->
            <main class="d-flex flex-column bg-green-medium col-lg-10 offset-lg-2">
                <nav class="d-flex bg-green-light rounded shadow-lg sticky-top mb-1">
                    <button class="btn bg-green text-white d-lg-none d-block" data-bs-toggle="offcanvas"
                        data-bs-target="#offCanvasMenu">
                        <i class="bi bi-list"></i></button>
                    <ol class="breadcrumb ps-1 my-auto fw-bold fs-6">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="link">
                                <i class="bi bi-house-fill mx-1"></i>Inicio</a>
                        </li>
                        @yield('breadcrumb')
                    </ol>
                    <div class="dropdown ms-auto">
                        <button class="btn dropdown-toggle w-100 fw-bold" id="userDropdown"
                            data-bs-toggle="dropdown">
                            {{ Auth::user()->ci }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <span class="dropdown-item">{{ Auth::user()->name }}</span>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('usuarios.change-password') }}">Cambiar
                                    Contraseña</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- contenido -->
                <div class="d-flex justify-content-center h-100">
                    @yield('contenido')
                </div>
                <!-- contenido -->
                <footer class="text-center mt-auto">
                    <span class="text-muted">&copy; 2025 | Desarrollado por RonaldoChambiQ.
                       
                
                    </span>
                </footer>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
