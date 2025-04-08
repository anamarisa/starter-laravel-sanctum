<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateCoupon extends FormRequest
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
        $couponId = $this->route('id');
        return [
            "name" => "required|string|max:255",
            "desc" => "string",
            "priority" => "required|integer",
            "allow_multiple" => "required|in:yes,no",
            "start_date" => "required|date",
            "end_date" => "required|date",
            "coupon_type" => "required|in:nominal,percentage",
            "voucher_code" => ["required", "string", Rule::unique('coupons')->ignore($couponId)],
            "discount" => "required|numeric",
            "enable" => "required|in:yes,no",
        ];
    }


    public function failedValidation(Validator $validator)
    {
        $response = response()->json(['Validation_Error' => $validator->errors()], 422);
        throw new ValidationException($validator, $response);
    }
}
