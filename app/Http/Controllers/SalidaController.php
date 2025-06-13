<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalidaFormRequest;
use App\Models\DetalleSalida;
use App\Models\Salida;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador para la gestión de salidas de productos del almacén.
 * 
 * Este controlador maneja las operaciones relacionadas con el registro
 * de salidas de productos, incluyendo sus detalles, generación de reportes
 * y actualización de inventario.
 * 
 * @package App\Http\Controllers
 */
class SalidaController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}

    /**
     * Muestra una lista de todas las salidas registradas.
     *
     * @return \Illuminate\View\View  Vista con lista de salidas
     */
    public function index()
    {
        $salidas = DB::table('vista_salidas')->get();
        return view('almacen.salida.index', compact('salidas'));
    }

    /**
     * Muestra el formulario para crear una nueva salida.
     * 
     * Prepara los datos necesarios para el formulario, incluyendo
     * unidades y productos con stock disponible.
     *
     * @param  Request  $request  Solicitud HTTP
     * @return \Illuminate\View\View  Vista con formulario de creación
     */
    public function create(Request $request)
    {
        $unidades = Unidad::all()->where('estado', '=', '1');
        // Obtener productos con su stock total
        $productos = DB::select("SELECT p.id_producto, p.descripcion, p.unidad, SUM(di.cantidad_disponible) AS stock_total
                                 FROM productos p
                                 INNER JOIN detalle_ingresos di ON p.id_producto = di.id_producto
                                 WHERE p.stock > 0 AND di.cantidad_disponible > 0 AND p.estado = 1
                                 GROUP BY p.id_producto, p.descripcion, p.unidad");
        $productosOld = [];
        if (old('id_producto')) {
            foreach (old('id_producto') as $index => $idProducto) {
                $producto = collect($productos)->firstWhere('id_producto', $idProducto);
                $productosOld[$index] = [
                    'id_producto' => $idProducto,
                    'producto' => $producto->descripcion ?? '',
                    'unidad' => old('unidad')[$index] ?? '',
                    'lote' => old('lote')[$index] ?? '',
                    'cantidad' => old('cantidad')[$index] ?? '',
                    'costo_u' => old('costo_u')[$index] ?? ''
                ];
            }
        }
        return view('almacen.salida.create', compact("unidades", "productos", "productosOld"));
    }

    /**
     * Almacena una nueva salida y sus detalles en la base de datos.
     * 
     * Actualiza también el inventario disminuyendo las cantidades disponibles
     * de los productos seleccionados.
     *
     * @param  SalidaFormRequest  $request  Solicitud HTTP validada
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(SalidaFormRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();

            // Crear nuevo salida
            $salida = new Salida();
            $salida->id_unidad = $validated['id_unidad'];
            $salida->id_usuario = Auth::id(); // Obtener el ID del usuario autenticado
            $salida->n_hoja_ruta = $validated['n_hoja_ruta'];
            $salida->n_pedido = $validated['n_pedido'];
            $salida->fecha_hora = Carbon::now('America/La_Paz')->toDateTimeString();
            $salida->total = 0;
            $salida->save();

            // Obtener los datos de los productos
            $id_producto = $request->input('id_producto', []);
            $cantidad = $request->input('cantidad', []);
            $lote = $request->input('lote', []);
            $costo_u = $request->input('costo_u', []);
            $total = 0;

            // Iterar sobre los productos y guardar los detalles de salida
            for ($cont = 0; $cont < count($id_producto); $cont++) {
                // Crear detalle de salida
                $detalle = new DetalleSalida();
                $detalle->id_salida = $salida->id_salida;
                $detalle->id_producto = $id_producto[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->costo_u = $costo_u[$cont];
                $detalle->lote = $lote[$cont];
                $detalle->save();

                // Restar la cantidad del detalle de ingreso
                DB::table('detalle_ingresos')
                    ->where('id_producto', $id_producto[$cont])
                    ->where('lote', $lote[$cont])
                    ->decrement('cantidad_disponible', $cantidad[$cont]);

                // Calcular el total
                $total += $cantidad[$cont] * $costo_u[$cont];
            }

            // Actualizar el total del salida
            $salida->total = $total;
            $salida->save();

            DB::commit();

            // Redirigir después de guardar
            return redirect()->route('salidas.index')->with('success', 'Salida registrada exitosamente');
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje
            DB::rollBack();
            Log::error("Error al registrar la salida: " . $e->getMessage());
            return redirect()->route('salidas.index')->with(['error' => 'Error al registrar la salida']);
        }
    }

    /**
     * Muestra los detalles de una salida específica.
     *
     * @param  int  $id  Identificador de la salida
     * @return \Illuminate\View\View  Vista con detalles de la salida
     */
    public function show($id)
    {
        // Obtener los detalles del salida desde la vista
        $salida = DB::table('vista_salidas')
            ->where('id_salida', '=', $id)
            ->first();

        $detalles = DB::select('CALL obtenerDetalleSalida(?)', [$id]);

        // Devolver la vista con los datos del salida y los detalles
        return view('almacen.salida.show', compact("salida", "detalles"));
    }

    /**
     * Obtiene los lotes disponibles para un producto específico.
     * 
     * Retorna un JSON con los lotes que tienen cantidad disponible
     * para el producto solicitado.
     *
     * @param  int  $idProducto  Identificador del producto
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con lotes disponibles
     */
    public function getLotesDisponibles($idProducto)
    {
        $lotes = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.id_ingreso', '=', 'ingresos.id_ingreso')
            ->where('detalle_ingresos.id_producto', $idProducto)
            ->where('detalle_ingresos.cantidad_disponible', '>', 0)
            ->orderBy('ingresos.fecha_hora', 'asc')
            ->select('detalle_ingresos.*')
            ->get();

        return response()->json($lotes);
    }


    /**
     * Genera un PDF con los detalles de una salida específica.
     * 
     * El PDF incluye información de la salida, detalles por categoría
     * y opcionalmente puede mostrar los costos.
     *
     * @param  Request  $request  Solicitud HTTP con opciones de visualización
     * @param  int  $id  Identificador de la salida
     * @return \Illuminate\Http\Response  Respuesta con el PDF generado
     */
    public function imprimirSalidaPDF(Request $request, $id)
    {
        $salida = DB::table('vista_salidas')
            ->where('id_salida', '=', $id)
            ->first();

        $detalles = collect(DB::select('CALL obtenerDetalleSalida(?)', [$id]));
        $categorias = $detalles->pluck('categoria')->unique();
        $logoPath = public_path('img/logo-para-pdf.jpg');
        $data = [
            'logoPath' => $logoPath,
            'fecha' => Carbon::parse($salida->fecha_hora)->format('d/m/Y'),
            'salida' => $salida,
            'detalles' => $detalles,
            'categorias' => $categorias,
            'mostrarCostos' => $request->input('mostrarCostos', false),
        ];

        $pdf = Pdf::loadView('almacen.reporte.salida_pdf', $data);

        $pdf->setPaper('letter', 'portrait')
            ->setOption('margin-top', 0) // Margen superior en mm
            ->setOption('margin-bottom', 10) // Margen inferior en mm
            ->setOption('margin-left', 10) // Margen izquierdo en mm
            ->setOption('margin-right', 10) // Margen derecho en mm
            ->setOption('footer-center', '[page]') // Pie de página centrado con número de página
            ->setOption('footer-font-size', '9'); // Tamaño de fuente del pie de página

        // Mostrar el PDF en el navegador
        return $pdf->stream('reporte_salida_' . $id . '.pdf');
    }
}
