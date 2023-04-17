<?php

namespace App\Http\Requests\Api\V1\CandidateDocuments;

use Illuminate\Foundation\Http\FormRequest;

class CreateCandidateDocumentRequest extends FormRequest
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
            'ocr_api_id' => [ 'nullable', 'string', 'max:255' ],
            'document' => [ 'required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,svg' ],

        ];
    }
}
