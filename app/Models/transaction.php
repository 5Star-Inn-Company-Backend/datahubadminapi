<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'charges',
        'commission',
        'recipient',
        'remark',
        'token',
        'prev_balance',
        'new_balance',
        'server',
        'status',
        'reference',
        'type',
    ];
}
