<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ShoppingSession extends Model
{
    use HasFactory, HasUlids;
    protected $connection = 'mongodb';
    // protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'total'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
