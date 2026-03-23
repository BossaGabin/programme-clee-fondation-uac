<?php

// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Admin\CandidatController as AdminCandidatController;
use App\Http\Controllers\Admin\CoachAssignmentController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DiagnosticRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Candidat;
use App\Http\Controllers\Candidat\DashboardController as CandidatDashboard;
use App\Http\Controllers\Candidat\DiagnosticRequestController as CandidatDiagnosticController;
use App\Http\Controllers\Coach\AppointmentController;
use App\Http\Controllers\Coach\CandidatController;
use App\Http\Controllers\Coach\DashboardController as CoachDashboard;
use App\Http\Controllers\Coach\FollowUpStepController;
use App\Http\Controllers\Coach\InterviewController;
use App\Http\Controllers\Coach\NeedAssignmentController;
use App\Http\Controllers\Coach\ProfessionalProjectController;
use App\Http\Controllers\Coach\ProgressionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// =============================================
// AUTH PUBLIC (candidat s'inscrit lui-même)
// =============================================
Route::middleware('guest')->group(function () {

    Route::get('/verifier-email',  [OtpController::class, 'showForm'])->name('otp.form');
    Route::post('/verifier-email', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/renvoyer-otp',   [OtpController::class, 'resend'])->name('otp.resend');
});

// =============================================
// ADMIN
// =============================================

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');


    // Gestion des coachs
    Route::get('/coachs',             [CoachController::class, 'index'])->name('coachs.index');
    Route::get('/coachs/{coach}',     [CoachController::class, 'show'])->name('coachs.show');
    Route::delete('/coachs/{coach}',  [CoachController::class, 'destroy'])->name('coachs.destroy');

    // Demandes de diagnostic
    Route::get('/demandes',                      [DiagnosticRequestController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}',            [DiagnosticRequestController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/valider',   [DiagnosticRequestController::class, 'validated'])->name('demandes.validate');
    Route::post('/demandes/{demande}/rejeter',   [DiagnosticRequestController::class, 'reject'])->name('demandes.reject');

    // Affectation coach
    Route::post('/demandes/{demande}/affecter',  [CoachAssignmentController::class, 'store'])->name('assignments.store');

    // Fiche candidat (vue complète du parcours)
    Route::get('/candidats',            [AdminCandidatController::class, 'index'])->name('candidats.index');
    Route::get('/candidats/{candidat}', [AdminCandidatController::class, 'show'])->name('candidats.show');
    Route::get('/candidats/{candidat}/pdf', [AdminCandidatController::class, 'exportPdf'])->name('candidats.pdf');

    Route::get('/utilisateurs/creer',  [UserController::class, 'create'])->name('users.create');
    Route::post('/utilisateurs/creer', [UserController::class, 'store'])->name('users.store');
    Route::get('/utilisateurs',        [UserController::class, 'index'])->name('users.index');
    Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');

    // Rapport d'evaluation dans la liste des candidats et exporter en pdf
    Route::get('/candidats/{candidat}/rapport', [InterviewController::class, 'reportByCandidat'])->name('candidats.rapport');
    Route::get('/entretiens/{interview}/pdf',          [InterviewController::class, 'exportPdf'])->name('interviews.pdf');

    //exporter en pdf le projet professionnel d'un candidat dans le show
    // Dans le groupe admin
    Route::get('/candidats/{candidat}/progression', [AdminCandidatController::class, 'progression'])->name('candidats.progression');


    // Profil admin
    Route::get('/profile',          [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Les routes pour voir les users supprimés et les restaurer
    Route::get('/users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

    //Voir la progression des candidats
    Route::get('/progression/{assignment}',        [ProgressionController::class, 'show'])->name('progression.show');
});

// =============================================
// COACH
// =============================================

Route::middleware(['auth', 'isCoach'])->prefix('coach')->name('coach.')->group(function () {

    Route::get('/dashboard', [CoachDashboard::class, 'index'])->name('dashboard');

    // Entretiens programmés
    Route::get('/entretiens',                          [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/candidats/{assignment}/programmer',   [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/candidats/{assignment}/programmer',  [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/entretiens/{appointment}',         [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Passer à l'entretien
    Route::get('/entretiens/{appointment}/demarrer',   [InterviewController::class, 'start'])->name('interviews.start');
    Route::get('/entretiens/termine',   [InterviewController::class, 'termine'])->name('interviews.end');
    Route::post('/entretiens/{appointment}/soumettre', [InterviewController::class, 'store'])->name('interviews.store');
    Route::get('/entretiens/{interview}/rapport',      [InterviewController::class, 'report'])->name('interviews.report');
    Route::get('/entretiens/{interview}/pdf',          [InterviewController::class, 'exportPdf'])->name('interviews.pdf');

    // Projet professionnel
    Route::get('/candidats/{candidat}/projet',       [ProfessionalProjectController::class, 'create'])->name('projects.create');
    Route::post('/candidats/{candidat}/projet',      [ProfessionalProjectController::class, 'store'])->name('projects.store');
    Route::get('/candidats/{candidat}/projet/edit',  [ProfessionalProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/candidats/{candidat}/projet',       [ProfessionalProjectController::class, 'update'])->name('projects.update');
    Route::get('/candidats/{candidat}/project', [ProfessionalProjectController::class, 'show'])->name('projects.show');
    Route::get('/candidats/{candidat}/project/pdf', [ProfessionalProjectController::class, 'exportPdf'])->name('projects.pdf');

    // Affectation du besoin
    Route::get('/candidats/{candidat}/besoin',       [NeedAssignmentController::class, 'create'])->name('needs.create');
    Route::post('/candidats/{candidat}/besoin',      [NeedAssignmentController::class, 'store'])->name('needs.store');

    // Suivi du candidat
    Route::get('/candidats/{candidat}/suivi',        [FollowUpStepController::class, 'index'])->name('followup.index');
    Route::post('/candidats/{candidat}/suivi',       [FollowUpStepController::class, 'store'])->name('followup.store');
    Route::put('/suivi/{step}/completer',            [FollowUpStepController::class, 'complete'])->name('followup.complete');
    Route::delete('/suivi/{step}',                   [FollowUpStepController::class, 'destroy'])->name('followup.destroy');

    Route::get('/candidats/{candidat}', [CandidatController::class, 'show'])->name('candidats.show');
    Route::get('/candidats/{candidat}/rapport', [InterviewController::class, 'reportByCandidat'])->name('interviews.report.candidat');

    // Profil coach
    Route::get('/profile',          [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    //Export la fiche du candidat
    Route::get('/candidats/{candidat}/pdf', [AdminCandidatController::class, 'exportPdf'])->name('candidats.pdf');

    // Annuler ou reporter un entretien 
    Route::get('/appointments/{appointment}/report', [AppointmentController::class, 'editReport'])->name('appointments.report');
    Route::put('/appointments/{appointment}/report', [AppointmentController::class, 'report'])->name('appointments.update.report');

    // Affectations
    Route::get('/assignments',                                [App\Http\Controllers\Coach\CoachAssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments/{assignment}/accept',           [App\Http\Controllers\Coach\CoachAssignmentController::class, 'accept'])->name('assignments.accept');
    Route::get('/assignments/{assignment}/reject-form',       [App\Http\Controllers\Coach\CoachAssignmentController::class, 'rejectForm'])->name('assignments.rejectForm');
    Route::post('/assignments/{assignment}/reject',           [App\Http\Controllers\Coach\CoachAssignmentController::class, 'reject'])->name('assignments.reject');

    //Enregistrer les progression de chaque candidat
    Route::get('/progression/{assignment}',        [ProgressionController::class, 'show'])->name('progression.show');
    Route::get('/progression/{assignment}/update', [ProgressionController::class, 'create'])->name('progression.create');
    Route::post('/progression/{assignment}/update', [ProgressionController::class, 'store'])->name('progression.store');
});

// =============================================
// CANDIDAT
// =============================================

Route::middleware(['auth', 'isCandidat'])->prefix('candidat')->name('candidat.')->group(function () {

    Route::get('/dashboard',          [CandidatDashboard::class, 'index'])->name('dashboard');

    Route::get('/profile',          [App\Http\Controllers\Candidat\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [App\Http\Controllers\Candidat\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Candidat\ProfileController::class, 'password'])->name('profile.password');
    Route::post('/profile/avatar',  [App\Http\Controllers\Candidat\ProfileController::class, 'avatar'])->name('profile.avatar');
    Route::post('/profile/cv',      [App\Http\Controllers\Candidat\ProfileController::class, 'cv'])->name('profile.cv');

    // Demande de diagnostic
    Route::post('/demande-diagnostic', [CandidatDiagnosticController::class, 'store'])->name('diagnostic.store');

    // Exporter son projet professionnel
    Route::get('/project/pdf', [CandidatDashboard::class, 'exportPdfCandidat'])->name('projects.pdf');

    // Confirmer un horaire d'entretien
    Route::get('/appointments/confirm/{proposal}/{choix}',    [CandidatDashboard::class, 'confirmAppointment'])->name('appointments.confirm');
});

Route::get('/', function () {
    return view('auth.login');
});


require __DIR__ . '/auth.php';
