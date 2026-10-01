<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'bin_id', 'truck_id', 'collector_id',
        'collected_at', 'status', 'notes'
    ];

    protected $casts = [
        'collected_at' => 'datetime',
    ];

    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
}