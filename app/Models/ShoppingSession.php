<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingSession extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'total'
    ];
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
}
