<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'status',
        'observations',
        'approved_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}