<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:1024'],
            'body' => ['required', 'string', 'max:1024'],
        ];
    }
    /**
     *  validate param
     */
    public function validationData()
    {
        $validator = Validator::make($this->route()->parameters(), [
            'post' => [
                'required',
                'numeric',
                'integer',
            ],
        ]);
        info($this->route()->parameters());
        Log::info($this->route()->parameters());
        $validator->validate();

        return $this->all();
    }
}
