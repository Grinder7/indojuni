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
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $guarded = [
        'id'
    ];

    protected $appends = ['default_payment_detail'];

    public function paymentDetail()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    // Add this accessor
    public function getDefaultPaymentDetailAttribute()
    {
        return $this->paymentDetail()->where('is_default', true)->first();
    }
}
