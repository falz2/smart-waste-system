<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number', 'driver_name', 'driver_phone',
        'capacity', 'status'
    ];

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}