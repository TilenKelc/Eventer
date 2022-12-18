<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getAddress(){
        return Address::find($this->address_id)->street;
    }

    public function getPostalCode(){
        return Address::find($this->address_id)->postal_code;
    }

    public function getCity(){
        return Address::find($this->address_id)->city;
    }
}
