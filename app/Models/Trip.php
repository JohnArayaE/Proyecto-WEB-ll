<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Trip extends Model
{
    //

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'route_id',
        'departure_time',
        'return_time',
        'km_departure',
        'km_return',
        'observations',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'return_time' => 'datetime',
            'km_departure' => 'float',
            'km_return' => 'float',
            'deleted_at' => 'datetime',
        ];
    }

     public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
}
