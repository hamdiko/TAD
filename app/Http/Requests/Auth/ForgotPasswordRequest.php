<?php

namespace App\Http\Requests\Auth;

use App\Models\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgotPasswordRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reset_by' => 'required|in:email,phone',
            'email' => [
            	'required_if:reset_by,email',
	            'email',
                'exists:emails'
            ],
            'phone_number' => [
            	'required_if:reset_by,phone',
	            Rule::exists('phone_numbers')->where(function () {
		            return PhoneNumber::search($this->phone_number);
	            })
            ]
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
