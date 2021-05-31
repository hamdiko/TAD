<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

class SocialLoginRequest extends LoginRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $socialToken = $this->social_token;

        try {
            $this->merge([
                'social_token' => Crypt::decrypt($socialToken)
            ]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'token' => '',
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'social_token.token' => 'required|string',
            'social_token.provider' => 'required|in:facebook,google',
        ];
    }
}
