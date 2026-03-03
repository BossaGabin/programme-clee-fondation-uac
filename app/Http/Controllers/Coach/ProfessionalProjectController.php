<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalProject;
use App\Models\User;
use Illuminate\Http\Request;

class ProfessionalProjectController extends Controller
{
    public function create(User $candidat)
    {
        return view('coach.projects.create', compact('candidat'));
    }

    public function store(Request $request, User $candidat)
    {
        $request->validate([
            'titre_projet'          => 'required|string|max:255',
            'secteur_cible'         => 'required|string|max:255',
            'poste_vise'            => 'required|string|max:255',
            'description'           => 'nullable|string',
            'objectif_court_terme'  => 'nullable|string',
            'objectif_long_terme'   => 'nullable|string',
        ]);

        ProfessionalProject::create([
            'candidat_id'           => $candidat->id,
            'coach_id'              => auth()->id(),
            'titre_projet'          => $request->titre_projet,
            'secteur_cible'         => $request->secteur_cible,
            'poste_vise'            => $request->poste_vise,
            'description'           => $request->description,
            'objectif_court_terme'  => $request->objectif_court_terme,
            'objectif_long_terme'   => $request->objectif_long_terme,
        ]);

        return redirect()->route('coach.candidat.show', $candidat)
            ->with('success', 'Projet professionnel enregistré.');
    }

    public function edit(User $candidat)
    {
        $project = ProfessionalProject::where('candidat_id', $candidat->id)->firstOrFail();
        return view('coach.projects.edit', compact('candidat', 'project'));
    }

    public function update(Request $request, User $candidat)
    {
        $request->validate([
            'titre_projet'         => 'required|string|max:255',
            'secteur_cible'        => 'required|string|max:255',
            'poste_vise'           => 'required|string|max:255',
            'description'          => 'nullable|string',
            'objectif_court_terme' => 'nullable|string',
            'objectif_long_terme'  => 'nullable|string',
        ]);

        ProfessionalProject::where('candidat_id', $candidat->id)
            ->update($request->only([
                'titre_projet', 'secteur_cible', 'poste_vise',
                'description', 'objectif_court_terme', 'objectif_long_terme'
            ]));

        return redirect()->route('coach.dashboard')
            ->with('success', 'Projet professionnel mis à jour.');
    }
}

