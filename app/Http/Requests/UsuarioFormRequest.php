<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioFormRequest extends FormRequest
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
        $id_usuario = $this->route('usuario') ?? 'NULL';
        return [
            'name' => 'required|string|max:255',
            'ci' => 'required|string|max:255|unique:users,ci,' . $id_usuario . ',id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.string' => 'El CI debe ser una cadena de texto.',
            'ci.max' => 'El CI no puede tener más de 255 caracteres.',
            'ci.unique' => 'El CI ya existe en la base de datos.',
        ];
    }
}
