<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorFormRequest;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la gestión de proveedores.
 * 
 * Este controlador maneja las operaciones CRUD para proveedores,
 * incluyendo la activación/desactivación de los mismos.
 * 
 * @package App\Http\Controllers
 */
class ProveedorController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}

    /**
     * Muestra una lista de todos los proveedores.
     *
     * @return \Illuminate\View\View  Vista con lista de proveedores
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('almacen.proveedor.index', compact('proveedores'));
    }

    /**
     * Muestra el formulario para crear un nuevo proveedor.
     *
     * @return \Illuminate\View\View  Vista con formulario de creación
     */
    public function create()
    {
        return view('almacen.proveedor.create');
    }

    /**
     * Almacena un nuevo proveedor en la base de datos.
     *
     * @param  ProveedorFormRequest  $request  Solicitud HTTP validada
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(ProveedorFormRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['estado'] = 1;
            Proveedor::create($validated);
            return redirect()->route('proveedores.index')->with('success', 'Proveedor guardado correctamente');
        } catch (\Exception $e) {
            Log::error("Error al guardar el Proveedor: " . $e->getMessage());
            return redirect()->route('proveedores.index')->with(['error' => 'Error al guardar el Proveedor']);
        }
    }

    /**
     * Muestra el formulario para editar un proveedor específico.
     *
     * @param  int  $id  Identificador del proveedor
     * @return \Illuminate\View\View  Vista con formulario de edición
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('almacen.proveedor.edit', compact('proveedor'));
    }

    /**
     * Actualiza la información de un proveedor específico.
     *
     * @param  ProveedorFormRequest  $request  Solicitud HTTP validada
     * @param  int  $id  Identificador del proveedor
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function update(ProveedorFormRequest $request, $id)
    {
        try {
            $categoria = Proveedor::findOrFail($id);
            $validated = $request->validated();
            $categoria->update($validated);
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente');
        } catch (\Exception $e) {
            Log::error("Error al actualizar el Proveedor: " . $e->getMessage());
            return redirect()->route('proveedores.index')->with(['error' => 'Error al actualizar el Proveedor']);
        }
    }

    /**
     * Cambia el estado de un proveedor (activo/inactivo).
     *
     * @param  Request  $request  Solicitud HTTP con el nuevo estado
     * @param  int  $id  Identificador del proveedor
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con resultado de la operación
     */
    public function toggleEstado(Request $request, $id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->estado = $request->estado;
            $proveedor->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error("Error al cambiar el estado del proveedor: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error al cambiar el estado del proveedor']);
        }
    }
}
