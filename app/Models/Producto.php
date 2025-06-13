<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión de productos del almacén.
 * 
 * Este modelo representa los productos almacenados en el sistema,
 * con sus características y relaciones con otras entidades.
 * 
 * @property int $id_producto Identificador único del producto
 * @property string $codigo Código único del producto
 * @property string $descripcion Descripción o nombre del producto
 * @property int $stock Cantidad disponible en stock
 * @property string $unidad Unidad de medida del producto
 * @property int $estado Estado del producto (1: activo, 0: inactivo)
 * @property int $id_categoria Identificador de la categoría a la que pertenece
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \App\Models\Categoria $categoria Categoría a la que pertenece el producto
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DetalleIngreso[] $detalleIngresos Detalles de ingresos asociados
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DetalleSalida[] $detalleSalidas Detalles de salidas asociados
 * 
 * @package App\Models
 */
class Producto extends Model
{
    use HasFactory;
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = ['codigo', 'descripcion', 'stock', 'unidad', 'estado', 'id_categoria'];

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
     * Convierte automáticamente la unidad a mayúsculas.
     *
     * @param  string  $value  Valor de la unidad
     * @return void
     */
    public function setUnidadAttribute($value)
    {
        $this->attributes['unidad'] = strtoupper($value);
    }

    /**
     * Relación con la categoría a la que pertenece este producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    /**
     * Relación con los detalles de ingresos asociados a este producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleIngresos()
    {
        return $this->hasMany(DetalleIngreso::class, 'id_producto');
    }

    /**
     * Relación con los detalles de salidas asociados a este producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleSalidas()
    {
        return $this->hasMany(DetalleSalida::class, 'id_producto');
    }
}
