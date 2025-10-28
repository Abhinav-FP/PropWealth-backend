<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suburb extends Model
{
    protected $fillable = [
        'suburb_id',
        'name',
        'state',
        'bbox',
    ];

    protected $casts = [
        'bbox' => 'array',
    ];
}
