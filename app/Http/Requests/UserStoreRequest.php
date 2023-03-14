<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'position' => ['required'],
            'office' => ['required'],
            'phone_number' => ['required', 'unique:users,phone_number', new PhoneNumber],
            'username' => ['required', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accountRole' => ['required'],
        ];
    }
}
