<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:2000'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

       $user = User::create([
    'name' => trim($request->name),
    'username' => strtolower(trim($request->username)),
    'email' => strtolower(trim($request->email)),
    'phone' => trim($request->phone),
    'address' => trim($request->address),
    'password' => Hash::make($request->password),
    'role' => 'jamaah',
]);


        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }
}
