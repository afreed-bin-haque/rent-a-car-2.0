<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratting extends Model
{
    use HasFactory;
     protected $fillable = [
        'comment',
        'rate',
        'rider',
        'author',
    ];
}
