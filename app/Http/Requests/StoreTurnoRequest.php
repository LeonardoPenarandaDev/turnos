<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTurnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ruta pública
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_documento' => 'required|in:CC,TI,CE,PAS',
            'numero_documento' => [
                'required',
                'string',
                'max:20',
                // Validación condicional según el tipo de documento
                Rule::when($this->tipo_documento === 'CC', [
                    'regex:/^\d{6,10}$/',
                ]),
                Rule::when($this->tipo_documento === 'TI', [
                    'regex:/^\d{10,11}$/',
                ]),
                Rule::when($this->tipo_documento === 'CE', [
                    'regex:/^[A-Z0-9]{6,15}$/',
                ]),
                Rule::when($this->tipo_documento === 'PAS', [
                    'regex:/^[A-Z0-9]{6,12}$/',
                ]),
            ],
            'nombre_completo' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[\pL\s\-\'\.]+$/u', // Solo letras, espacios, guiones y apóstrofes
            ],
            'tipo_tramite_id' => [
                'required',
                'exists:tipos_tramite,id,activo,1'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.in' => 'El tipo de documento debe ser CC, TI, CE o PAS.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.regex' => 'El formato del número de documento no es válido para el tipo seleccionado.',
            'numero_documento.max' => 'El número de documento no puede tener más de 20 caracteres.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre_completo.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre_completo.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'tipo_tramite_id.required' => 'Debe seleccionar un tipo de trámite.',
            'tipo_tramite_id.exists' => 'El tipo de trámite seleccionado no es válido o no está activo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tipo_documento' => 'tipo de documento',
            'numero_documento' => 'número de documento',
            'nombre_completo' => 'nombre completo',
            'tipo_tramite_id' => 'tipo de trámite',
        ];
    }
}
