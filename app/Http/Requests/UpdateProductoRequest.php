<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;


class UpdateProductoRequest extends FormRequest
{

//si esta autorizado para hacer eso osea admin 
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function after(): array
{
    return [
        function (Validator $validator) {
            if ($validator->errors()->hasAny(['cantidad', 'estado'])) {
                return;
            }

            $cantidad = (int) $this->cantidad;

            if ($cantidad === 0 && $this->estado === 'Disponible') {
                $validator->errors()->add(
                    'estado',
                    'Un producto con 0 existencias no puede marcarse como Disponible.'
                );
            }

            if ($cantidad > 0 && $this->estado === 'Agotado') {
                $validator->errors()->add(
                    'estado',
                    'Un producto con existencias mayores a 0 no puede marcarse como Agotado.'
                );
            }
        }
    ];
}


    public function rules(): array
    {
        $productoId = $this->route('producto')?->id ?? $this->route('producto');

        return [
            'producto'     => ['required', 'string', 'max:255', Rule::unique('productos', 'producto')->ignore($productoId)],
            'descripcion'  => ['required', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'precio'       => ['required', 'numeric', 'gt:0'],
            'cantidad'     => ['required', 'integer', 'min:0'],
            'estado'       => ['required', Rule::in(['Disponible', 'Agotado'])],
        ];
    }

    public function messages(): array
    {
        return [
            'producto.required'     => 'El nombre del producto es obligatorio.',
            'producto.unique'       => 'El nombre del producto que quiere registrar ya existe.',
            'descripcion.required'  => 'La descripción del producto es obligatoria.',
            'categoria_id.required' => 'El producto debe de tener una categoría.',
            'categoria_id.exists'   => 'La categoría seleccionada no es válida.',
            'precio.required'       => 'El producto requiere de un precio.',
            'precio.numeric'        => 'El precio debe de ser un valor numérico.',
            'precio.gt'             => 'El precio debe de ser mayor a cero.',
            'cantidad.required'     => 'La cantidad de productos es obligatoria.',
            'cantidad.integer'      => 'La cantidad de productos debe de ser entera.',
            'cantidad.min'          => 'Las cantidades de un producto no pueden ser negativas.',
            'estado.required'       => 'Debes seleccionar un estado.',
            'estado.in'             => 'El estado seleccionado no es permitido.',
        ];
    }
}