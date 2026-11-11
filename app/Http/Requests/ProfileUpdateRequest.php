<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash:ascii',
                Rule::unique(User::class, 'username')->ignore($this->user()->id),
            ],
            // Email opsional (boleh kosong), tapi kalau diisi harus email asli.
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email:rfc,dns',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.unique' => 'Username ini sudah dipakai akun lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip (-) dan underscore (_), tanpa spasi dan tanpa @.',
            'email.email' => 'Email tidak valid atau domainnya tidak ditemukan. Pakai email asli atau kosongkan saja.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
        ];
    }
}
