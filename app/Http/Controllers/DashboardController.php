<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Ingreso;
use App\Models\Salida;
use App\Models\Categoria;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para el panel de control (dashboard) del sistema.
 * 
 * Este controlador maneja la visualización de estadísticas y resúmenes
 * sobre los productos, ingresos y salidas del almacén.
 * 
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel de control con estadísticas del sistema.
     * 
     * Incluye totales de productos, ingresos y salidas, así como
     * distribución de productos por categoría y tendencias mensuales.
     *
     * @return \Illuminate\View\View  Vista del panel de control
     */
    public function index()
    {
        // Fetch data from the database
        $totalProductos = Producto::count();
        $totalIngresos = Ingreso::count();
        $totalSalidas = Salida::count();
        $productosPorCategoria = Categoria::withCount('productos')->get();
        $ultimaSalida = Salida::latest()->first();
        $ultimoIngreso = Ingreso::latest()->first();
        $fecha = Carbon::now()->format('Y-m-d');
        // Obtener la cantidad de salidas por mes
        $salidasPorMes = Salida::select(
            DB::raw('YEAR(fecha_hora) as year'),
            DB::raw('MONTH(fecha_hora) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc') // Corregido, ahora se especifica la dirección
            ->orderBy('month', 'asc') // Corregido, ahora se especifica la dirección
            ->get();

        return view('almacen.dashboard', compact(
            'totalProductos',
            'totalIngresos',
            'totalSalidas',
            'productosPorCategoria',
            'ultimaSalida',
            'ultimoIngreso',
            'fecha',
            'salidasPorMes'

        ));
    }
}
