<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = [
        'bin_code', 'location_name', 'latitude', 'longitude',
        'fill_level', 'status', 'is_active'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function scopeFull($query)
    {
        return $query->where('fill_level', '>=', 80);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}