<?php

namespace App\Http\Requests\Course;

use Illuminate\Validation\Rule;
use App\Rules\SessionsTimeConflictRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Session\SessionRequest;

class CourseRequest extends FormRequest
{
    protected $sessionRequest;

    public function __construct()
    {
        // If request is injected in the constructor, it will be automatically validated
        $this->sessionRequest = new SessionRequest();
        $this->sessionRequest->setType('array');
    }
	
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
        return array_merge([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1536',
            'name_en' => 'required|max:100',
            'name_ar' => 'nullable|regex:/\p{Arabic}/u|max:100',
            'description' => 'required',
            'plain_description' => 'max:200',
            'minimum_seats' => 'required|numeric|min:1|max:99|lt:maximum_seats',
            'maximum_seats' => 'required|numeric|min:1|max:99|gt:minimum_seats',
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
            'sessions' => [
                'required',
                'array',
                new SessionsTimeConflictRule
            ],
        ], $this->sessionRequest->rules());
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {

        return array_merge([
        	'plain_description' => 'description',
            'minimum_seats' => 'minimum seats',
            'maximum_seats' => 'maximum seats',
            'category_id' => 'category ID',
        ], $this->sessionRequest->attributes());

        return $this->sessionRequest->attributes();
    }
}
