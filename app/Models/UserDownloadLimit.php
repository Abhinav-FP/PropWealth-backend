<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDownloadLimit extends Model
{
    use HasFactory;

    protected $table = 'user_download_limits';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'daily_limit',    // Now only daily limit
        'lifetime_limit', // Now only lifetime limit
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'daily_limit' => 'integer',    // Cast for daily limit
        'lifetime_limit' => 'integer', // Cast for lifetime limit
    ];
}
