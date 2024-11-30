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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'min:4'],
        ], [
            'name.required' => 'Username harus diisi',
            'name.string' => 'Username harus berupa string',
            'name.max' => 'Username maksimal 255 kata',
            'email.required' => 'Email harus diisi',
            'email.string' => 'Email harus berupa string',
            'email.max' => 'Email maksimal 255 kata',
            'unique.max' => 'Email sudah ada',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 4 kata',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Register Berhasil, Silahkan Login.');


        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
    }
}
