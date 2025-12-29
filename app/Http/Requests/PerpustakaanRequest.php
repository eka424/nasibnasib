<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PerpustakaanRequest extends FormRequest
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
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'file_ebook' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'file_url' => ['nullable', 'url'],
            'cover_url' => ['nullable', 'url'],
            'stok_total' => ['nullable', 'integer', 'min:0'],
            'stok_tersedia' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $total = (int) ($this->input('stok_total') ?? 0);
            $available = (int) ($this->input('stok_tersedia') ?? $total);

            if ($available > $total) {
                $validator->errors()->add('stok_tersedia', 'Stok tersedia tidak boleh melebihi total eksemplar.');
            }
        });
    }
}
