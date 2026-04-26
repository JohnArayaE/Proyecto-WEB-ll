<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'assigned_by',
        'start_date',
        'end_date',
        'status',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
     * Relación: la asignación pertenece a un chofer (usuario)
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    /**
     * Relación: la asignación pertenece a un vehículo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    /**
     * Relación: la asignación fue creada por un operador (usuario)
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
}