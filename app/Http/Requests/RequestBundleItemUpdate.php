<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RequestBundleItemUpdate extends FormRequest
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
            '*.id' => 'required|exists:bundle_items,id',
            '*.bundle_id' => 'required|exists:bundles,id',
            '*.menu_id' => 'required|exists:menus,id',
            '*.qty' => 'required|integer',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json(['Validation_Error' => $validator->errors()], 422);
        throw new ValidationException($validator, $response);
    }
}
