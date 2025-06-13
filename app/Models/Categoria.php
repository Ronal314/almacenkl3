<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión de categorías de productos.
 * 
 * Este modelo representa las categorías a las que pueden pertenecer los productos
 * en el sistema de almacén.
 * 
 * @property int $id_categoria Identificador único de la categoría
 * @property string $codigo Código único de la categoría
 * @property string $descripcion Descripción o nombre de la categoría
 * @property int $estado Estado de la categoría (1: activo, 0: inactivo)
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Producto[] $productos Productos pertenecientes a esta categoría
 * 
 * @package App\Models
 */
class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = ['codigo', 'descripcion', 'estado'];

    public $timestamps = true;

    /**
     * Convierte automáticamente el código a mayúsculas.
     *
     * @param  string  $value  Valor del código
     * @return void
     */
    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper($value);
    }

    /**
     * Convierte automáticamente la descripción a mayúsculas.
     *
     * @param  string  $value  Valor de la descripción
     * @return void
     */
    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = strtoupper($value);
    }

    /**
     * Relación con los productos que pertenecen a esta categoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }
}
