<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $guarded = [];
//    protected $primaryKey = 'id_customer';

    protected $fillable = [
        'email', 'name', 'address', 'phone'
    ];

    public function order_detail()
    {
        return $this->hasMany(Order_detail::class);
    }

    public function getNameAttrobute($value){
        return ucfirst($value);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
