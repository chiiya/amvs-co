<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch($this->method()) 
        {
            case 'POST':
                return [
                    'name' => 'required|unique:users|alpha_dash|max:30',
                    'email' => 'required|unique:users|email',
                    'password' => 'required'
                ];
            case 'PUT':
                /**
                 * When updating user, don't validate unique email on existing user model
                 */
                return [
                    'avatar' => 'image|mimes:jpeg,png,jpg|max:150',
                    'email' => 'required|email|unique:users,email,'.$this->route('id'),
                ];
        }
        
    }
}
