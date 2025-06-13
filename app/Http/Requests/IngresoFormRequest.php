<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngresoFormRequest extends FormRequest
{

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
            // Validaciones para la tabla Ingresos
            'n_factura' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingresos')
                    ->where('id_proveedor', $this->input('id_proveedor'))
                    ->ignore($this->route('ingresos.edit')) // Ignora el ID si se está editando
            ],
            'n_pedido' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingresos')
                    ->where('id_proveedor', $this->input('id_proveedor'))
                    ->ignore($this->route('ingresos.edit')) // Ignora el ID si se está editando
            ],
            'id_proveedor' => ['required', 'exists:proveedores,id_proveedor'],
        ];
    }


    public function messages()
    {
        return [
            // Mensajes personalizados para Ingresos
            'n_factura.required' => 'El número de factura es obligatorio.',
            'n_factura.string' => 'El número de factura debe ser una cadena de texto.',
            'n_factura.max' => 'El número de factura no puede tener más de 255 caracteres.',
            'n_factura.unique' => 'El número de factura ya existe para este proveedor.',
            'id_proveedor.required' => 'El proveedor es obligatorio.',
            'id_proveedor.exists' => 'El proveedor seleccionado no es válido.',
            'n_pedido.required' => 'El número de pedido es obligatorio.',
            'n_pedido.string' => 'El número de pedido debe ser una cadena de texto.',
            'n_pedido.max' => 'El número de pedido no puede tener más de 255 caracteres.',
            'n_pedido.unique' => 'El número de pedido ya existe para esta unidad.',
        ];
    }
}
