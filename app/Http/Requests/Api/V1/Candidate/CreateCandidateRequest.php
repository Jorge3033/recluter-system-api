<?php

namespace App\Http\Requests\Api\V1\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class CreateCandidateRequest extends FormRequest
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
            'candidate_key' => [ 'required','string', 'max:255', 'unique:ATT_candidates,candidate_key' ],
            'name' => [ 'required', 'string', 'max:255' ],
            'first_last_name' => [ 'required', 'string', 'max:255' ],
            'second_last_name' => [ 'required', 'string', 'max:255' ],
            'is_national' => [ 'required', 'boolean' ],
            'street' => [ 'nullable', 'string', 'max:255' ],
            'number_in' => [ 'nullable', 'string', 'max:255' ],
            'number_out' => [ 'nullable', 'string', 'max:255' ],
            'colony' => [ 'nullable', 'string', 'max:255' ],
            'city' => [ 'nullable', 'string', 'max:255' ],
            'state' => [ 'nullable', 'string', 'max:255' ],
            'country' => [ 'nullable', 'string', 'max:255' ],
            'postal_code' => [ 'nullable', 'string', 'max:255' ],
            'phone' => [ 'required', 'string', 'max:255' ],
            'email' => [ 'required', 'string', 'max:255' ],
            'curp' => [ 'required', 'string', 'max:18' ],
            'birth_date' => [ 'required', 'date' ],
            'ATT_Vacantes_id' => [ 'required', 'integer', 'exists:ATT_Vacantes,ID' ],
            'status' => [ 'nullable', 'string', 'max:255' ],
        ];
    }
}
