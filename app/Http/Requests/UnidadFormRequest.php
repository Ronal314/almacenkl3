<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:50',
            'direccion' => 'max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre de unidad es obligatorio.',
            'nombre.string' => 'El nombre de unidad debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de unidad no puede tener más de 50 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 100 caracteres.',
        ];
    }
}
