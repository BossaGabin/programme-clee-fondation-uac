<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\FollowUpStep;
use App\Models\User;
use Illuminate\Http\Request;

class FollowUpStepController extends Controller
{
    public function index(User $candidat)
    {
        $steps = FollowUpStep::where('candidat_id', $candidat->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('coach.followup.index', compact('candidat', 'steps'));
    }

    public function store(Request $request, User $candidat)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        FollowUpStep::create([
            'candidat_id' => $candidat->id,
            'coach_id'    => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'in_progress',
        ]);

        return back()
            ->with('success', 'Étape ajoutée.');
        // return redirect()->route('coach.followup.index', $candidat)
        //     ->with('success', 'Étape ajoutée.');
    }

    public function complete(Request $request, FollowUpStep $step)
    {
        $request->validate(['result' => 'nullable|string']);

        $step->update([
            'status'         => 'completed',
            'completed_date' => now()->toDateString(),
            'result'         => $request->result,
        ]);

        return back()->with('success', 'Étape marquée comme terminée.');
    }

    public function destroy(FollowUpStep $step)
    {
        abort_if($step->coach_id !== auth()->id(), 403);
        $step->delete();
        return back()->with('success', 'Étape supprimée.');
    }
}