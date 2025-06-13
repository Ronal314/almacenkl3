<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngresoFormRequest;
use App\Models\DetalleIngreso;
use App\Models\Ingreso;
use App\Models\Producto;
use App\Models\Proveedor;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión de ingresos de productos al almacén.
 * 
 * Este controlador maneja las operaciones relacionadas con el registro
 * de ingresos de productos, incluyendo sus detalles y cálculos de totales.
 * 
 * @package App\Http\Controllers
 */
class IngresoController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}

    /**
     * Muestra una lista de todos los ingresos registrados.
     *
     * @return \Illuminate\View\View  Vista con lista de ingresos
     */
    public function index()
    {
        $ingresos = DB::table('vista_ingresos')->get();
        return view('almacen.ingreso.index', compact('ingresos'));
    }

    /**
     * Muestra el formulario para crear un nuevo ingreso.
     * 
     * Genera automáticamente el siguiente número de lote y prepara
     * los datos necesarios para el formulario.
     *
     * @return \Illuminate\View\View  Vista con formulario de creación
     */
    public function create()
    {
        // Obtener el siguiente número de lote basado en el último id_ingreso
        $ultimoIngreso = Ingreso::orderBy('id_ingreso', 'desc')->first();
        $siguienteLote = 'L-' . str_pad(($ultimoIngreso ? $ultimoIngreso->id_ingreso + 1 : 1), 6, '0', STR_PAD_LEFT);

        // Utilizando modelos para obtener los datos
        $proveedores = Proveedor::all()->where('estado', '=', '1');
        $productos = Producto::where('estado', '=', '1')
            ->select('id_producto', 'descripcion', 'unidad')
            ->get();

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

        return view('almacen.ingreso.create', compact("proveedores", "productos", "siguienteLote", "productosOld"));
    }

    /**
     * Almacena un nuevo ingreso y sus detalles en la base de datos.
     *
     * @param  IngresoFormRequest  $request  Solicitud HTTP validada
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(IngresoFormRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            // Crear nuevo ingreso
            $ingreso = new Ingreso();
            $ingreso->id_proveedor = $validated['id_proveedor'];
            $ingreso->id_usuario = Auth::id(); // Obtener el ID del usuario autenticado
            $ingreso->n_factura = $validated['n_factura'];
            $ingreso->n_pedido = $validated['n_pedido'];
            $ingreso->fecha_hora = Carbon::now('America/La_Paz')->toDateTimeString();
            $ingreso->total = 0; // Inicializa total a 0, lo calculamos después
            $ingreso->save();

            // Obtener los datos de los productos
            $id_producto = $request->input('id_producto', []);
            $cantidad = $request->input('cantidad', []);
            $costo_u = $request->input('costo_u', []);
            $lote = $request->input('lote', []);
            $total = 0;

            // Iterar sobre los productos y guardar los detalles de ingreso
            for ($cont = 0; $cont < count($id_producto); $cont++) {
                // Crear detalle de ingreso
                $detalle = new DetalleIngreso();
                $detalle->id_ingreso = $ingreso->id_ingreso;
                $detalle->id_producto = $id_producto[$cont];
                $detalle->cantidad_original = $cantidad[$cont];
                $detalle->cantidad_disponible = $cantidad[$cont];
                $detalle->costo_u = $costo_u[$cont];
                $detalle->lote = $lote[$cont];
                $detalle->save();

                // Calcular el total
                $total += $cantidad[$cont] * $costo_u[$cont];
            }

            // Actualizar el total del ingreso
            $ingreso->total = $total;
            $ingreso->save();

            DB::commit();

            // Redirigir después de guardar
            return redirect()->route('ingresos.index')->with('success', 'Ingreso registrado exitosamente');
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje
            DB::rollBack();
            Log::error("Error al registrar el ingreso: " . $e->getMessage());
            return redirect()->route('ingresos.index')->with(['error' => 'Error al registrar el ingreso']);
        }
    }


    /**
     * Muestra los detalles de un ingreso específico.
     *
     * @param  int  $id  Identificador del ingreso
     * @return \Illuminate\View\View  Vista con detalles del ingreso
     */
    public function show($id)
    {
        // Obtener los detalles del ingreso desde la vista
        $ingreso = DB::table('vista_ingresos')
            ->where('id_ingreso', '=', $id)
            ->first();

        $detalles = DB::select('CALL obtenerDetalleIngreso(?)', [$id]);

        // Devolver la vista con los datos del ingreso y los detalles
        return view('almacen.ingreso.show', compact("ingreso", "detalles"));
    }
}
