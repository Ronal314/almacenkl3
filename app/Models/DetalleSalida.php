<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los detalles de salidas de productos.
 * 
 * Este modelo representa los detalles o líneas individuales de una salida
 * de productos del almacén.
 * 
 * @property int $id Identificador único del detalle de salida
 * @property string $lote Número de lote del producto
 * @property int $cantidad Cantidad de productos entregados
 * @property float $costo_u Costo unitario del producto
 * @property int $id_salida Identificador de la salida asociada
 * @property int $id_producto Identificador del producto
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \App\Models\Salida $salida Salida a la que pertenece este detalle
 * @property-read \App\Models\Producto $producto Producto asociado a este detalle
 * 
 * @package App\Models
 */
class DetalleSalida extends Model
{
    use HasFactory;


    protected $table = 'detalle_salidas';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'lote',
        'cantidad',
        'costo_u',
        'id_salida',
        'id_producto'
    ];

    /**
     * Relación con la salida a la que pertenece este detalle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salida()
    {
        return $this->belongsTo(Salida::class, 'id_salida');
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
