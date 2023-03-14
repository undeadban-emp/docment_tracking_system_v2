<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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

        //  'unique:users,phone_number,' . $this->user->id
        return [
            'firstname' => ['sometimes', 'required', 'string', 'max:255'],
            'middlename' => ['sometimes', 'nullable', 'string', 'max:255'],
            'lastname' => ['sometimes', 'required', 'string', 'max:255'],
            'suffix' => ['sometimes', 'nullable', 'string', 'max:255'],
            'position' => ['sometimes', 'required'],
            'office' => ['sometimes', 'required'],
            'phone_number' => ['sometimes', 'required', new PhoneNumber,],
            // 'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'username' => ['required', Rule::unique('users')->ignore($this->user->id)],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
