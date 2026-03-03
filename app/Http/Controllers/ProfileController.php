<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // public function edit()
    // {
    //     $user = auth()->user()->load('coachProfile');
    //     return view('profile.edit', compact('user'));
    // }

    public function edit()
    {
        $user = auth()->user()->load('coachProfile');

        if ($user->role === 'candidat') {
            $profile = $user->candidatProfile;
            return view('candidat.profile.edit', compact('user', 'profile'));
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'       => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'avatar'     => 'nullable|image|max:2048',
            'speciality' => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:1000',
        ];

        if ($user->role === 'admin') {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        $request->validate($rules);

        $updateData = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        if ($user->role === 'admin') {
            $updateData['email'] = $request->email;
        }
        // dd($request->hasFile('avatar'), $updateData);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $path;
        }

        $user->update($updateData);

        if ($user->role === 'coach') {
            $user->coachProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'speciality' => $request->speciality,
                    'bio'        => $request->bio,
                ]
            );
        }

        return redirect()->route(auth()->user()->role . '.profile.edit')
            ->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route(auth()->user()->role . '.profile.edit')
            ->with('success', 'Mot de passe mis à jour.');
    }
}
