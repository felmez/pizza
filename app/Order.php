<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_id', 'product_quantity', 'total_price', 'name', 'phone', 'address',
    ];
}
