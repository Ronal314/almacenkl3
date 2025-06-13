<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoFormRequest extends FormRequest
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
            'codigo' => 'required|string|max:255|unique:productos,codigo,' . $this->route('producto') . ',id_producto',
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required',
            'id_categoria' => 'required|exists:categorias,id_categoria',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.string' => 'El código debe ser una cadena de texto.',
            'codigo.max' => 'El código no puede tener más de 255 caracteres.',
            'codigo.unique' => 'El código ya existe en la base de datos.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
            'unidad.required' => 'Seleccione una unidad.',
            'id_categoria.required' => 'Seleccione una categoria.',
            'id_categoria.exists' => 'La categoría seleccionada no es válida.'
        ];
    }
}
