<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión de proveedores.
 * 
 * Este modelo representa a los proveedores que suministran productos
 * al almacén.
 * 
 * @property int $id_proveedor Identificador único del proveedor
 * @property string $razon_social Razón social del proveedor
 * @property string $nombre Nombre o denominación comercial
 * @property string $nit Número de Identificación Tributaria
 * @property string $direccion Dirección física del proveedor
 * @property string $telefono Número telefónico de contacto
 * @property int $estado Estado del proveedor (1: activo, 0: inactivo)
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ingreso[] $ingresos Ingresos realizados por este proveedor
 * 
 * @package App\Models
 */
class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';

    protected $fillable = ['razon_social', 'nombre', 'nit', 'direccion', 'telefono', 'estado'];

    /**
     * Convierte automáticamente la razón social a mayúsculas.
     *
     * @param  string  $value  Valor de la razón social
     * @return void
     */
    public function setRazonSocialAttribute($value)
    {
        $this->attributes['razon_social'] = strtoupper($value);
    }

    /**
     * Convierte automáticamente el nombre a mayúsculas.
     *
     * @param  string  $value  Valor del nombre
     * @return void
     */
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    /**
     * Convierte automáticamente la dirección a mayúsculas.
     *
     * @param  string  $value  Valor de la dirección
     * @return void
     */
    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = strtoupper($value);
    }

    /**
     * Relación con los ingresos realizados por este proveedor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class, 'id_proveedor');
    }
}
