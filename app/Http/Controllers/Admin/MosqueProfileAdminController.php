<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MosqueProfile;
use Illuminate\Http\Request;

class MosqueProfileAdminController extends Controller
{
    public function edit()
    {
        $profile = MosqueProfile::firstOrFail();
        return view('admin.mosque_profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = MosqueProfile::firstOrFail();

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'no_id_masjid' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|integer|min:1000|max:3000',

            'alamat' => 'nullable|string',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:20',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',

            'email' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'url_website' => 'nullable|string|max:255',
            'url_peta' => 'nullable|string|max:255',
            'url_petunjuk' => 'nullable|string|max:255',

            'sejarah' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi_text' => 'nullable|string',

            'jumlah_pengurus' => 'nullable|integer|min:0',
            'jumlah_imam' => 'nullable|integer|min:0',
            'jumlah_khatib' => 'nullable|integer|min:0',
            'jumlah_muazin' => 'nullable|integer|min:0',
            'jumlah_remaja' => 'nullable|integer|min:0',

            'luas_tanah_m2' => 'nullable|numeric|min:0',
            'status_tanah' => 'nullable|string|max:255',
            'luas_bangunan_m2' => 'nullable|numeric|min:0',
            'daya_tampung' => 'nullable|integer|min:0',
        ]);

        // misi inputnya per-baris
        $misiLines = collect(explode("\n", (string)($data['misi_text'] ?? '')))
            ->map(fn($x) => trim($x))
            ->filter()
            ->values()
            ->all();

        unset($data['misi_text']);
        $data['misi'] = $misiLines;

        $profile->update($data);

        return back()->with('success', 'Profil masjid berhasil diperbarui.');
    }
}
