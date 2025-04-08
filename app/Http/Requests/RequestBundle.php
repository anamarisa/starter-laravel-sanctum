<?php

namespace App\Http\Requests\DeliveryStatus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RequestBundle extends FormRequest
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
            'bundle_price' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'desc' => 'required|max:150',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // Customize the error response format
        $response = response()->json(['Validation_Error' => $validator->errors()], 422);
        throw new ValidationException($validator, $response);
    }
}
