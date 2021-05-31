<?php

namespace App\Http\Requests\Course;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
	
	/**
	 * Prepare the data for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'plain_description' => filter_var($this->description, FILTER_SANITIZE_STRING),
		
		]);
	}
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:1536',
            'name_en' => 'required|max:100',
            'name_ar' => 'nullable|regex:/\p{Arabic}/u|max:100',
            'description' => 'required',
            'plain_description' => 'max:200',
            'cost' => 'required|numeric|min:0|max:99999',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:Offline,Online',
            'branch_id' => [
                'bail',
                //'required_if:type,Offline',
                Rule::requiredIf(function () {
                    return auth()->user()->isInstitute();
                }),
                Rule::exists('branches', 'id')->where('user_id', auth()->id()) 
            ],
        ];
    }
	
	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		
		return [
			'plain_description' => 'description',
		];
	}
}
