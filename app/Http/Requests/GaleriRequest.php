<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class GaleriRequest extends FormRequest
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
            'deskripsi' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:8192'],
            'url_file' => ['nullable', 'string', 'max:255'],
            'tipe' => ['required', 'in:image,video'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->hasFile('attachment') && ! $this->filled('url_file')) {
                $validator->errors()->add('url_file', 'Unggah file atau isi URL galeri.');
            }
        });
    }
}
}
