<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class GetSaleFromSellerRequest extends FormRequest
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
            'id' => 'exists:sales,id',
            'initial_date' => 'date_format:Y-m-d H:i:s',
            'final_date' => 'required_if:initial_date,!=,null|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'O ID da venda informado não existe.',
            'initial_date.date_format' => 'A data inicial deve estar no formato Y-m-d H:i:s.',
            'final_date.required_if' => 'A data final é obrigatória quando a data inicial está presente.',
            'final_date.date_format' => 'A data final deve estar no formato Y-m-d H:i:s.',
        ];
    }
}
