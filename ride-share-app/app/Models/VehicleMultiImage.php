<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMultiImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'img_description',
        'img_path',
    ];
}
