<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ini akan memakai username setelah LoginRequest kita ubah
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $redirectRoute = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'pengurus' => route('admin.pengurus.dashboard'),
            'ustadz' => route('ustadz.pertanyaan.index'),
            default => route('home'),
        };

        return redirect()->intended($redirectRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
