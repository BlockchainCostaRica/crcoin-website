<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PasswordRequest extends FormRequest
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
            'old_password' => 'required',
            'password' => 'required|min:8|strong_pass|confirmed',
            'password_confirmation' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        $messages=[
            'password_confirmation.confirmed' => 'The password confirmation does not match.',
            'password.required' => __('Password field can not be empty'),
            'old_password.required' => __('Old Password field can not be empty'),
            'password_confirmation.required' => __('Password confirmed field can not be empty'),
            'password.min' => __('Password length must be minimum 8 characters.'),
            'password.strong_pass' => __('Password must be consist of one uppercase, one lowercase and one number!')
        ];

        return $messages;
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
            $json = ['success'=>false,
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
