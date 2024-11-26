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

    public function user(){
        return $this->belongsTo(User::class,'user_id')->select('id','firstname','lastname', 'phone', 'email');
    }
}
