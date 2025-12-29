<?php

namespace App\Http\Controllers;

use App\Models\MosqueProfile;

class MosqueProfileController extends Controller
{
    public function show()
    {
        $profile = MosqueProfile::firstOrFail();
        return view('mosque.profile', compact('profile'));
    }

    public function sejarah()
    {
        $profile = MosqueProfile::firstOrFail();
        return view('mosque.sejarah', compact('profile'));
    }
}
