<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_serverconfig_betting extends Model
{
    use HasFactory;

    protected $table = 'tbl_serverconfig_betting';
    
    protected $fillable = [
        'name',
        'code',
        'discount',
        'status',
        'server'
        ];
}
