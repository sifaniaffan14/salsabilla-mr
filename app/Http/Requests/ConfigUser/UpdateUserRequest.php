<?php

namespace App\Http\Requests\ConfigUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ["string", "max:50", "unique:users,UserName"],
            'email' => ["string","max:50", "unique:users,UserEmail"],
        ];
    }

    // public function prepareForValidation()
    // {
    //     if (auth()->check()) {
    //         $this->merge([
    //             'CtCreatedBy' => Auth::id(),
    //             'CtUpdatedBy' => Auth::id(),
    //             'CcId' => Auth::user()->token()->country_id,
    //         ]);
    //     }
    // }
}
