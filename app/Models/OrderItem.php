<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, HasUlids;
    protected $connection = 'mongodb';
    // protected $guarded = ['id'];
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, "order_id");
    }
}
