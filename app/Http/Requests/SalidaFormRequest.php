<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SalidaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            // Validaciones para la tabla Salidas
            'n_hoja_ruta' => [
                'required',
                'string',
                'max:255',
                Rule::unique('salidas')
                    ->where('id_unidad', $this->input('id_unidad'))
                    ->ignore($this->route('salidas.edit')) // Ignora el ID si se está editando
            ],
            'n_pedido' => [
                'required',
                'string',
                'max:255',
                Rule::unique('salidas')
                    ->where('id_unidad', $this->input('id_unidad'))
                    ->ignore($this->route('salidas.edit')) // Ignora el ID si se está editando
            ],
            'id_unidad' => ['required', 'exists:unidades,id_unidad'],

        ];
    }
    public function messages()
    {
        return [
            // Mensajes personalizados para Salidas
            'n_hoja_ruta.required' => 'El número de hoja de ruta es obligatorio.',
            'n_hoja_ruta.string' => 'El número de hoja de ruta debe ser una cadena de texto.',
            'n_hoja_ruta.max' => 'El número de hoja de ruta no puede tener más de 255 caracteres.',
            'n_hoja_ruta.unique' => 'El número de hoja de ruta ya existe para esta unidad.',
            'n_pedido.required' => 'El número de pedido es obligatorio.',
            'n_pedido.string' => 'El número de pedido debe ser una cadena de texto.',
            'n_pedido.max' => 'El número de pedido no puede tener más de 255 caracteres.',
            'n_pedido.unique' => 'El número de pedido ya existe para esta unidad.',
            'id_unidad.required' => 'La unidad es obligatoria.',
            'id_unidad.exists' => 'La unidad seleccionada no es válida.',
        ];
    }
}
