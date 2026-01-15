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
    'url_file' => ['required', 'url', 'max:2000'],
    'gdrive_url' => ['nullable', 'url', 'max:2000'], // ✅ tambah ini
    'tipe' => ['required', Rule::in(['image', 'video'])],
    'kategori' => ['required', Rule::in(['idarah', 'imarah', 'riayah'])],
    'seksi' => ['nullable', 'string', 'max:255'],
];
$table->text('gdrive_url')->nullable()->after('url_file');

    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->filled('url_file')) {
                $validator->errors()->add('url_file', 'Link wajib diisi.');
                return;
            }

            $url = (string) $this->input('url_file');

            // Validasi tambahan ringan sesuai tipe
            $tipe = (string) $this->input('tipe');

            if ($tipe === 'image') {
                // boleh drive / direct image
                // kalau bukan drive, minimal harus mengandung ekstensi image umum (opsional ketat)
                // NOTE: kalau kamu sering pakai link tanpa ekstensi, hapus blok ini.
                $isDrive = str_contains($url, 'drive.google.com');
                $looksImage = preg_match('/\.(jpg|jpeg|png|webp|gif)(\?.*)?$/i', $url);

                if (! $isDrive && ! $looksImage) {
                    $validator->errors()->add('url_file', 'Untuk tipe image, gunakan link Google Drive atau link gambar (jpg/png/webp/gif).');
                }
            }

            if ($tipe === 'video') {
                $isYoutube = str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be');
                $looksMp4  = preg_match('/\.(mp4|webm)(\?.*)?$/i', $url);

                if (! $isYoutube && ! $looksMp4 && ! str_contains($url, 'drive.google.com')) {
                    $validator->errors()->add('url_file', 'Untuk tipe video, gunakan link YouTube, link mp4/webm, atau link Google Drive.');
                }
            }
        });
    }
}
