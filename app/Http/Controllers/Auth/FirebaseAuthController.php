<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kreait\Firebase\JWT\IdTokenVerifier;

class FirebaseAuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        $idToken = $request->string('id_token')->toString();
        $projectId = config('services.firebase.project_id');

        if (! $projectId) {
            return back()->withErrors(['email' => 'Konfigurasi Firebase belum diset (FIREBASE_PROJECT_ID).']);
        }

        try {
            $verifier = IdTokenVerifier::createWithProjectId($projectId);
            $token = $verifier->verifyIdToken($idToken);
            $claims = $token->claims()->all();
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Token Firebase tidak valid. Silakan login ulang.']);
        }

        $email = $claims['email'] ?? null;
        $name = $claims['name'] ?? ($claims['firebase']['sign_in_provider'] ?? 'Pengguna Firebase');

        if (! $email) {
            return back()->withErrors(['email' => 'Firebase tidak mengirimkan email yang tervalidasi.']);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'role' => 'jamaah',
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, true);

        return redirect()->intended(route('home'));
    }
}
