<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showForm()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('register');
        }

        if ($user->otp_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Code incorrect.']);
        }

        if (now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Code expiré. Veuillez en demander un nouveau.']);
        }

        // Valider l'email
        $user->update([
            'email_verified_at' => now(),
            'otp_code'          => null,
            'otp_expires_at'    => null,
        ]);

        session()->forget('otp_user_id');
        Auth::login($user);

        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'coach' => redirect()->route('coach.dashboard'),
            default => redirect()->route('candidat.dashboard'),
        };
    }

    public function resend()
    {
        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('register');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, $user->name));

        return back()->with('success', 'Un nouveau code a été envoyé.');
    }
}
