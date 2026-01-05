<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasjidProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // policy kamu udah di controller
    }

    protected function prepareForValidation(): void
    {
        // Ambil misi_text -> jadikan array untuk disimpan ke kolom "misi"
        $misiText = $this->input('misi_text');

        if ($misiText !== null) {
            $lines = preg_split("/\r\n|\r|\n/", (string) $misiText);
            $lines = array_values(array_filter(array_map('trim', $lines))); // buang kosong

            $this->merge([
                'misi' => $lines,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama' => ['required','string','max:255'],
            'kategori' => ['nullable','string','max:255'],
            'tipe' => ['nullable','string','max:255'],
            'no_id_masjid' => ['nullable','string','max:255'],
            'tahun_berdiri' => ['nullable','string','max:20'],

            'alamat' => ['nullable','string'],
            'kelurahan' => ['nullable','string','max:255'],
            'kecamatan' => ['nullable','string','max:255'],
            'kabupaten' => ['nullable','string','max:255'],
            'provinsi' => ['nullable','string','max:255'],
            'kode_pos' => ['nullable','string','max:20'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],

            'email' => ['nullable','email','max:255'],
            'website' => ['nullable','string','max:255'],
            'url_website' => ['nullable','string','max:500'],
            'url_peta' => ['nullable','string','max:500'],
            'url_petunjuk' => ['nullable','string','max:500'],

            'sejarah' => ['nullable','string'],
            'visi' => ['nullable','string'],
            'misi' => ['nullable','array'],      // hasil dari prepareForValidation
            'misi_text' => ['nullable','string'],// biar inputnya ga bikin error

            'jumlah_pengurus' => ['nullable','integer'],
            'jumlah_imam' => ['nullable','integer'],
            'jumlah_khatib' => ['nullable','integer'],
            'jumlah_muazin' => ['nullable','integer'],
            'jumlah_remaja' => ['nullable','integer'],

            'luas_tanah_m2' => ['nullable','integer'],
            'status_tanah' => ['nullable','string','max:255'],
            'luas_bangunan_m2' => ['nullable','integer'],
            'daya_tampung' => ['nullable','string','max:255'],
        ];
    }
}
