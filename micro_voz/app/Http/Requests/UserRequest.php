<?php

namespace App\Http\Requests;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        if ($this->method() == 'POST') {
            $rules = [
                'name' => "required|string|min:1|max:20",
                'last_name' => "required|string|min:1|max:20",
                'email' => "required|email|unique:users,email",
                'phone_number' => "required|digits_between:5,15",
                'company' => "required|string|min:1|max:30",
                'type_desc' => "required_if:type_client_id,3|max:25",
                'password' => "required|string|min:1|max:30",
                'type_client_id' => "required|exists:type_clients,id",
            ];
        }elseif($this->method() == 'POST') {
            $rules = [
                'name' => "sometimes|string|min:1|max:20",
                'last_name' => "sometimes|string|min:1|max:20",
                'email' => "sometimes|email|unique:users,email",
                'phone_number' => "sometimes|digits_between:5,15",
                'company' => "sometimes|string|min:1|max:30",
                'password' => "sometimes|string|min:1|max:30",
                'type_client_id' => "sometimes|exists:type_clients,id",
                'type_desc' => "required_if:type_client_id,3|max:25",
            ];
        }

        return $rules;
    }
}
