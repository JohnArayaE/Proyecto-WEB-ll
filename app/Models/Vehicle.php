<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Vehicle extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'plate',
        'brand',
        'model',
        'year',
        'type',
        'capacity',
        'fuel_type',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'capacity' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'vehicle_id', 'id');
    }
}
