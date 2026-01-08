<?php

namespace App\Http\Requests;

use App\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLayoutRequest extends FormRequest
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
            'layout_category_id' => [
                'required',
                'integer',
                Rule::exists('layout_categories', 'id')->where('organization_id', $organizationId),
            ],
            'payload' => ['required', 'json'],
            'screenshot' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
