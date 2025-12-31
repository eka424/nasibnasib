<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GaleriRequest extends FormRequest
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

            // file upload opsional, tapi wajib salah satu: attachment atau url_file
            'attachment' => ['nullable', 'file', 'max:8192', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime'],
            'url_file' => ['nullable', 'string', 'max:255'],

            'tipe' => ['required', Rule::in(['image', 'video'])],

            // dropdown baru
            'kategori' => ['required', Rule::in(['idarah', 'imarah', 'riayah'])],
            'seksi' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->hasFile('attachment') && ! $this->filled('url_file')) {
                $validator->errors()->add('url_file', 'Unggah file atau isi URL galeri.');
            }

            // Kalau tipe video tapi upload image (atau sebaliknya) bisa kamu ketatkan di sini.
            // Untuk sekarang cukup mimetype list di atas + pilihan tipe.
        });
    }
}
