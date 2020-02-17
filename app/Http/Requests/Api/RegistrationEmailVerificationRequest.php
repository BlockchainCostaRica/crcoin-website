<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class RegistrationEmailVerificationRequest extends FormRequest
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
        $rules = [
            'email'=>'required|email|max:256|exists:users,email'
            ,'vf_code'=>'required|max:6|exists:user_verification_codes,code'
        ];
        return $rules;
    }
    public function messages()
    {
        return [
            'email.required' => __('Email can\'t be empty'),
            'vf_code.required' => __('Code can\'t be empty'),
            'vf_code.exists' => __('Code is invalid'),
        ]; // TODO: Change the autogenerated stub
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
