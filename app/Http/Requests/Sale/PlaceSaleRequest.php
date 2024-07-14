<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class PlaceSaleRequest extends FormRequest
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
            'value' => 'required|numeric',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O campo valor deve ser um número.',
            'lat.required' => 'O campo latitude é obrigatório.',
            'lat.numeric' => 'O campo latitude deve ser um número.',
            'long.required' => 'O campo longitude é obrigatório.',
            'long.numeric' => 'O campo longitude deve ser um número.',
        ];
    }
}
