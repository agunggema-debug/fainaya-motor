<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Tampilkan Portal Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Validasi & Penanganan Session
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba lakukan autentikasi (Attempt Login)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // PENGALIHAN SESSION BERDASARKAN ROLE (Multi-role Redirect)
            return match ($user->role) {
                'owner'     => redirect()->route('services.index')->with('success', 'Welcome back, Guild Master!'),
                'cashier'   => redirect()->route('services.index')->with('success', 'Merchant Station Connected.'),
                'mechanic'  => redirect()->route('services.index')->with('success', 'Combatant ready for action.'),
                'warehouse' => redirect()->route('inventory.index')->with('success', 'Inventory link synchronized.'),
                default     => redirect()->route('services.index')->with('success', 'Logged in successfully.')  
            };
        }

        // Jika Login Gagal
        throw ValidationException::withMessages([
            'email' => ['Kredensial yang dimasukkan tidak cocok dengan record data kami.'],
        ]);
    }

    // Proses Logout / Revoke Session
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Session Terminated Successfully.');
    }
}