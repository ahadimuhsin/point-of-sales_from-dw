<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
//    protected $primaryKey = 'id_order';
    protected $fillable = [
        'invoice', 'customer_id', 'user_id', 'total'
    ];

    public function order_detail()
    {
        //1st argument, related model
        //2nd argument, related column of order_details table
        //3rd argument, related column of order table
        return $this->hasMany(Order_detail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
