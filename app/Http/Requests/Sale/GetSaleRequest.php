<?php

namespace App\Http\Requests\Sale;

use App\Models\Unity;
use App\Models\UserDirectorship;
use App\Models\UserUnities;
use Illuminate\Foundation\Http\FormRequest;

class GetSaleRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'initial_date' => 'date_format:Y-m-d H:i:s',
            'final_date' => 'required_if:initial_date,!=,null|date_format:Y-m-d H:i:s',
            'seller_id' => 'integer|exists:users,id',
            'unity_id' => 'integer|exists:unities,id',
            'directorship_id' => 'integer|exists:directorships,id',
        ];
    }

    public function messages()
    {
        return [
            'initial_date.date_format' => 'A data inicial deve estar no formato Y-m-d H:i:s.',
            'final_date.required_if' => 'A data final é obrigatória quando a data inicial está presente.',
            'final_date.date_format' => 'A data final deve estar no formato Y-m-d H:i:s.',
            'seller_id.integer' => 'O ID do vendedor deve ser um número inteiro.',
            'seller_id.exists' => 'O ID do vendedor deve existir na tabela de usuários.',
            'unity_id.integer' => 'O ID da unidade deve ser um número inteiro.',
            'unity_id.exists' => 'O ID da unidade deve existir na tabela de unidades.',
            'directorship_id.integer' => 'O ID da diretoria deve ser um número inteiro.',
            'directorship_id.exists' => 'O ID da diretoria deve existir na tabela de diretorias.',
        ];
    }

    public function withValidator($validator): void
    {
        $user = auth()->user();

        $validator->after(function ($validator) use ($user) {
            if ($this->filled('directorship_id') and ! $user->hasRole('general-director')) {
                if (! $this->verifyIfUserCanFilterDirectorship()) {
                    $validator->errors()->add('directorship_id', 'Você não tem permissão para filtrar por esta diretoria.');
                }

                if (! $this->verifyUserHasDirectorship()) {
                    $validator->errors()->add('directorship_id', 'Você não possui esta diretoria associada.');
                }
            }

            if ($this->filled('unity_id') and ! $user->hasRole('general-director')) {
                if (! $this->verifyIfUserCanFilterUnity()) {
                    $validator->errors()->add('unity_id', 'Você não tem permissão para filtrar por esta unidade.');
                }

                if (! $this->verifyUserHasUnity()) {
                    $validator->errors()->add('unity_id', 'Você não possui esta unidade associada.');
                }
            }

            if ($this->filled('seller_id') and ! $user->hasRole('general-director')) {
                if (! $this->canFilterSeller()) {
                    $validator->errors()->add('seller_id', 'Você não tem permissão para filtrar por este vendedor.');
                }
            }
        });
    }

    public function verifyIfUserCanFilterDirectorship(): bool
    {
        $user = auth()->user();

        return $user->hasAnyRole(['director', 'general-director']);
    }

    public function verifyUserHasDirectorship(): bool
    {
        $user = auth()->user();
        $directorshipId = $this->input('directorship_id');

        return UserDirectorship::where('user_id', $user->id)
            ->where('directorship_id', $directorshipId)
            ->exists();
    }

    public function verifyIfUserCanFilterUnity(): bool
    {
        $user = auth()->user();

        return $user->hasAnyRole(['manager', 'director', 'general-director']);
    }

    public function verifyUserHasUnity(): bool
    {
        $user = auth()->user();
        $unityId = $this->input('unity_id');

        if ($user->hasRole('director')) {
            $userDirectorships = UserDirectorship::where('user_id', $user->id)
                ->pluck('directorship_id');

            return Unity::where('directorship_id', $userDirectorships)
                ->where('id', $unityId)
                ->exists();
        }

        return UserUnities::where('user_id', $user->id)
            ->where('unity_id', $unityId)
            ->exists();
    }

    public function canFilterSeller(): bool
    {
        $user = auth()->user();
        $sellerId = $this->input('seller_id');

        if ($sellerId) {
            if ($user->hasRole('director')) {
                return $this->isSellerInUserDirectorshipsUnits($sellerId);
            }

            if ($user->hasRole('manager')) {
                return $this->isSellerInUserUnities($sellerId);
            }
        }

        return false;
    }

    public function isSellerInUserDirectorshipsUnits($sellerId): bool
    {
        $user = auth()->user();

        $userDirectorships = UserDirectorship::where('user_id', $user->id)
            ->pluck('directorship_id');

        $unities = Unity::whereIn('directorship_id', $userDirectorships)
            ->pluck('id');

        return UserUnities::whereIn('unity_id', $unities)
            ->where('user_id', $sellerId)
            ->exists();
    }

    public function isSellerInUserUnities($sellerId): bool
    {
        $user = auth()->user();

        $userUnities = UserUnities::where('user_id', $user->id)
            ->pluck('unity_id');

        return UserUnities::where('user_id', $sellerId)
            ->whereIn('unity_id', $userUnities)
            ->exists();
    }
}
