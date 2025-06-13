<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión de salidas de productos del almacén.
 * 
 * Este modelo representa las salidas o entregas de productos
 * a diferentes unidades o departamentos.
 * 
 * @property int $id_salida Identificador único de la salida
 * @property string $n_hoja_ruta Número de hoja de ruta asociada
 * @property string $n_pedido Número de pedido o solicitud
 * @property \Illuminate\Support\Carbon $fecha_hora Fecha y hora de la salida
 * @property float $total Monto total de la salida
 * @property int $id_unidad Identificador de la unidad solicitante
 * @property int $id_usuario Identificador del usuario que registró la salida
 * @property \Illuminate\Support\Carbon $created_at Fecha de creación
 * @property \Illuminate\Support\Carbon $updated_at Fecha de actualización
 * 
 * @property-read \App\Models\Unidad $unidad Unidad asociada a la salida
 * @property-read \App\Models\User $usuario Usuario que registró la salida
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DetalleSalida[] $detalleSalida Detalles de la salida
 * 
 * @package App\Models
 */
class Salida extends Model
{
    use HasFactory;
    protected $table = 'salidas';
    protected $primaryKey = 'id_salida';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['n_hoja_ruta', 'n_pedido', 'fecha_hora', 'total', 'id_unidad', 'id_usuario'];

    /**
     * Relación con la unidad asociada a esta salida.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad');
    }

    /**
     * Relación con el usuario que registró esta salida.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relación con los detalles de esta salida.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleSalida()
    {
        return $this->hasMany(DetalleSalida::class, 'id_salida');
    }
}
