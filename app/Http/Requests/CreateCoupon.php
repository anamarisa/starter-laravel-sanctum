<?php

namespace App\Http\Requests\DeliveryStatus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Helpers\ResponseHelpers;

class CreateCoupon extends FormRequest
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
            'status' => 'required|string',
            'status_en' => 'required|string',
            'status_kr' => 'required|string',
            'desc' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // Customize the error response format
        $response = response()->json(['Validation_Error' => $validator->errors()], 422);
        throw new ValidationException($validator, $response);
    }
}
