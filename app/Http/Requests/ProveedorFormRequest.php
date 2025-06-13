<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorFormRequest extends FormRequest
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
        $id_proveedor = $this->route('proveedore') ?? 'NULL';
        return [
            'razon_social' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:255|unique:proveedores,nit,' .  $id_proveedor . ',id_proveedor',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'razon_social.required' => 'El campo razón social es obligatorio.',
            'razon_social.string' => 'La razón social debe ser una cadena de texto.',
            'razon_social.max' => 'La razón social no puede tener más de 255 caracteres.',
            'nombre.required' => 'El campo nombre de proveedor es obligatorio.',
            'nombre.string' => 'El nombre nombre de proveedor debe ser una cadena de texto.',
            'nombre.max' => 'El nombre nombre de proveedor no puede tener más de 255 caracteres.',
            'nit.required' => 'El campo NIT es obligatorio.',
            'nit.string' => 'El NIT debe ser una cadena de texto.',
            'nit.max' => 'El NIT no puede tener más de 255 caracteres.',
            'nit.unique' => 'El NIT ya existe en la base de datos.',
            'direccion.required' => 'El campo dirección es obligatorio.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 255 caracteres.',
        ];
    }
}
