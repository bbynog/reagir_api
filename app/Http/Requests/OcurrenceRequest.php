<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OcurrenceRequest extends FormRequest
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
            'violence_type' => 'required',
            'what_to_do' => 'required',
        ];
        
    }

    public function messages()
    {
        return [
            'required' => 'você precisa obrigatoriamente preencher este campo.',
            'violence_type.required' => 'preencha com exatidão, pois tipos de violência.' 
        ];
    }
}
