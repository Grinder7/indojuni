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
    public function product_id()
    {
        return $this->belongsTo(Product::class);
    }
    public function session_id()
    {
        return $this->belongsTo(ShoppingSession::class);
    }
}
