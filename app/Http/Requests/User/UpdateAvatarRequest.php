<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateAvatarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
        ];
    }

    public function validationData()
    {
        $validator = Validator::make($this->route()->parameters(), [
            'userId' => [
                'bail', 
                'required',
                'numeric',
                'integer',
            ],
        ]);
        $validator->validate();
        
        return $this->all();
    }
   
}
