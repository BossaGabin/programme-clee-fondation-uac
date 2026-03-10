<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Mail\DiagnosticRequestMail;
use App\Models\DiagnosticRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DiagnosticRequestController extends Controller
{
    public function store(Request $request)
    {
        $candidat = auth()->user();
        $profile  = $candidat->candidatProfile;

        if ($profile->profile_completion < 100) {
            return redirect()->route('candidat.profile.edit')
                ->withErrors(['error' => 'Votre profil doit être complet à 100% avant de faire une demande.']);
        }

        $existante = DiagnosticRequest::where('candidat_id', $candidat->id)
            ->whereIn('status', ['pending', 'validated'])
            ->exists();

        if ($existante) {
            return redirect()->route('candidat.dashboard')
                ->withErrors(['error' => 'Vous avez déjà une demande en cours.']);
        }

        $request->validate([
            'parcours_professionnel' => 'required|string|min:50',
            'mode_entretien'         => 'required|in:presentiel,en_ligne',
            'plateforme_enligne'     => 'required_if:mode_entretien,en_ligne|nullable|in:whatsapp,google_meet,appel_direct',
            'numero_whatsapp'        => 'required_if:plateforme_enligne,whatsapp|nullable|string|max:20',
            'numero_appel'           => 'required_if:plateforme_enligne,appel_direct|nullable|string|max:20',
        ], [
            'parcours_professionnel.required' => 'Veuillez décrire votre parcours professionnel.',
            'parcours_professionnel.min'      => 'Votre description doit contenir au moins 50 caractères.',
            'mode_entretien.required'         => 'Veuillez choisir un mode d\'entretien.',
            'mode_entretien.in'               => 'Le mode d\'entretien choisi est invalide.',
            'plateforme_enligne.required_if'  => 'Veuillez choisir une plateforme en ligne.',
            'numero_whatsapp.required_if'     => 'Veuillez renseigner votre numéro WhatsApp.',
            'numero_appel.required_if'        => 'Veuillez renseigner votre numéro joignable.',
        ]);

        DiagnosticRequest::create([
            'candidat_id'            => $candidat->id,
            'status'                 => 'pending',
            'parcours_professionnel' => $request->parcours_professionnel,
            'mode_entretien'         => $request->mode_entretien,
            'plateforme_enligne'     => $request->mode_entretien === 'en_ligne'
                                            ? $request->plateforme_enligne
                                            : null,
            'numero_whatsapp'        => $request->plateforme_enligne === 'whatsapp'
                                            ? $request->numero_whatsapp
                                            : null,
            'numero_appel'           => $request->plateforme_enligne === 'appel_direct'
                                            ? $request->numero_appel
                                            : null,
        ]);

        Mail::to($candidat->email)->send(new DiagnosticRequestMail($candidat->name));

        return redirect()->route('candidat.dashboard')
            ->with('success', 'Votre demande de diagnostic a été envoyée.');
    }
}