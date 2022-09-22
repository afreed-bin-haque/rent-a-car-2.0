<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'api_user',
        'token',
        'access',
        'limit',
        'limit_range',
        'status',
    ];
}
