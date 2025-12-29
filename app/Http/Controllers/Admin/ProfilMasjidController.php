<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasjidProfileRequest;
use App\Models\MasjidProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilMasjidController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
    }

    public function edit(): View
    {
        $profile = MasjidProfile::firstOrCreate([]);
        $this->authorize('view', $profile);

        return view('admin.profil-masjid.edit', compact('profile'));
    }

    public function update(MasjidProfileRequest $request): RedirectResponse
    {
        $profile = MasjidProfile::firstOrCreate([]);
        $this->authorize('update', $profile);

        $profile->update($request->validated());

        return back()->with('success', 'Profil masjid diperbarui.');
    }
}
