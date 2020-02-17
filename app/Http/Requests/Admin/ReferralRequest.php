<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReferralRequest extends FormRequest
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
            'max_affiliation_level' => 'required|integer|min:1|max:10'
        ];
        if($this->fees_level1) {
            $rules['fees_level1'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level1) {
            $rules['fees_level1'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level2) {
            $rules['fees_level2'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level3) {
            $rules['fees_level3'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level4) {
            $rules['fees_level5'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level6) {
            $rules['fees_level6'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level7) {
            $rules['fees_level7'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level8) {
            $rules['fees_level8'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level9) {
            $rules['fees_level9'] = 'numeric|min:0|max:100';
        }
        if($this->fees_level10) {
            $rules['fees_level10'] = 'numeric|min:0|max:100';
        }

        return $rules;
    }
}
