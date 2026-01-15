<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
{
    $userId = $this->route('user')?->id;

    $passwordRule = $this->isMethod('post')
        ? ['required', 'string', 'min:8', 'confirmed']
        : ['nullable', 'string', 'min:8', 'confirmed'];

    return [
        'name' => ['required', 'string', 'max:255'],
        'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
        'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],

        // ✅ ini yang bikin No HP bisa kesimpen
        'phone' => ['nullable', 'string', 'max:25'],

        'address' => ['nullable', 'string', 'max:255'],
        'password' => $passwordRule,
        'role' => ['required', Rule::in(['admin', 'pengurus', 'ustadz', 'jamaah'])],
    ];
}

}
