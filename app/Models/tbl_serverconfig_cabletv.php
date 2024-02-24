<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_serverconfig_cabletv extends Model
{
    use HasFactory;

    protected $table = 'tbl_serverconfig_cabletvs';
    
    protected $fillable = [
        'type',
        'name',
        'coded',
        'code',
        'price',
        'discount',
        'status',
        'server'
        ];
}
