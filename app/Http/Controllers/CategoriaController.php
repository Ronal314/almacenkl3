<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaFormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de categorías de productos.
 * 
 * Este controlador maneja las operaciones CRUD para categorías,
 * incluyendo la activación/desactivación de las mismas.
 * 
 * @package App\Http\Controllers
 */
class CategoriaController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}

    /**
     * Muestra una lista de todas las categorías.
     *
     * @return \Illuminate\View\View  Vista con lista de categorías
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('almacen.categoria.index', compact('categorias'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     *
     * @return \Illuminate\View\View  Vista con formulario de creación
     */
    public function create()
    {
        return view('almacen.categoria.create');
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     *
     * @param  CategoriaFormRequest  $request  Solicitud HTTP validada
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(CategoriaFormRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['estado'] = 1;
            Categoria::create($validated);

            return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al guardar la categoría: " . $e->getMessage());
            return redirect()->route('categorias.index')->with(['error' => 'Error al guardar la categoría']);
        }
    }

    /**
     * Muestra el formulario para editar una categoría específica.
     *
     * @param  int  $id  Identificador de la categoría
     * @return \Illuminate\View\View  Vista con formulario de edición
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('almacen.categoria.edit', compact('categoria'));
    }

    /**
     * Actualiza la información de una categoría específica.
     *
     * @param  CategoriaFormRequest  $request  Solicitud HTTP validada
     * @param  int  $id  Identificador de la categoría
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function update(CategoriaFormRequest $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $validated = $request->validated();
            $categoria->update($validated);

            return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar la categoría: " . $e->getMessage());
            return redirect()->route('categorias.index')->with(['error' => 'Error al actualizar la categoría']);
        }
    }

    /**
     * Cambia el estado de una categoría (activa/inactiva).
     *
     * @param  Request  $request  Solicitud HTTP con el nuevo estado
     * @param  int  $id  Identificador de la categoría
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con resultado de la operación
     */
    public function toggleEstado(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->estado = $request->estado;
            $categoria->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error("Error al cambiar el estado de la categoría: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error al cambiar el estado de la categoría']);
        }
    }
}
