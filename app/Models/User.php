<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'address',
        'phone',
        'gender',
        'dob',
        'bvn',
        'bank_code',
        'account_name',
        'account_number',
        'status',
        'status_reasons',
        'pin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function wallet(){
        return $this->hasMany(Wallet::class,'user_id');
    }

    function vaccts(){
        return $this->hasMany(virtual_acct::class,'user_id');
    }

    function trans(){
        return $this->hasMany(transaction::class,'user_id');
    }

    function referee(){
        return $this->belongsTo(User::class,'referer_id')->select("id","firstname","lastname");
    }
}
