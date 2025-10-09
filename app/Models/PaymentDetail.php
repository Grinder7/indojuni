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
        'email',
        'address',
        'address2',
        'payment_method',
        'card_name',
        'card_number',
        'card_expiration',
        'card_cvv',
        'zip',
        'remember_detail'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
