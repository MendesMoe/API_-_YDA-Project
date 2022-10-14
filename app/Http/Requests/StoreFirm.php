<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFirm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|alpha_dash',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'siret' => 'required',
            'logo' => 'mimes:jpeg,jpg,bmp,png',
        ];
    }
}
