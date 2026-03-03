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
        ], [
            'parcours_professionnel.required' => 'Veuillez décrire votre parcours professionnel.',
            'parcours_professionnel.min'      => 'Votre description doit contenir au moins 50 caractères.',
        ]);
        // dd($request);
        DiagnosticRequest::create([
            'candidat_id'            => $candidat->id,
            'status'                 => 'pending',
            'parcours_professionnel' => $request->parcours_professionnel,
        ]);
        Mail::to($candidat->email)->send(new DiagnosticRequestMail($candidat->name));

        return redirect()->route('candidat.dashboard')
            ->with('success', 'Votre demande de diagnostic a été envoyée.');
    }
}
