<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    //
    use HasFactory, SoftDeletes;

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
}
