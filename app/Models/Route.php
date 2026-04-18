<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'origin',
        'destination',
        'distance',
        'description'
    ];

    /**
     * Relación: una ruta puede tener muchos viajes.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'route_id', 'id');
    }
}