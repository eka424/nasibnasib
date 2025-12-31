<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KidsContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // nanti bisa ganti policy/role
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:video,dongeng,kata'],
            'title' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'quote_text' => ['nullable', 'string', 'max:1000'],

            // youtube (boleh url atau id)
            'youtube_input' => ['nullable', 'string', 'max:255'],

            // pdf
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5MB

            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Tipe konten harus video / dongeng / kata.',
            'pdf_file.mimes' => 'File harus PDF.',
        ];
    }
}
