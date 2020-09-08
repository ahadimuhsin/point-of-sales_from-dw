<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    //
//    protected $primaryKey = 'order_detail_id';

    protected $fillable = [
        'order_id', 'product_id', 'qty', 'price'
    ];

    protected $guarded = [];

    public function order()
    {
        //1st argument, related model
        //2nd argument, related column of order table
        //3rd argument, related column of order_detail table
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
