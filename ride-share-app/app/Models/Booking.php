<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
     protected $fillable = [
        'post_id',
        'seat_booked',
        'total_fare_cost',
        'booked_by',
        'status',
    ];
}
