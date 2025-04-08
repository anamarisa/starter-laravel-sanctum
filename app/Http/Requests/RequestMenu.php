<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RequestMenu extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'nullable|integer',
            'desc' => 'nullable|string|max:255',
            'priority' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'qty' => 'required|integer',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json(['Validation_Error' => $validator->errors()], 422);
        throw new ValidationException($validator, $response);
    }
}
