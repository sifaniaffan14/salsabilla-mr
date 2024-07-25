<?php

namespace App\Http\Requests\FooterContent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterContentRequest extends FormRequest
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
            'FooterContentTermAndCondition' => ["string", "max:150"],
            'FooterContentPrivacyPolicy' => ["string","max:150"],
            'FooterContentFAQ' => ["string", "max:50"],
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
