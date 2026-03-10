<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CandidatProfile;
use App\Models\CoachProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string|max:20',
            'date_of_birth' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'password'      => 'required|min:8|confirmed',
        ], [
            'name.required'              => 'Le nom est obligatoire.',
            'email.required'             => "L'email est obligatoire.",
            'email.unique'               => 'Cet email est déjà utilisé.',
            'phone.required'             => 'Le téléphone est obligatoire.',
            'date_of_birth.required'     => 'La date de naissance est obligatoire.',
            'date_of_birth.before_or_equal' => 'Vous devez avoir au moins 18 ans pour vous inscrire.',
            'password.required'          => 'Le mot de passe est obligatoire.',
            'password.min'               => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'         => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérification âge maximum 50 ans
        $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
        if ($age > 50) {
            return back()
                ->withInput()
                ->withErrors([
                    'date_of_birth' => 'Ce programme ne prend pas en charge les personnes de plus de 50 ans. Veuillez contacter le secrétariat au 0162470707 pour être redirigé vers le programme adapté à votre âge.'
                ]);
        }        

        // -----------------------------------------------
        // Détermination du rôle
        // Par défaut : candidat
        // Si un rôle est envoyé depuis un formulaire admin
        // on l'utilise, sinon on force candidat
        // -----------------------------------------------
        $role = $request->input('role', 'candidat');

        if (in_array($role, ['coach', 'admin'])) {
            // Seul un admin connecté peut créer un coach ou un autre admin
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                $role = 'candidat';
            }
        }

        // Upload avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        // Pour les comptes créés par l'admin (coach/admin),
        // l'email est directement vérifié, pas besoin d'OTP
        $isAdmin         = in_array($role, ['coach', 'admin']);
        $otp             = $isAdmin ? null : str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $emailVerifiedAt = $isAdmin ? now() : null;
        $otpExpiresAt    = $isAdmin ? null : now()->addMinutes(10);

        // Créer l'utilisateur
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'avatar'            => $avatarPath,
            'password'          => Hash::make($request->password),
            'role'              => $role,
            'otp_code'          => $otp,
            'otp_expires_at'    => $otpExpiresAt,
            'email_verified_at' => $emailVerifiedAt,
            'is_active'         => true,
        ]);

        // Créer le profil selon le rôle
        if ($role === 'candidat') {
            CandidatProfile::create([
                'user_id'            => $user->id,
                'profile_completion' => 0,
                'date_of_birth'      => $request->date_of_birth,
            ]);

            // Envoyer l'OTP par mail uniquement pour les candidats
            Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, $user->name));

            session(['otp_user_id' => $user->id]);

            return redirect()->route('otp.form');
        }

        if ($role === 'coach') {
            CoachProfile::create([
                'user_id'    => $user->id,
                'speciality' => $request->input('speciality'),
                'bio'        => $request->input('bio'),
            ]);
        }

        // Redirection après création par l'admin (coach ou admin)
        return redirect()->route('admin.coachs.index')
            ->with('success', ucfirst($role) . ' créé avec succès.');
    }


}