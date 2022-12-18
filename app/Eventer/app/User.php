<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Address;

class User extends Authenticatable //implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAgent(){
        if($this->user_role == 'agent'){
            return true;
        }
        return false;
    }

    public function isAdmin(){
        if($this->user_role == 'admin'){
            return true;
        }
        return false;
    }

    public function isStaff(){
        if($this->isAdmin() || $this->isAgent()){
            return true;
        }
        return false;
    }

    public function getAddress(){
        return Address::find($this->address_id);
    }
}
