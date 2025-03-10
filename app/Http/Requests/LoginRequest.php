<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    public const MAX_ATTEMPT = 3;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */


    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember-me' => 'integer'
        ];
    }

    public function checkThrottle(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), LoginRequest::MAX_ATTEMPT)) {
            return;
        }
        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', ['seconds' => $seconds])
        ]);
    }
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
    public function getAttempt(): int
    {
        return RateLimiter::attempts($this->throttleKey());
    }
}
