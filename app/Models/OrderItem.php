<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity'
    ];
    public function product_id()
    {
        return $this->belongsTo(Product::class);
    }
    public function order_id()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
