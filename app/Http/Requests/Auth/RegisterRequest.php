<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use App\Models\PhoneNumber;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber as LaravelPhoneNumber;

class RegisterRequest extends FormRequest
{

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'phone_number' => str_replace(' ', '', $this->phone_number),
            'formatted_phone_number' => LaravelPhoneNumber::make("{$this->country_code}{$this->phone_number}"),
            'role' => strtolower($this->role),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->getRules(), [
            'email' => [
                'required',
                'email:filter',
                Rule::unique('emails', 'email')->where(function ($query) {
                    return $query->whereNotNull('verified_at');
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
            'password_confirmation' => 'required'
        ]);
    }

    /**
     * Get the common rules for registration using social account or using the default registration
     *
     * @return array
     */
    protected function getRules()
    {
        $rules = [];

        if ($this->role === UserRole::INSTITUTE) {
            $rules = $this->getInstituteRules();
        } else if ($this->role === UserRole::TUTOR) {
            $rules = $this->getTutorRules();
        }


        return array_merge($rules, [
            'role' => [
                'required',
                'exists:roles,name',
                Rule::notIn(['admin'])
            ],
        ]);
    }

    protected function getTutorRules()
    {
        return [
            'first_name_ar' => 'nullable|min:2|regex:/\p{Arabic}/u|max:25',
            'last_name_ar' => 'nullable|min:2|regex:/\p{Arabic}/u|max:25',
            'first_name_en' => 'required|min:2|alpha_spaces|max:25',
            'last_name_en' => 'required|min:2|alpha_spaces|max:25',
            'phone_number' => [
                'required',
                Rule::unique('phone_numbers')->where(function ($query) {
                    return PhoneNumber::search($this->phone_number)->verified();
                })
            ],
            'formatted_phone_number' => [
                'required',
                'phone:auto',
            ],
        ];
    }


    protected function getInstituteRules()
    {
        return [
            'name_ar' => 'nullable|min:2|regex:/\p{Arabic}/u|max:50',
            'name_en' => 'required|min:2|alpha_spaces|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => __('auth.email_already_exist'),
            'phone_number.unique' => __('auth.phone_already_exist'),
        ];
    }
}
