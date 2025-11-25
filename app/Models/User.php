<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUlids;
    protected $fillable = [
        'username',
        'email',
        'password',
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
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $guarded = [
        'is_admin',
        'id',
    ];

    protected $appends = ['default_payment_detail'];

    public function paymentDetail()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    // Add this accessor
    public function getDefaultPaymentDetailAttribute()
    {
        if (!$this->card_number) {
            return null;
        }
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'postcode' => $this->postcode,
            'card_name' => $this->card_name,
            'card_number' => $this->card_number,
            'card_type' => $this->card_type === 1 ? 'Kartu Kredit' : 'Kartu Debit',
            'card_expiration' => $this->card_expiration,
            'card_cvv' => $this->card_cvv,
        ];
    }
}
