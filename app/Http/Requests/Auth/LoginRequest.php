<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identifier' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return User
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $user = User::phoneOrEmail($this->identifier)->first();

        if ($user) {
            if ($user->isLocked()) {
                throw ValidationException::withMessages([
                    'identifier' => __('auth.account_locked'),
                ]);
            }
            $this->ensureIsNotRateLimited();
            if (Hash::check($this->password, $user->password)) {
                RateLimiter::clear($this->throttleKey());
                return $user;
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => __('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        RateLimiter::clear($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => __("auth.throttle"),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('identifier')) . '|' . $this->ip();
    }

    /**
     * Check if the user has verified the email and the phone number
     *
     * @param User
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyUser(User $user)
    {
        $this->verifyEmail($user);

        $this->verifyPhoneNumber($user);
    }


    /**
     * Check if the user has verified the email
     *
     * @param User $user
     * @param Boolean sendVerificationEmail
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyEmail(User $user, $sendVerificationEmail = false)
    {

        $emailVerified = $user->hasVerifiedEmail();

        $email = $user->emails()->where('email', $this->identifier)->first();

        if (!$emailVerified || ($email && $email->unverified())) {

            if ($email && $sendVerificationEmail) {
                $email->sendEmailVerificationNotification();
            }

            abort(403,  __('auth.email_not_verified'));
        }
    }

    /**
     * Check if the user has verified the phone number
     *
     * @param User
     * @param Boolean $sendOTPMessage
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function verifyPhoneNumber(User $user, $sendOTPMessage = false)
    {
        if (!$user->mustVerifyPhoneNumber()) return;

        $phoneVerified = $user->hasVerifiedPhoneNumber();

        $phoneNumber = $user->phoneNumbers()->search($this->identifier)->first();

        if (!$phoneVerified || ($phoneNumber && $phoneNumber->unverified())) {

            if ($phoneNumber && $sendOTPMessage) {
                $phoneNumber->sendPhoneVerificationNotification();
            }

            abort(403,  __('auth.phone_not_verified'));
        }
    }
}
