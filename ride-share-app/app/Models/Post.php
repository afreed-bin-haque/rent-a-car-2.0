<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
     protected $fillable = [
        'from_loc',
        'to_loc',
        'vehicle_id',
        'plate',
        'jurney_date',
        'seat',
        'price_per_seat',
        'total_fare',
        'author',
        'status',
    ];
}
