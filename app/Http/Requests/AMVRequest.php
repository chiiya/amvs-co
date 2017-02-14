<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AMVRequest extends FormRequest
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
            'title' => 'required',
            'music' => 'required',
            'animes' => 'required',
            'poster' => 'image|mimes:jpeg,png,jpg|max:500',
            'bg' => 'image|mimes:jpeg,png,jpg|max:500'
        ];
    }
}
