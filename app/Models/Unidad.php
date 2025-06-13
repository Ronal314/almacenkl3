<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión de unidades o departamentos.
 * 
 * Este modelo representa las unidades o departamentos que solicitan
 * productos del almacén.
 * 
 * @property int $id_unidad Identificador único de la unidad
 * @property string $jefe Nombre del jefe o responsable de la unidad
 * @property string $nombre Nombre de la unidad o departamento
 * @property string $direccion Dirección física de la unidad
 * @property string $telefono Número telefónico de contacto
 * @property int $estado Estado de la unidad (1: activo, 0: inactivo)
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Salida[] $salidas Salidas realizadas para esta unidad
 * 
 * @package App\Models
 */
class Unidad extends Model
{
    use HasFactory;
    protected $table = 'unidades';
    protected $primaryKey = 'id_unidad';

    protected $fillable = ['nombre', 'direccion', 'estado'];

    /**
     * Convierte automáticamente el nombre de la unidad a mayúsculas.
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
     * Relación con las salidas realizadas para esta unidad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salidas()
    {
        return $this->hasMany(Salida::class, 'id_unidad');
    }
}
