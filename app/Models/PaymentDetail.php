<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory, HasUlids;
    protected $primaryKey = 'id';
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'user_id',
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
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
}
