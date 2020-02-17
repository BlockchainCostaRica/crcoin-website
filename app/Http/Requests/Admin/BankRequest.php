<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_holder_name' => 'required|max:255',
            'account_holder_address' => 'required|max:255',
            'bank_name' => 'required|max:255',
            'bank_address' => 'required|max:255',
            'iban' => ['required','max:255', Rule::unique('banks')->ignore($this->edit_id,'id')],
            'swift_code' => 'required|max:255',
            'country' => 'required|max:255',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'account_holder_name.required' => __('Account holder name can not empty.'),
            'account_holder_address.required' => __('Account holder address can not empty.'),
            'bank_name.required' => __('Bank name can not empty.'),
            'bank_address.required' => __('Bank address can not empty.'),
            'iban.required' => __('IBAN can not empty.'),
            'swift_code.required' => __('Swift code can not empty.'),
            'country.required' => __('Country can not empty.'),
        ];
    }
}
