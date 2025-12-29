<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransaksiDonasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'donasi_id' => ['required', 'exists:donasis,id'],
            'jumlah' => ['required', 'integer', 'min:10000'],
            'status_pembayaran' => ['required', Rule::in(['pending', 'berhasil', 'gagal'])],
        ];
    }
}
