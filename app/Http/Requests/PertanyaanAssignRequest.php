<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PertanyaanAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ustadz_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'ustadz'),
            ],
        ];
    }
}
