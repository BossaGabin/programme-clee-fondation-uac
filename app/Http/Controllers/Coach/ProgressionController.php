<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CoachAssignment;
use App\Models\ProgressionUpdate;
use Illuminate\Http\Request;

class ProgressionController extends Controller
{
    // Fiche progression d'un candidat
    public function show(CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        $scores  = $assignment->currentScores();
        $updates = $assignment->progressionUpdates;

        return view('coach.progression.show', compact('assignment', 'scores', 'updates'));
    }

    // Formulaire mise à jour
    public function create(CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        $scores = $assignment->currentScores();

        return view('coach.progression.update', compact('assignment', 'scores'));
    }

    public function store(Request $request, CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        $request->validate([
            'note_seance' => 'required|string|min:10',
            'bloc_a'      => 'nullable|integer|min:0|max:20',
            'bloc_b'      => 'nullable|integer|min:0|max:20',
            'bloc_c'      => 'nullable|integer|min:0|max:20',
            'bloc_d'      => 'nullable|integer|min:0|max:20',
            'bloc_e'      => 'nullable|integer|min:0|max:20',
        ], [
            'note_seance.required' => 'Veuillez renseigner ce qui a été observé lors de cette séance.',
            'note_seance.min'      => 'La note de séance doit contenir au moins 10 caractères.',
        ]);

        // Récupérer les scores actuels du candidat
        $scores = $assignment->currentScores();
        $current = $scores['current'];

        $blocs = ['bloc_a', 'bloc_b', 'bloc_c', 'bloc_d', 'bloc_e'];
        $errors = [];
        $blocsModifies = [];

        foreach ($blocs as $bloc) {
            $nouvelleValeur = $request->$bloc;

            // Ignorer les blocs non renseignés
            if (is_null($nouvelleValeur)) continue;

            $valeurActuelle = $current[$bloc];

            // Règle 1 — Pas de régression
            if ($nouvelleValeur < $valeurActuelle) {
                $labels = [
                    'bloc_a' => 'Bloc A',
                    'bloc_b' => 'Bloc B',
                    'bloc_c' => 'Bloc C',
                    'bloc_d' => 'Bloc D',
                    'bloc_e' => 'Bloc E',
                ];
                $errors[$bloc] = $labels[$bloc] . ' : la nouvelle valeur (' . $nouvelleValeur . ') ne peut pas être inférieure au score actuel (' . $valeurActuelle . ').';
                continue;
            }

            // Règle 2 — Pas de modification si déjà à 20
            if ($valeurActuelle === 20) {
                continue; // on ignore silencieusement ce bloc
            }

            // Règle 3 — Pas de modification si aucun changement
            if ($nouvelleValeur === $valeurActuelle) continue;

            $blocsModifies[$bloc] = $nouvelleValeur;
        }

        // Retourner les erreurs de régression
        if (!empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->withErrors($errors);
        }

        // Vérifier qu'au moins un bloc a réellement évolué
        if (empty($blocsModifies)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Aucune progression détectée. Les valeurs sont identiques aux scores actuels ou les blocs sont déjà à 20/20.');
        }

        // Vérifier si TOUS les blocs sont déjà à 20 — progression terminée
        $tousA20 = collect($current)->every(fn($val) => $val === 20);
        if ($tousA20) {
            return redirect()->route('coach.progression.show', $assignment)
                ->with('error', 'Ce candidat a validé les 5 blocs à 20/20. Aucune progression à enregistrer.');
        }

        ProgressionUpdate::create([
            'coach_assignment_id' => $assignment->id,
            'bloc_a'              => $blocsModifies['bloc_a'] ?? null,
            'bloc_b'              => $blocsModifies['bloc_b'] ?? null,
            'bloc_c'              => $blocsModifies['bloc_c'] ?? null,
            'bloc_d'              => $blocsModifies['bloc_d'] ?? null,
            'bloc_e'              => $blocsModifies['bloc_e'] ?? null,
            'note_seance'         => $request->note_seance,
        ]);

        return redirect()->route('coach.progression.show', $assignment)
            ->with('success', 'Progression mise à jour avec succès.');
    }
}
