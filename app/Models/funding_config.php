<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class funding_config extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'charges',
        'description',
    ];
}
