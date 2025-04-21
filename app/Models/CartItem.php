<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    protected $fillable = [
        'session_id',
        'product_id',
        'quantity'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function shoppingSession()
    {
        return $this->belongsTo(ShoppingSession::class, "session_id");
    }
}
