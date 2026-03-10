<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CompteCreeMail;
use App\Models\CandidatProfile;
use App\Models\CoachProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $coachs = User::where('role', 'coach')
            ->with('coachProfile')
            ->withCount('assignments')
            ->latest()
            ->get();

        $candidats = User::where('role', 'candidat')
            ->with('candidatProfile')
            ->latest()
            ->get();

        $admins = User::where('role', 'admin')
            ->latest()
            ->get();

        return view('admin.users.index', compact('coachs', 'candidats', 'admins'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name'      => 'required|string|max:255',
        //     'email'     => 'required|email|unique:users,email',
        //     'phone'     => 'required|string|max:20',
        //     'role'      => 'required|in:coach,candidat',
        //     'speciality' => 'nullable|string|max:255',
        //     'bio'       => 'nullable|string',
        // ], [
        //     'name.required'  => 'Le nom est obligatoire.',
        //     'email.required' => "L'email est obligatoire.",
        //     'email.unique'   => 'Cet email est déjà utilisé.',
        //     'phone.required' => 'Le téléphone est obligatoire.',
        //     'role.required'  => 'Le rôle est obligatoire.',
        //     'role.in'        => 'Le rôle doit être coach ou candidat.',
        // ]);

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|string|max:20',
            'role'       => 'required|in:coach,candidat,admin', // ← ajouter admin
            'speciality' => 'nullable|string|max:255',
            'bio'        => 'nullable|string',
        ], [
            'name.required'  => 'Le nom est obligatoire.',
            'email.required' => "L'email est obligatoire.",
            'email.unique'   => 'Cet email est déjà utilisé.',
            'phone.required' => 'Le téléphone est obligatoire.',
            'role.required'  => 'Le rôle est obligatoire.',
            'role.in'        => 'Le rôle doit être coach, candidat ou admin.',
        ]);

        // Générer un mot de passe aléatoire
        $plainPassword = Str::random(10);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($plainPassword),
            'role'              => $request->role,
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        // Créer le profil selon le rôle
        if ($request->role === 'coach') {
            CoachProfile::create([
                'user_id'    => $user->id,
                'speciality' => $request->speciality,
                'bio'        => $request->bio,
            ]);
        }

        if ($request->role === 'candidat') {
            CandidatProfile::create([
                'user_id'            => $user->id,
                'profile_completion' => 0,
            ]);
        }

        // Envoyer les identifiants par email
        Mail::to($user->email)->send(new CompteCreeMail(
            $user->name,
            $user->email,
            $plainPassword,
            $user->role
        ));

        // return redirect()->route('admin.users.index')
        //     ->with('success', ucfirst($request->role) . ' créé avec succès. Les identifiants ont été envoyés par email.');
        $redirectRoute = match($request->role) {
        'coach'  => 'admin.users.index',
        'admin'  => 'admin.users.index',
        default  => 'admin.users.index',
    };

    return redirect()->route($redirectRoute)
    ->with('success', ucfirst($request->role) . ' créé avec succès. Les identifiants ont été envoyés par email.');
    }

    public function destroy(User $user)
    {
        $user->delete(); // Avec SoftDeletes, c'est automatiquement une suppression douce

        $role = $user->role === 'coach' ? 'Coach' : 'Candidat';

        return redirect()->route('admin.users.index')
            ->with('success', $role . ' supprimé avec succès.');
    }

    public function trashed()
    {
        $coachs = User::onlyTrashed()
            ->where('role', 'coach')
            ->with('coachProfile')
            ->latest('deleted_at')
            ->get();

        $candidats = User::onlyTrashed()
            ->where('role', 'candidat')
            ->with('candidatProfile')
            ->latest('deleted_at')
            ->get();

        return view('admin.users.trashed', compact('coachs', 'candidats'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        $role = $user->role === 'coach' ? 'Coach' : 'Candidat';

        return redirect()->route('admin.users.trashed')
            ->with('success', $role . ' ' . $user->name . ' restauré avec succès.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete(); // Suppression définitive

        return redirect()->route('admin.users.trashed')
            ->with('success', 'Utilisateur supprimé définitivement.');
    }

        public function toggleActive(User $user)
    {
        // Empêcher de se désactiver soi-même
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $role   = match($user->role) {
            'coach'  => 'Coach',
            'admin'  => 'Administrateur',
            default  => 'Candidat',
        };

        $statut = $user->is_active ? 'activé' : 'désactivé';

        return redirect()->back()
            ->with('success', $role . ' ' . $user->name . ' ' . $statut . ' avec succès.');
    }
}
