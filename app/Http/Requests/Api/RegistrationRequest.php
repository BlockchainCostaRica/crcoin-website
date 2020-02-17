<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class RegistrationRequest extends FormRequest
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
        $rules=[
            'first_name'=>'required|max:60',
            'last_name'=>'required|max:60',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|strong_pass',
            'password_confirmation' => 'required|min:8|same:password',
            'phone' => 'required|numeric',
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'first_name.required' => __('First name field can not be empty.'),
            'last_name.required' => __('Last name field can not be empty.'),
            'phone.required' => __('Phone field can not be empty.'),
            'password.required' => __('Password field can not be empty'),
            'password.min' => __('Password length must be minimum 8 characters.'),
            'password_confirmation.same' => __('The confirm password does not match at password.'),
            'password.strong_pass' => __('Password must be consist of one uppercase, one lowercase and one number'),
            'email.required' => __('Email field can not be empty'),
            'email.unique' => __('Email address already exists'),
            'email.email' => __('Invalid email address')
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
