<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UsuarioFormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para la gestión de usuarios del sistema.
 * 
 * Este controlador maneja las operaciones CRUD para usuarios, 
 * así como la actualización de contraseñas.
 * 
 * @package App\Http\Controllers
 */
class UsuarioController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct() {}

    /**
     * Muestra una lista paginada de todos los usuarios.
     *
     * @param  Request  $request  Solicitud HTTP con parámetros de búsqueda
     * @return \Illuminate\View\View  Vista con lista de usuarios
     */
    public function index(Request $request)
    {
        $buscar = trim($request->get('buscar'));
        $usuarios = User::where('name', 'LIKE', '%' . $buscar . '%')
            ->orWhere('ci', 'LIKE', '%' . $buscar . '%')
            ->orderBy('id', 'desc')
            ->paginate(5);
        return view('almacen.usuario.index', compact('usuarios', 'buscar'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View  Vista con formulario de creación
     */
    public function create()
    {
        return view('almacen.usuario.create');
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     *
     * @param  UsuarioFormRequest  $request  Solicitud HTTP validada
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(UsuarioFormRequest $request)
    {
        try {
            $validated = $request->validated();
            User::create($validated);
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al guardar el usuario: " . $e->getMessage());
            return back()->with(['error' => 'Error al guardar el usuario']);
        }
    }

    /**
     * Muestra el formulario para editar un usuario específico.
     *
     * @param  int  $id  Identificador del usuario
     * @return \Illuminate\View\View  Vista con formulario de edición
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('almacen.usuario.edit', compact('usuario'));
    }

    /**
     * Actualiza la información de un usuario específico.
     *
     * @param  UsuarioFormRequest  $request  Solicitud HTTP validada
     * @param  int  $id  Identificador del usuario
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function update(UsuarioFormRequest $request, $id)
    {
        try {
            $usuario = User::findOrFail($id);
            $validated = $request->validated();
            $usuario->update($validated);
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar el usuario: " . $e->getMessage());
            return back()->with(['error' => 'Error al actualizar el usuario']);
        }
    }

    /**
     * Desactiva un usuario (soft delete).
     *
     * @param  int  $id  Identificador del usuario
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function destroy($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->estado = 0;
            $usuario->save();
            return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado correctamente');
        } catch (\Exception $e) {
            Log::error("Error al desactivar el usuario: " . $e->getMessage());
            return back()->with(['error' => 'Error al desactivar el usuario']);
        }
    }

    /**
     * Muestra el formulario para cambiar la contraseña del usuario.
     *
     * @return \Illuminate\View\View  Vista con formulario para cambio de contraseña
     */
    public function showChangePasswordForm()
    {
        return view('usuarios.change-password');
    }

    /**
     * Actualiza la contraseña del usuario autenticado.
     *
     * @param  Request  $request  Solicitud HTTP con las contraseñas
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function updatePassword(Request $request)
    {
        // Validar los datos
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Obtener el usuario autenticado
        $usuario = auth()->user();

        // Verificar la contraseña actual
        if (!Hash::check($request->current_password, $usuario->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        // Actualizar la contraseña directamente en la base de datos
        DB::table('users')
            ->where('id', $usuario->id)
            ->update(['password' => bcrypt($request->new_password)]);

        // Redirigir con éxito
        return redirect()->route('usuarios.change-password')->with('success', 'Contraseña actualizada correctamente.');
    }
}
