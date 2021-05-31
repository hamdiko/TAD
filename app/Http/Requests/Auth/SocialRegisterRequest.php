<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class SocialRegisterRequest extends RegisterRequest
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

        parent::prepareForValidation();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return array_merge($this->getRules(), [
            'social_token.token' => 'required|string',
            'social_token.provider' => 'required|in:facebook,google',
        ]);
    }
}
