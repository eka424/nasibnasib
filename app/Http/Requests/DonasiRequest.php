<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'target_dana' => ['required', 'integer', 'min:1000000'],
            'tanggal_selesai' => ['nullable', 'date', 'after:today'],
        ];
    }
}
