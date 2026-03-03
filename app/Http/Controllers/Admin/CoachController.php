<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'coach')
            ->with('coachProfile')
            ->withCount(['assignments' => fn($q) => $q->where('status', 'active')]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $coachs = $query->latest()->paginate(15);

        return view('admin.coachs.index', compact('coachs'));
    }

    public function show(User $coach)
    {
        $coach->load([
            'coachProfile',
            'assignments.candidat.candidatProfile',
            'assignments.candidat.needAssignment',
            'assignments.candidat.followUpSteps',
        ]);

        return view('admin.coachs.show', compact('coach'));
    }

    public function destroy(User $coach)
    {
        $coach->delete();

        return redirect()->route('admin.coachs.index')
            ->with('success', 'Coach supprimé avec succès.');
    }
}