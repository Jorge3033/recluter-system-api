<?php

namespace App\Http\Requests\Api\V1\Document;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateDocumentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,svg'],
            'has_ocr' => ['required', 'Regex:/^(true|false)$/'],
            'ocr_strategy' => ['nullable', 'string'],
        ];
    }
}
