<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'cc_holder_name' => 'string|required',
            'cc_no' => 'string|required|max:16|min:16',
            'expiry_month' => 'required|max:12|min:1',
            'expiry_year' => 'required',
            'cvv' => 'nullable',
            'total' => 'required',
            'installments_number' => 'required',
            'amount' => 'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'name' => 'required',
            'tckn' => 'required|max:11|min:11'
        ];
    }
}
