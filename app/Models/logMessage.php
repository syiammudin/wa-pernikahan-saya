<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'nama',
        'nomor',
        'message',
    ];
}
