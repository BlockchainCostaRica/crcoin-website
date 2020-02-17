<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BuyCoinRequest extends FormRequest
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
        $check = [
            'payment_type' => ['required'],
//            'btc_address' => ['required'],
//            'total_coin_price_in_dollar' => ['required'],
            'coin' => 'required|min:.001|max:9999999',
        ];
        if ($this->payment_type == BTC){
//            $check['btc_address'] =  ['required'];
        }
        if ($this->payment_type == CARD){
            $check['payment_method_nonce'] =  ['required'];
        }
        if ($this->payment_type == BANK_DEPOSIT){

            $check['sleep'] =  ['required','mimes:jpeg,jpg,png,gif|required|max:10000'];
            $check['bank_id'] =  'required|integer|exists:banks,id';
        }

        return $check;
    }
    public function messages()
    {
        $data['payment_type.required'] = __('Select your payment method');
        $data['bank_id.required'] = __('Must be select a bank');
        $data['payment_method_nonce.required'] = __('Payment method nonce is required');


        return $data;
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->header('accept') == "application/json") {
            $errors = [];
            if ($validator->fails()) {
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
            }
            $json = [
                'success'=>false,
                'message' => $errors[0],
            ];
            $response = new JsonResponse($json, 200);

            throw (new ValidationException($validator, $response))->errorBag($this->errorBag)->redirectTo($this->getRedirectUrl());
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }

    }
}
