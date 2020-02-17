<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileUpdateRequest extends FormRequest
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
            'first_name'=>'required|max:50',
            'last_name'=>'required|max:50',
            'phone'=>'required|numeric',
        ];
        if (!empty($this->photo)) {
            $rules['photo'] = 'mimes:jpeg,jpg,JPG,png,PNG,gif|max:3048|dimensions:max_width=500,max_height=500';
        }
        return $rules;
    }

    public function messages()
    {
        $messages=[
            'first_name.required'=>__('Name field can\'t be empty.'),
            'phone.required'=>__('Phone field can\'t be empty.'),
            'country.required'=>__('Country field can\'t be empty.'),
            'phone.numeric'=>__('Phone number must be numeric'),
            'image.image'=>__('Invalid image format.'),
            'image.mimes'=>__('Image formate must be jpg,jpeg or png.'),
            'image.max'=>__('Last Name can\'t be more than 3 Mb.')
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
