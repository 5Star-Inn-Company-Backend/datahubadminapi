<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_serverconfig_airtime extends Model
{
    use HasFactory;
    protected $table = 'tbl_serverconfig_airtimes';
    
    protected $fillable = [
        'network',
        'server',
        'discount',
        'status'
        ];
}
