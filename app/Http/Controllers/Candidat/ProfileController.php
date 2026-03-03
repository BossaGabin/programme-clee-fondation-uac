<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user    = auth()->user();
        $profile = $user->candidatProfile;
        // dd($user);

        return view('candidat.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user    = auth()->user();
        $profile = $user->candidatProfile;

        $request->validate([
            'date_of_birth'      => 'nullable|date',
            'gender'             => 'nullable|in:homme,femme',
            'address'            => 'nullable|string|max:255',
            'niveau_etude'       => 'nullable|string|max:100',
            'domaine_formation'  => 'nullable|string|max:100',
            'experience_years'   => 'nullable|integer|min:0|max:50',
            'situation_actuelle' => 'nullable|in:en_emploi,sans_emploi,etudiant',
        ]);

        // Calcul complétion profil
        $fields = [
            'date_of_birth', 'gender', 'address',
            'niveau_etude', 'domaine_formation',
            'experience_years', 'situation_actuelle',
        ];

        $data = $request->only($fields);

        // Calculer le pourcentage de complétion
        $weights = [
            'date_of_birth'      => 20,
            'gender'             => 10,
            'address'            => 10,
            'niveau_etude'       => 20,
            'domaine_formation'  => 20,
            'experience_years'   => 10,
            'situation_actuelle' => 10,
        ];

        $completion = 0;
        foreach ($weights as $field => $weight) {
            $value = $data[$field] ?? $profile?->$field;
            if (!is_null($value) && $value !== '') {
                $completion += $weight;
            }
        }

        $data['profile_completion'] = $completion;

        if ($profile) {
            $profile->update($data);
        } else {
            $user->candidatProfile()->create(array_merge($data, ['user_id' => $user->id]));
        }

        return redirect()->route('candidat.profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function avatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        // Supprimer l'ancien avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return redirect()->route('candidat.profile.edit')
            ->with('success', 'Photo de profil mise à jour.');
    }

    public function cv(Request $request)
    {
        $request->validate([
            'cv' => 'required|mimes:pdf,doc,docx|max:5120',
        ]);

        $user    = auth()->user();
        $profile = $user->candidatProfile;

        // Supprimer l'ancien CV
        if ($profile?->cv_path && Storage::disk('public')->exists($profile->cv_path)) {
            Storage::disk('public')->delete($profile->cv_path);
        }

        $path = $request->file('cv')->store('cvs', 'public');

        if ($profile) {
            $profile->update(['cv_path' => $path]);
        } else {
            $user->candidatProfile()->create([
                'user_id'  => $user->id,
                'cv_path'  => $path,
            ]);
        }

        return redirect()->route('candidat.profile.edit')
            ->with('success', 'CV uploadé avec succès.');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password'         => 'required',
            'new_password'             => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('candidat.profile.edit')
            ->with('success', 'Mot de passe modifié avec succès.');
    }
}