<?php

namespace App\Http\Requests\Api\V1\DocumentJob;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentJobDataRequest extends FormRequest
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
            'is_national' => ['required', 'boolean'],
            'importance' => ['required', 'string', 'in:low,medium,high'],
        ];
    }
}
