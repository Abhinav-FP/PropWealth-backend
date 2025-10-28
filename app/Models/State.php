<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_code',
        'name',
    ];

    protected $primaryKey = 'state_code';

    public $incrementing = false;

    protected $keyType = 'string';
}
