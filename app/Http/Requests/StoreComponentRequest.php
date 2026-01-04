<?php

namespace App\Http\Requests;

use App\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->hasAnyRole([
            Membership::ROLE_OWNER,
            Membership::ROLE_ADMIN,
            Membership::ROLE_EDITOR,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $organizationId = $this->user()?->currentOrganizationId();

        return [
            'name' => ['required', 'string', 'max:120'],
            'component_category_id' => [
                'required',
                'integer',
                Rule::exists('component_categories', 'id')->where('organization_id', $organizationId),
            ],
            'payload' => ['required', 'json'],
            'screenshot' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
