<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoFormRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la gestión de productos.
 * 
 * Este controlador maneja las operaciones CRUD para productos,
 * incluyendo la generación automática de códigos y cambios de estado.
 * 
 * @package App\Http\Controllers
 */
class ProductoController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}

    /**
     * Muestra una lista de todos los productos.
     *
     * @return \Illuminate\View\View  Vista con lista de productos
     */
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return view('almacen.producto.index', compact('productos'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     *
     * @return \Illuminate\View\View  Vista con formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::where('estado', '=', '1')->get();
        return view('almacen.producto.create', compact('categorias'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     *
     * @param  ProductoFormRequest  $request  Solicitud HTTP validada
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(ProductoFormRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['estado'] = 1;
            $producto = new Producto($validated);

            // Guardar el nuevo producto en la base de datos
            $producto->save();

            // Redirigir después de guardar
            return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje
            Log::error("Error al crear el producto: " . $e->getMessage());
            return redirect()->route('productos.index')->with(['error' => 'Error al crear el producto']);
        }
    }

    /**
     * Muestra el formulario para editar un producto específico.
     *
     * @param  int  $id  Identificador del producto
     * @return \Illuminate\View\View  Vista con formulario de edición
     */
    public function edit($id)
    {
        $categorias = Categoria::where('estado', '=', '1')->get();
        $producto = Producto::findOrFail($id);
        return view('almacen.producto.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualiza la información de un producto existente.
     *
     * @param  ProductoFormRequest  $request  Solicitud HTTP validada
     * @param  string  $id  Identificador del producto
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function update(ProductoFormRequest $request, string $id)
    {
        try {
            // Encontrar el producto por su ID
            $producto = Producto::findOrFail($id);
            $validated = $request->validated();
            $producto->fill($validated);

            // Guardar los cambios en la base de datos
            $producto->save();

            // Redirigir después de guardar
            return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje
            Log::error("Error al actualizar el producto: " . $e->getMessage());
            return redirect()->route('productos.index')->with(['error' => 'Error al actualizar el producto']);
        }
    }

    /**
     * Genera automáticamente un código único para un nuevo producto basado en su categoría.
     *
     * @param  int  $id_categoria  Identificador de la categoría
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con el código generado
     */
    public function generarCodigo($id_categoria)
    {
        // Encontrar la categoría por ID
        $categoria = Categoria::find($id_categoria);
        if (!$categoria) {
            return response()->json(['codigo' => ''], 404);
        }

        // Obtener el código de la categoría
        $categoriaCodigo = $categoria->codigo;

        // Buscar el último producto de la categoría ordenado por su código en orden descendente
        $ultimoProducto = Producto::where('id_categoria', $id_categoria)
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "-", -1) AS UNSIGNED) DESC')
            ->first();

        // Definir el nuevo número como 1 por defecto
        $nuevoNumero = 1;

        // Si hay un último producto, extraer el número y sumar 1
        if ($ultimoProducto) {
            $ultimoCodigo = $ultimoProducto->codigo;
            $partesCodigo = explode('-', $ultimoCodigo);
            if (count($partesCodigo) == 2 && is_numeric($partesCodigo[1])) {
                $nuevoNumero = intval($partesCodigo[1]) + 1;
            }
        }

        // Generar el nuevo código con el número incrementado
        $codigo = $categoriaCodigo . '-' . str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);

        // Retornar el código generado en una respuesta JSON
        return response()->json(['codigo' => $codigo]);
    }

    /**
     * Cambia el estado de un producto (activo/inactivo).
     *
     * @param  Request  $request  Solicitud HTTP con el nuevo estado
     * @param  int  $id  Identificador del producto
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con resultado de la operación
     */
    public function toggleEstado(Request $request, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->estado = $request->estado;
            $producto->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error("Error al cambiar el estado del producto: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error al cambiar el estado del producto']);
        }
    }
}
