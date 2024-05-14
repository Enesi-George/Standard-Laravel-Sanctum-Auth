<?php

namespace App\Http\Requests;

use App\DataTransferObject\StoreUserDTO;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors(),
        ], 400));
    }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => "bail|required|string|max:255",
            "first_name" => "required|string|max:255|regex:/^[A-Z][a-z]*$/",
            "last_name" => "required|string|max:255|regex:/^[A-Z][a-z]*$/",
            "email" => "required|email|max:255|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
            "phone_number" => "required|string|max:255|regex:/^\+?\d+$/",
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#€.\\-])[A-Za-z\d@$!%*?&#€.\\-]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            "first_name.regex" => "The :attribute must start with an uppercase letter followed by lowercase letters.",
            "last_name.regex" => "The :attribute must start with an uppercase letter followed by lowercase letters.",
            'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, one digit, one special character and min of 8 characters',
        ];
    }

    public function toDTO(): StoreUserDTO
    {
        return new StoreUserDTO(
            username: $this->username,
            first_name: $this->first_name,
            last_name: $this->last_name,
            email: $this->email,
            phone_number: $this->phone_number,
            password: $this->password,

        );
    }
}
