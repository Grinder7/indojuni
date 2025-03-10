<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'img',
    ];
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;

    // public function img(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($img) => config('product.img_dir') . '/' . $img
    //     );
    // }
}
