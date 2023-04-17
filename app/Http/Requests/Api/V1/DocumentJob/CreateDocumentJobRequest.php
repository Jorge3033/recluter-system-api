<?php

namespace App\Http\Requests\Api\V1\DocumentJob;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentJobRequest extends FormRequest
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

    // TODO: Validar que los registros insertados no sean duplicados los id vienen en la url

    //Todo Crear una regla para validar que el id de la vacante exista en la tabla de vacantes
        // Usar el comando php artisan make:rule DocumentJobExistsRule para su validacion

}
