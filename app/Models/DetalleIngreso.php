<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los detalles de ingresos de productos.
 * 
 * Este modelo representa los detalles o líneas individuales de un ingreso
 * de productos al almacén.
 * 
 * @property int $id Identificador único del detalle de ingreso
 * @property string $lote Número de lote del producto
 * @property int $cantidad_original Cantidad original ingresada
 * @property int $cantidad_disponible Cantidad disponible actual
 * @property float $costo_u Costo unitario del producto
 * @property int $id_ingreso Identificador del ingreso asociado
 * @property int $id_producto Identificador del producto
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \App\Models\Ingreso $ingreso Ingreso al que pertenece este detalle
 * @property-read \App\Models\Producto $producto Producto asociado a este detalle
 * 
 * @package App\Models
 */
class DetalleIngreso extends Model
{
    use HasFactory;

    protected $table = 'detalle_ingresos';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['lote', 'cantidad_original', 'cantidad_disponible', 'costo_u', 'id_ingreso', 'id_producto'];

    /**
     * Relación con el ingreso al que pertenece este detalle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso');
    }

    /**
     * Relación con el producto asociado a este detalle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
