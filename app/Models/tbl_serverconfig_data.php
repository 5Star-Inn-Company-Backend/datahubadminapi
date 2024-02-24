<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_serverconfig_data extends Model
{
    use HasFactory;

    protected $table = 'tbl_serverconfig_datas';
    
    protected $fillable = [
        'name',
        'coded',
        'server',
        'category',
        'amount',
        'network',
        'network_code',
        'dataplan',
        'price',
        'plan_id',
        'status'
        ];
}
