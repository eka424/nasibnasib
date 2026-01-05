<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArtikelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array
{
    $artikelId = $this->route('artikel')?->id;

    return [
        'title' => ['required', 'string', 'max:255'],
        'slug' => ['required', 'string', 'max:255', Rule::unique('artikels', 'slug')->ignore($artikelId)],
        'content' => ['required', 'string'],

        // upload file (optional)
        'thumbnail' => ['nullable', 'image', 'max:4096'],

        // link drive (optional)
        'thumbnail_url' => ['nullable', 'string', 'max:2000'],

        'status' => ['required', 'in:draft,published'],
    ];
}

}
