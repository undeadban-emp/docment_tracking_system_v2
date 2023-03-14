<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceUpdateRequest extends FormRequest
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
            'service_process_id'        =>      ['required', 'unique:services,service_process_id,' . $this->service->id],
            'name'                      =>      ['required', 'string', 'max:255', 'unique:services,name,' . $this->service->id],
            'description'               =>      ['required', 'string', 'max:255'],
            'office'                    =>      ['required'],
        ];
    }
}
