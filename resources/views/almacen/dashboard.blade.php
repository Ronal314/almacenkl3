@extends('layouts.admin')
@section('contenido')
    <section class="w-100 d-flex flex-column">
        <div class="row flex-grow-1">
            <div class="col-md-4 my-1">
                <div class="card-dashboard card-hover shadow-lg p-2 h-100">
                    <a href="{{ route('productos.index') }}" class="link h-100">
                        <div class="card-body d-flex justify-content-between h-100">
                            <i class="bi bi-cart fs-1 my-auto px-2"></i>
                            <div class="d-flex flex-column my-auto w-100">
                                <span class="text-center fs-5">Productos Totales</span>
                                <span class="small text-muted text-center">{{ $fecha }}</span>
                            </div>
                            <span class="fs-1 my-auto px-2">{{ $totalProductos }} </span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 my-1">
                <div class="card-dashboard shadow p-2 h-100">
                    <a href="{{ route('ingresos.index') }}" class="link h-100">
                        <div class="card-body d-flex justify-content-between h-100">
                            <i class="bi bi-cart-plus fs-1 my-auto px-2"></i>
                            <div class="d-flex flex-column my-auto w-100">
                                <span class="text-center fs-5">Ingresos Totales</span>
                                <span class="small text-muted text-center">{{ $fecha }}</span>
                            </div>
                            <span class="fs-1 my-auto px-2">{{ $totalIngresos }} </span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 my-1">
                <div class="card-dashboard shadow p-2 h-100">
                    <a href="{{ route('salidas.index') }}" class="link h-100">
                        <div class="card-body d-flex justify-content-between h-100">
                            <i class="bi bi-cart-dash fs-1 my-auto px-2"></i>
                            <div class="d-flex flex-column my-auto w-100">
                                <span class="text-center fs-5">Salidas Totales</span>
                                <span class="small text-muted text-center">{{ $fecha }}</span>
                            </div>
                            <span class="fs-1 my-auto px-2">{{ $totalSalidas }} </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row flex-grow-1">
            <div class="col-md-8 my-1">
                <div class="card-dashboard shadow h-100">
                    <a href="{{ route('salidas.index') }}" class="link h-100">
                        <div class="card-body d-flex flex-column pt-1 h-100">
                            <span class="text-center">Salidas por Mes</span>
                            <canvas id="salidasPorMesChart" class="w-100 h-100"></canvas>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 my-1">
                <div class="card-dashboard shadow h-100">
                    <a href="{{ route('categorias.index') }}" class="link h-100">
                        <div class="card-body d-flex flex-column py-2 h-100">
                            <span class="text-center">Productos por Categoría</span>
                            <canvas id="myChart" class="h-100"></canvas>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row flex-grow-1">
            <div class="col-md-6 my-1">
                <div class="card-dashboard shadow p-2 h-100">
                    @if ($ultimoIngreso)
                        <a href="{{ route('ingresos.show', $ultimoIngreso->id_ingreso) }}" class="link h-100">
                        @else
                            <a href="#" class="link h-100">
                    @endif
                    <div class="card-body d-flex justify-content-between h-100">
                        <i class="bi bi-box-arrow-in-down fs-1 my-auto px-2"></i>
                        <div class="d-flex flex-column my-auto w-100">
                            <span class="text-center fs-5">Último Ingreso</span>
                            @if ($ultimoIngreso)
                                <span class="small text-muted text-center">{{ $ultimoIngreso->fecha_hora }}</span>
                                <span class="fs-6 text-center">Bs: {{ number_format($ultimoIngreso->total, 2) }}</span>
                            @else
                                <span class="small text-muted text-center">No hay datos disponibles</span>
                            @endif
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 my-1">
                <div class="card-dashboard shadow p-2 h-100">
                    @if ($ultimaSalida)
                        <a href="{{ route('salidas.show', $ultimaSalida->id_salida) }}" class="link h-100">
                        @else
                            <a href="#" class="link h-100">
                    @endif
                    <div class="card-body d-flex justify-content-between h-100">
                        <i class="bi bi-box-arrow-up fs-1 my-auto px-2"></i>
                        <div class="d-flex flex-column my-auto w-100">
                            <span class="text-center fs-5">Última Salida</span>
                            @if ($ultimaSalida)
                                <span class="small text-muted text-center">{{ $ultimaSalida->fecha_hora }}</span>
                                <span class="fs-6 text-center">Bs: {{ number_format($ultimaSalida->total, 2) }}</span>
                            @else
                                <span class="small text-muted text-center">No hay datos disponibles</span>
                            @endif
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-3.7.1.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('welcomeShown') === false)
                Swal.fire({
                    title: 'Bienvenido al Sistema de Control de Inventario',
                    text: '{{ Auth::user()->name }}',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0b5ed7'
                }).then(() => {
                    @php
                        session(['welcomeShown' => true]);
                    @endphp
                });
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('myChart').getContext('2d');
            const productosPorCategoriaArray = {!! json_encode($productosPorCategoria->pluck('productos_count', 'descripcion')->toArray()) !!};
            const data = {
                labels: Object.keys(productosPorCategoriaArray),
                datasets: [{
                    label: '# de Productos',
                    data: Object.values(productosPorCategoriaArray),
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ],
                    hoverOffset: 4
                }]
            };
            const config = {
                type: 'doughnut',
                data: data,
            };
            const myChart = new Chart(ctx, config);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxSalidasPorMes = document.getElementById('salidasPorMesChart').getContext('2d');
            const salidasPorMesData = {!! json_encode($salidasPorMes) !!};

            // Procesar los datos para Chart.js
            const labels = salidasPorMesData.map(item => `${item.year}-${String(item.month).padStart(2, '0')}`);
            const data = salidasPorMesData.map(item => item.count);

            // Encontrar el valor máximo en los datos y añadir un margen
            const maxDataValue = Math.max(...data);
            const maxYValue = maxDataValue + Math.ceil(maxDataValue * 0.1); // Añadir un margen del 10%

            const chartData = {
                labels: labels,
                datasets: [{
                    label: 'Salidas por Mes',
                    data: data,
                    backgroundColor: 'rgb(54, 162, 235)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            };

            new Chart(ctxSalidasPorMes, {
                type: 'bar',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxYValue
                        }
                    }
                }
            });
        });
    </script>
@endpush
