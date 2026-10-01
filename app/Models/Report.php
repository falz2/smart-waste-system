<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bin_id', 'type', 'description',
        'latitude', 'longitude', 'photo', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }
}