<?php

namespace App\Http\Controllers;

use App\Models\MasjidProfile;

class MosqueProfileController extends Controller
{
    public function show()
    {
        $profile = MasjidProfile::first(); // aman walau tabel kosong
        return view('mosque.profile', compact('profile'));
    }

    public function sejarah()
    {
        $profile = MasjidProfile::first();
        return view('mosque.sejarah', compact('profile'));
    }
}
