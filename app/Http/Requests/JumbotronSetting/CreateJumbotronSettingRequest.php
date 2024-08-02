<?php

namespace App\Http\Requests\JumbotronSetting;

use Illuminate\Foundation\Http\FormRequest;

class CreateJumbotronSettingRequest extends FormRequest
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
            'JumbotronTittle' => ["required", "string", "max:50"],
            'JumbotronDescription' => ["required","string","max:150"],
            // 'JumbotronImage' => ["nullable", "string", "max:255"],
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
