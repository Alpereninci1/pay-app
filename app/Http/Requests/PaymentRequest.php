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
            'cc_no' => 'string|required|max:16',
            'expiry_month' => 'required|max:12|min:1',
            'expiry_year' => 'required',
            'cvv' => 'nullable',
            'total' => 'required',
            'installments_number' => 'required',
            'amount' => 'nullable', // TODO: amount bilgisini neden null geçilebilir kıldık? aynı şekilde nullable olarak tanımladığın input alanlarını gözden geçirebilir misin?
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'name' => 'nullable',
            'tckn' => 'nullable|max:11' // TODO: min değeri de eklemeliyiz
        ];
    }
}
