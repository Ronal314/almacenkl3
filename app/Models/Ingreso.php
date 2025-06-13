<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión de ingresos de productos al almacén.
 * 
 * Este modelo representa los ingresos o entradas de productos
 * al sistema de almacén.
 * 
 * @property int $id_ingreso Identificador único del ingreso
 * @property string $n_factura Número de factura del ingreso
 * @property string $n_pedido Número de pedido o orden de compra
 * @property \Illuminate\Support\Carbon $fecha_hora Fecha y hora del ingreso
 * @property float $total Monto total del ingreso
 * @property int $id_proveedor Identificador del proveedor
 * @property int $id_usuario Identificador del usuario que registró el ingreso
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \App\Models\Proveedor $proveedor Proveedor asociado al ingreso
 * @property-read \App\Models\User $user Usuario que registró el ingreso
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DetalleIngreso[] $detalleIngresos Detalles del ingreso
 * 
 * @package App\Models
 */
class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingresos';
    protected $primaryKey = 'id_ingreso';

    protected $fillable = ['n_factura', 'n_pedido', 'fecha_hora', 'total', 'id_proveedor', 'id_usuario'];

    /**
     * Relación con el proveedor asociado a este ingreso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    /**
     * Relación con el usuario que registró este ingreso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relación con los detalles de este ingreso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleIngresos()
    {
        return $this->hasMany(DetalleIngreso::class, 'id_ingreso');
    }
}
