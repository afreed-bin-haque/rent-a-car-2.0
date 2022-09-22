<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'mileage',
        'plate',
        'condition',
        'model',
        'type',
        'seats',
        'color',
        'main_image'
    ];
}
