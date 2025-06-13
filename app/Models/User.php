<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo para la gestión de usuarios del sistema.
 * 
 * Este modelo representa a los usuarios que pueden acceder al sistema
 * y realizar operaciones según sus permisos.
 * 
 * @property int $id Identificador único del usuario
 * @property string $name Nombre completo del usuario
 * @property string $ci Cédula de identidad (usada para autenticación)
 * @property string $password Contraseña del usuario (encriptada)
 * @property string $remember_token Token para recordar sesión
 * @property int $estado Estado del usuario (1: activo, 0: inactivo)
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ingreso[] $ingresos Ingresos registrados por este usuario
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Salida[] $salidas Salidas registradas por este usuario
 * 
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'ci',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    /**
     * Obtiene el identificador de autenticación del usuario.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey(); // Esto asegura que devuelva la clave primaria (id)
    }

    /**
     * Relación con los ingresos registrados por este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class, 'id');
    }

    /**
     * Relación con las salidas registradas por este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salidas()
    {
        return $this->hasMany(Salida::class, 'id');
    }
}
