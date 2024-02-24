<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_airtime2cash extends Model
{
    use HasFactory;

    protected $table = 'tbl_airtime2cashs';
    
    /**
     * Get the user associated with the airtime2cash.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
