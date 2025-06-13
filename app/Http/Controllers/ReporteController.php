<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Importar DomPDF
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para la generación de reportes del sistema.
 * 
 * Este controlador maneja la generación de diferentes tipos de reportes
 * relacionados con el movimiento de productos y saldos de almacén,
 * tanto en formato web como en PDF.
 * 
 * @package App\Http\Controllers
 */
class ReporteController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}
    /**
     * Muestra el reporte de movimientos de almacén entre fechas.
     * 
     * Si se reciben fechas por GET, ejecuta la consulta y muestra los resultados.
     * 
     * @param  Request  $request  Solicitud HTTP con parámetros de fechas
     * @return \Illuminate\View\View  Vista con resultados del reporte
     */
    public function movimientoAlmacen(Request $request)
    {
        $resultados = null;
        $productos = [];
        $totalGeneral = null;

        if ($request->isMethod('get') && $request->has(['fecha_inicio', 'fecha_fin'])) {
            // Validar las fechas
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);

            $fecha_inicio = $request->input('fecha_inicio');
            $fecha_fin = $request->input('fecha_fin');

            // Llamar al procedimiento almacenado
            $resultados = DB::select('CALL obtenerMovimientos(?, ?)', [$fecha_inicio, $fecha_fin]);

            // Usar array_pop() para obtener el total general
            $totalGeneral = array_pop($resultados); // La última fila es el total general

            // Si el total general existe, lo eliminamos del arreglo de productos
            $productos = $resultados; // El resto de los productos son los productos normales
        }

        return view('almacen.reporte.movimiento', compact('productos', 'totalGeneral'));
    }
    /**
     * Genera un PDF con el reporte de movimientos entre fechas.
     * 
     * @param  Request  $request  Solicitud HTTP con parámetros de fechas
     * @return \Illuminate\Http\Response  Respuesta con el PDF generado
     */
    public function imprimirMovimientoPDF(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        // Llamar al procedimiento almacenado
        $resultados = DB::select('CALL obtenerMovimientos(?, ?)', [$fecha_inicio, $fecha_fin]);

        // Usar array_pop() para obtener el total general
        $totalGeneral = array_pop($resultados); // La última fila es el total general

        // Si el total general existe, lo eliminamos del arreglo de productos
        $productos = $resultados; // El resto de los productos son los productos normales


        // Ruta del logo
        $logoPath = public_path('img/logo-para-pdf.jpg');
        // Datos para la vista
        $data = [
            'logoPath' => $logoPath,
            'resultados' => $resultados,
            'totalGeneral' => $totalGeneral,
            'productos' => $productos,
            'fecha_inicio' => Carbon::parse($fecha_inicio)->format('d/m/Y'),
            'fecha_fin' => Carbon::parse($fecha_fin)->format('d/m/Y'),
            'fecha_impresion' => Carbon::now('America/La_Paz')->format('d/m/Y'),
        ];

        // Generar el PDF
        $pdf = Pdf::loadView('almacen.reporte.movimiento_pdf', $data);

        // Configurar el tamaño del papel y la orientación
        $pdf->setPaper('letter', 'landscape')
            ->setOption('margin-top', 0) // Margen superior en mm
            ->setOption('margin-bottom', 10) // Margen inferior en mm
            ->setOption('margin-left', 10) // Margen izquierdo en mm
            ->setOption('margin-right', 10) // Margen derecho en mm
            ->setOption('footer-center', '[page]') // Pie de página centrado con número de página
            ->setOption('footer-font-size', '9'); // Tamaño de fuente del pie de página

        // Mostrar el PDF en el navegador
        return $pdf->stream('reporte_movimiento_' . $fecha_inicio . '_al_' . $fecha_fin . '.pdf');
    }
    /**
     * Muestra el reporte de saldo de almacén a una fecha determinada.
     * 
     * Si se recibe fecha por GET, ejecuta la consulta y muestra los resultados.
     * Se puede filtrar opcionalmente por categoría.
     * 
     * @param  Request  $request  Solicitud HTTP con parámetros de fecha y categoría
     * @return \Illuminate\View\View  Vista con resultados del reporte
     */
    public function saldoAlmacen(Request $request)
    {
        $resultados = [];
        $categorias = DB::table('categorias')->get();

        if ($request->isMethod('get') && $request->has(['fecha_fin'])) {
            // Validar las fechas
            $request->validate([
                'fecha_fin' => 'required|date|before_or_equal:today',
                'id_categoria' => 'nullable|integer|exists:categorias,id_categoria',
            ]);

            $fecha_fin = $request->input('fecha_fin');
            $id_categoria = $request->input('id_categoria', null);

            // Llamar a los procedimientos almacenados
            $resultados['detalles'] = DB::select('CALL obtenerSaldoPorLote(?, ?)', [$id_categoria, $fecha_fin]);
            $resultados['totales_por_producto'] = DB::select('CALL obtenerTotalesPorProducto(?, ?)', [$id_categoria, $fecha_fin]);
            $totalesPorCategoria = DB::select('CALL obtenerTotalesPorCategoria(?, ?)', [$id_categoria, $fecha_fin]);

            // Usar array_pop() para separar el total general (última fila)
            $totalGeneral = array_pop($totalesPorCategoria); // Obtener la última fila como total general

            // Guardar el resto de los totales por categoría
            $resultados['totales_por_categoria'] = $totalesPorCategoria;
            $resultados['total_general'] = $totalGeneral;
        }

        return view('almacen.reporte.saldo', compact('resultados', 'categorias'));
    }

    /**
     * Genera un PDF con el reporte de saldo de almacén a una fecha determinada.
     * 
     * @param  Request  $request  Solicitud HTTP con parámetros de fecha y categoría
     * @return \Illuminate\Http\Response  Respuesta con el PDF generado
     */
    public function imprimirSaldoPDF(Request $request)
    {
        $request->validate([
            'fecha_fin' => 'required|date',
            'categoria_id' => 'nullable|integer|exists:Categorias,id_categoria',
        ]);

        $fecha_fin = $request->input('fecha_fin');
        $id_categoria = $request->input('id_categoria', null);

        // Llamar a los procedimientos almacenados
        $resultados['detalles'] = DB::select('CALL obtenerSaldoPorLote(?, ?)', [$id_categoria, $fecha_fin]);
        $resultados['totales_por_producto'] = DB::select('CALL obtenerTotalesPorProducto(?, ?)', [$id_categoria, $fecha_fin]);
        $totalesPorCategoria = DB::select('CALL obtenerTotalesPorCategoria(?, ?)', [$id_categoria, $fecha_fin]);

        // Usar array_pop() para separar el total general (última fila)
        $totalGeneral = array_pop($totalesPorCategoria); // Obtener la última fila como total general

        // Guardar el resto de los totales por categoría
        $resultados['totales_por_categoria'] = $totalesPorCategoria;
        $resultados['total_general'] = $totalGeneral;

        // Ruta del logo
        $logoPath = public_path('img/logo-para-pdf.jpg');
        // Datos para la vista
        $data = [
            'logoPath' => $logoPath,
            'resultados' => $resultados,
            'fecha_fin' => Carbon::parse($fecha_fin)->format('d/m/Y'),
            'fecha_impresion' => Carbon::now('America/La_Paz')->format('d/m/Y'),
        ];

        // Generar el PDF
        $pdf = Pdf::loadView('almacen.reporte.saldo_pdf', $data);

        $pdf->setPaper('letter', 'portrait')
            ->setOption('margin-top', 0) // Margen superior en mm
            ->setOption('margin-bottom', 10) // Margen inferior en mm
            ->setOption('margin-left', 10) // Margen izquierdo en mm
            ->setOption('margin-right', 10) // Margen derecho en mm
            ->setOption('footer-center', '[page]') // Pie de página centrado con número de página
            ->setOption('footer-font-size', '9'); // Tamaño de fuente del pie de página

        // Mostrar el PDF en el navegador
        return $pdf->stream('reporte_saldo_al_' . $fecha_fin . '.pdf');
    }
}
