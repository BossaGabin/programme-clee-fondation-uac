<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->candidatProfile;
        return view('candidat.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'date_of_birth'     => 'nullable|date|before:today',
            'gender'            => 'nullable|in:homme,femme',
            'address'           => 'nullable|string|max:255',
            'niveau_etude'      => 'nullable|string|max:255',
            'domaine_formation' => 'nullable|string|max:255',
            'experience_years'  => 'nullable|integer|min:0|max:50',
            'situation_actuelle' => 'nullable|in:en_emploi,sans_emploi,etudiant',
        ]);

        $profile = auth()->user()->candidatProfile;
        $profile->update($request->only([
            'date_of_birth',
            'gender',
            'address',
            'niveau_etude',
            'domaine_formation',
            'experience_years',
            'situation_actuelle',
        ]));

        $profile->calculateCompletion();

        return redirect()->route('candidat.profile.edit')
            ->with('success', 'Profil mis à jour.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');
        auth()->user()->update(['avatar' => $path]);

        return redirect()->back()->with('success', 'Photo de profil mise à jour.');
    }

    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv' => 'required|mimes:pdf,doc,docx|max:5120',
        ]);

        $path = $request->file('cv')->store('cvs', 'public');
        auth()->user()->candidatProfile->update(['cv_path' => $path]);

        return redirect()->back()->with('success', 'CV uploadé avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required'     => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min'          => 'Le mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed'    => 'Les mots de passe ne correspondent pas.',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Mot de passe modifié avec succès.');
    }
}
