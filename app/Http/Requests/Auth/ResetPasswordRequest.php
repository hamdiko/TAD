<?php

namespace App\Http\Requests\Auth;

use App\Models\PhoneNumber;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required_without:phone_number|email|exists:emails',

            'phone_number' => [
                'required_without:email',
                Rule::exists('phone_numbers')->where(function () {
                    return PhoneNumber::search($this->phone_number);
                })
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
	            'confirmed',
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => __('Please enter a valid email used for registration'),
            'phone_number.exists' => __('Please enter a phone number used for registration'),
        ];
    }
}
