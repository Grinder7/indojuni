<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'user_id',
        'is_default',
        'firstname',
        'lastname',
        'address',
        'city',
        'province',
        'postcode',
        'card_name',
        'card_number',
        'card_type',
        'card_expiration',
        'card_cvv',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
