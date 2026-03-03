<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Competence;
use App\Models\Interview;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function start(Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        $interview = $appointment->interview ?? Interview::create([
            'appointment_id' => $appointment->id,
            'status'         => 'in_progress',
        ]);

        // $appointment->update(['status' => 'in_progress']);

        $competences = Competence::orderBy('order')->get();

        return view('coach.interviews.start', compact('appointment', 'interview', 'competences'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        $request->validate([
            'blocs'        => 'required|array',
            'strengths'    => 'required|string',
            'weaknesses'   => 'required|string',
            'coach_summary' => 'nullable|string',
        ]);

        $interview = $appointment->interview;

        // Calculer le score de chaque bloc et enregistrer
        $totalGeneral = 0;

        foreach ($request->blocs as $blocKey => $questions) {
            $competenceId = $request->bloc_ids[$blocKey] ?? null;
            if (!$competenceId) continue;

            // Sommer les réponses du bloc
            $scoreBloc = array_sum(array_map('intval', $questions));

            // S'assurer que le score ne dépasse pas 20
            $scoreBloc = min($scoreBloc, 20);
            $totalGeneral += $scoreBloc;

            $interview->scores()->updateOrCreate(
                ['competence_id' => $competenceId],
                [
                    'note'    => $scoreBloc,
                    'comment' => null,
                ]
            );
        }

        // Note finale = total / 5
        $noteFinale = round($totalGeneral / 5);

        // Orientation automatique
        $orientation = match (true) {
            $noteFinale <= 7  => 'Renforcement compétences (formation de base)',
            $noteFinale <= 11 => 'Stage / immersion professionnelle',
            $noteFinale <= 15 => 'Insertion emploi accompagnée',
            default           => 'Insertion rapide / autonomie',
        };

        $interview->update([
            'status'        => 'completed',
            'total_score'   => $totalGeneral,
            'strengths'     => $request->strengths,
            'weaknesses'    => $request->weaknesses,
            'coach_summary' => $request->coach_summary ?? $orientation,
            'completed_at'  => now(),
        ]);

        $appointment->update(['status' => 'completed']);

        return redirect()->route('coach.interviews.report', $interview)
            ->with('success', 'Entretien terminé. Voici le rapport.');
    }

    public function report(Interview $interview)
    {
        $interview->load('scores.competence', 'appointment.coachAssignment.candidat.candidatProfile');
        return view('coach.interviews.report', compact('interview'));
    }

    public function exportPdf(Interview $interview)
    {
        $interview->load('scores.competence', 'appointment.coachAssignment.candidat.candidatProfile');
        $pdf = Pdf::loadView('coach.interviews.pdf', compact('interview'));
        $candidatName = $interview->appointment->coachAssignment->candidat->name;
        return $pdf->download("rapport-entretien-" . str()->slug($candidatName) . ".pdf");
    }

    public function reportByCandidat(User $candidat)
    {
        $interview = Interview::whereHas('appointment', function ($q) use ($candidat) {
            $q->whereHas('coachAssignment', function ($q2) use ($candidat) {
                $q2->where('candidat_id', $candidat->id);
            });
        })->with('scores.competence', 'appointment.coachAssignment.candidat.candidatProfile')
            ->first();

        if (!$interview) {
            return redirect()->back()->with('error', 'Aucun entretien passé pour ce candidat.');
        }

        return view('coach.interviews.report', compact('interview'));
    }
}
