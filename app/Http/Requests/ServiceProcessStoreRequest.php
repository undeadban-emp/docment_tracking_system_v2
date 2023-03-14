<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProcessStoreRequest extends FormRequest
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
            'office'                           =>      ['required', 'string', 'max:255'],
            'responsible'                      =>      ['required', 'string', 'max:255'],
            'responsible_user'                 =>      ['required', 'string', 'max:255'],
            // 'action'                           =>      ['required', 'string', 'max:255'],
            // 'description'                      =>      ['required'],
        ];
    }
}
