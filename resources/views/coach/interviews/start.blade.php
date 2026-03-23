{{-- resources/views/coach/interviews/start.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Entretien de diagnostic</title>
@include('section.head')

<style>
    .choice-btn {
        cursor: pointer;
        display: inline-block;
    }

    .choice-btn input[type="radio"] {
        display: none;
    }

    .choice-btn span {
        display: inline-block;
        padding: 8px 18px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
        background: #fff;
    }

    .choice-btn input:checked+span {
        background: #006b08;
        border-color: #006b08;
        color: #fff;
        font-weight: bold;
    }

    .choice-btn:hover span {
        border-color: #006b08;
    }

    .choice-btn-sm span {
        padding: 6px 14px;
        font-size: 15px;
        font-weight: bold;
    }
</style>

<body class="v-light vertical-nav fix-header fix-sidebar">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <div id="main-wrapper">
        @include('section.header')
        @include('section.sidebar')

        <div class="content-body">
            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-6 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-clipboard-check mr-2"></i> Entretien de diagnostic
                        </h4>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button> <strong>Bravo!</strong> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button> <strong>Erreur!</strong> {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-6 align-self-center text-right">
                        <small class="text-muted">
                            Candidat : <strong>{{ $appointment->coachAssignment->candidat->name }}</strong>
                        </small>
                    </div>
                </div>

                {{-- Info candidat --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card" style="border-left:4px solid #006b08;">
                            <div class="card-body d-flex align-items-center" style="gap:15px;">
                                @php $candidat = $appointment->coachAssignment->candidat; @endphp
                                @if ($candidat->avatar)
                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                        style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div
                                        style="width:50px;height:50px;border-radius:50%;background:#006b08;
                                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-user text-white fa-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0 font-weight-bold">{{ $candidat->name }}</h5>
                                    <small class="text-muted">
                                        {{ $candidat->candidatProfile?->niveau_etude ?? '—' }} —
                                        {{ $candidat->candidatProfile?->domaine_formation ?? '—' }}
                                    </small>
                                </div>
                                <div class="ml-auto text-right">
                                    <small class="text-muted">Date de l'entretien</small><br>
                                    <strong>{{ \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y') }}
                                        à
                                        {{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Score en temps réel --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted font-weight-bold">SCORE EN TEMPS RÉEL</small>
                                    <div class="d-flex" style="gap:15px;">
                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $bloc)
                                            <div class="text-center">
                                                <small class="text-muted d-block">Bloc {{ $bloc }}</small>
                                                <span id="score-bloc-{{ $bloc }}" class="font-weight-bold"
                                                    style="font-size:16px;">0/20</span>
                                            </div>
                                        @endforeach
                                        <div class="text-center" style="border-left:2px solid #eee; padding-left:15px;">
                                            <small class="text-muted d-block">TOTAL</small>
                                            <span id="score-total" class="font-weight-bold"
                                                style="font-size:20px; color:#006b08;">0/100</span>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Note /20</small>
                                            <span id="note-finale" class="font-weight-bold"
                                                style="font-size:20px; color:#f4a900;">0/20</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('coach.interviews.store', $appointment) }}" id="interview-form">
                    @csrf

                    {{-- ============================= --}}
                    {{-- BLOC A --}}
                    {{-- ============================= --}}
                    <div class="card" id="bloc-A">
                        <div class="card-header" style="background:#006b08;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-bullseye mr-2"></i>
                                Bloc A — Clarté du projet professionnel
                                <span class="float-right" style="font-size:13px; opacity:0.9;">Note max : /20</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Objectif : vérifier si le bénéficiaire sait où il va.
                            </p>

                            @php $competenceA = $competences->firstWhere('order', 1); @endphp
                            <input type="hidden" name="bloc_ids[A]" value="{{ $competenceA->id }}">

                            {{-- Q1 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    1. Avez-vous un métier ou poste clairement identifié ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 3 => 'Vaguement (3)', 5 => 'Clairement (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="A" data-max="10">
                                            <input type="radio" name="blocs[A][q1]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="A"
                                                {{ old('blocs.A.q1') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q2 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    2. Savez-vous quelles compétences sont exigées pour ce métier ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 3 => 'Partiellement (3)', 5 => 'Oui clairement (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="A" data-max="10">
                                            <input type="radio" name="blocs[A][q2]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="A"
                                                {{ old('blocs.A.q2') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q3 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    3. Avez-vous identifié un secteur ou type d'employeur précis ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="A" data-max="5">
                                            <input type="radio" name="blocs[A][q3]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="A"
                                                {{ old('blocs.A.q3') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q4 --}}
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">
                                    4. Votre projet est-il réaliste par rapport à votre niveau actuel ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="A" data-max="5">
                                            <input type="radio" name="blocs[A][q4]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="A"
                                                {{ old('blocs.A.q4') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-3 text-right">
                                <span class="text-muted">Score Bloc A : </span>
                                <strong id="display-bloc-A">0/20</strong>
                            </div>
                        </div>
                    </div>

                    {{-- ============================= --}}
                    {{-- BLOC B --}}
                    {{-- ============================= --}}
                    <div class="card" id="bloc-B">
                        <div class="card-header" style="background:#4e73df;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-fire mr-2"></i>
                                Bloc B — Motivation & engagement
                                <span class="float-right" style="font-size:13px; opacity:0.9;">Note max : /20</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Objectif : mesurer la volonté réelle d'insertion.
                            </p>

                            @php $competenceB = $competences->firstWhere('order', 2); @endphp
                            <input type="hidden" name="bloc_ids[B]" value="{{ $competenceB->id }}">

                            @foreach ([
        'q1' => "1. Êtes-vous prêt(e) à vous engager dans un parcours d'accompagnement ?",
        'q2' => '2. Êtes-vous disponible pour formations, coaching et démarches ?',
        'q3' => "3. Avez-vous déjà entrepris des démarches d'insertion ?",
        'q4' => '4. Êtes-vous prêt(e) à fournir un effort personnel régulier ?',
    ] as $qkey => $qlabel)
                                <div class="form-group {{ $qkey === 'q4' ? 'mb-0' : '' }}">
                                    <label class="font-weight-bold">{{ $qlabel }}</label>
                                    <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                        @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                            <label class="choice-btn" data-bloc="B" data-max="5">
                                                <input type="radio" name="blocs[B][{{ $qkey }}]"
                                                    value="{{ $val }}" class="bloc-radio" data-bloc="B"
                                                    {{ old("blocs.B.{$qkey}") == $val ? 'checked' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-3 text-right">
                                <span class="text-muted">Score Bloc B : </span>
                                <strong id="display-bloc-B">0/20</strong>
                            </div>
                        </div>
                    </div>

                    {{-- ============================= --}}
                    {{-- BLOC C --}}
                    {{-- ============================= --}}
                    <div class="card" id="bloc-C">
                        <div class="card-header" style="background:#39b085;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-tools mr-2"></i>
                                Bloc C — Compétences & savoir-faire
                                <span class="float-right" style="font-size:13px; opacity:0.9;">Note max : /20</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Objectif : apprécier le niveau technique actuel.
                            </p>

                            @php $competenceC = $competences->firstWhere('order', 3); @endphp
                            <input type="hidden" name="bloc_ids[C]" value="{{ $competenceC->id }}">

                            {{-- Q1 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    1. Avez-vous une compétence technique valorisable ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Aucune (0)', 5 => 'Faible (5)', 10 => 'Bonne (10)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="C" data-max="10">
                                            <input type="radio" name="blocs[C][q1]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="C"
                                                {{ old('blocs.C.q1') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q2 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    2. Avez-vous déjà une expérience pratique (stage, job, activité) ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="C" data-max="5">
                                            <input type="radio" name="blocs[C][q2]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="C"
                                                {{ old('blocs.C.q2') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q3 --}}
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">
                                    3. Savez-vous décrire clairement ce que vous savez faire ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="C" data-max="5">
                                            <input type="radio" name="blocs[C][q3]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="C"
                                                {{ old('blocs.C.q3') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-3 text-right">
                                <span class="text-muted">Score Bloc C : </span>
                                <strong id="display-bloc-C">0/20</strong>
                            </div>
                        </div>
                    </div>

                    {{-- ============================= --}}
                    {{-- BLOC D --}}
                    {{-- ============================= --}}
                    <div class="card" id="bloc-D">
                        <div class="card-header" style="background:#f6c23e;">
                            <h5 class="mb-0" style="color:#333;">
                                <i class="fas fa-user-tie mr-2"></i>
                                Bloc D — Soft skills & posture professionnelle
                                <span class="float-right" style="font-size:13px; opacity:0.8;">Note max : /20</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Objectif : évaluer le comportement professionnel. Chaque critère est noté de 0 à 5.
                            </p>

                            @php $competenceD = $competences->firstWhere('order', 4); @endphp
                            <input type="hidden" name="bloc_ids[D]" value="{{ $competenceD->id }}">

                            @foreach ([
        'q1' => 'Communication (orale / écrite)',
        'q2' => 'Ponctualité & discipline',
        'q3' => 'Travail en équipe',
        'q4' => 'Attitude professionnelle',
    ] as $qkey => $qlabel)
                                <div class="form-group {{ $qkey === 'q4' ? 'mb-0' : '' }}">
                                    <label class="font-weight-bold">{{ $qlabel }}</label>
                                    <div class="d-flex" style="gap:8px; flex-wrap:wrap;">
                                        @for ($i = 0; $i <= 5; $i++)
                                            <label class="choice-btn choice-btn-sm" data-bloc="D" data-max="5">
                                                <input type="radio" name="blocs[D][{{ $qkey }}]"
                                                    value="{{ $i }}" class="bloc-radio" data-bloc="D"
                                                    {{ old("blocs.D.{$qkey}") == $i ? 'checked' : '' }}>
                                                <span>{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-3 text-right">
                                <span class="text-muted">Score Bloc D : </span>
                                <strong id="display-bloc-D">0/20</strong>
                            </div>
                        </div>
                    </div>

                    {{-- ============================= --}}
                    {{-- BLOC E --}}
                    {{-- ============================= --}}
                    <div class="card" id="bloc-E">
                        <div class="card-header" style="background:#e74a3b;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-rocket mr-2"></i>
                                Bloc E — Autonomie & préparation à l'insertion
                                <span class="float-right" style="font-size:13px; opacity:0.9;">Note max : /20</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Objectif : mesurer la capacité à agir seul.
                            </p>

                            @php $competenceE = $competences->firstWhere('order', 5); @endphp
                            <input type="hidden" name="bloc_ids[E]" value="{{ $competenceE->id }}">

                            {{-- Q1 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    1. Disposez-vous d'un CV à jour ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 5 => 'En cours (5)', 10 => 'Prêt (10)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="E" data-max="10">
                                            <input type="radio" name="blocs[E][q1]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="E"
                                                {{ old('blocs.E.q1') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q2 --}}
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    2. Savez-vous chercher un emploi ou un stage ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="E" data-max="5">
                                            <input type="radio" name="blocs[E][q2]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="E"
                                                {{ old('blocs.E.q2') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q3 --}}
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">
                                    3. Êtes-vous à l'aise avec les outils numériques ?
                                </label>
                                <div class="d-flex" style="gap:10px; flex-wrap:wrap;">
                                    @foreach ([0 => 'Non (0)', 2 => 'Autre réponse (2)', 5 => 'Oui (5)'] as $val => $label)
                                        <label class="choice-btn" data-bloc="E" data-max="5">
                                            <input type="radio" name="blocs[E][q3]" value="{{ $val }}"
                                                class="bloc-radio" data-bloc="E"
                                                {{ old('blocs.E.q3') == $val ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-3 text-right">
                                <span class="text-muted">Score Bloc E : </span>
                                <strong id="display-bloc-E">0/20</strong>
                            </div>
                        </div>
                    </div>

                    {{-- ============================= --}}
                    {{-- SYNTHÈSE COACH --}}
                    {{-- ============================= --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-comment-dots mr-2 text-info"></i> Synthèse du coach
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-success">
                                            <i class="fas fa-plus-circle mr-1"></i>
                                            Points forts <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="strengths" class="form-control @error('strengths') is-invalid @enderror" rows="4"
                                            placeholder="Points forts observés durant l'entretien..." style="min-height: 100px">{{ old('strengths') }}</textarea>
                                        @error('strengths')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-danger">
                                            <i class="fas fa-minus-circle mr-1"></i>
                                            Points faibles <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="weaknesses" class="form-control @error('weaknesses') is-invalid @enderror" rows="4"
                                            placeholder="Axes d'amélioration identifiés..." style="min-height: 100px">{{ old('weaknesses') }}</textarea>
                                        @error('weaknesses')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-info">
                                            <i class="fas fa-file-alt mr-1"></i> Synthèse globale
                                        </label>
                                        <textarea name="coach_summary" class="form-control" rows="3"
                                            placeholder="Recommandations et orientation suggérée..." style="min-height: 100px">{{ old('coach_summary') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bouton soumettre --}}
                    <div class="card">
                        <div class="card-body">

                            {{-- Orientation automatique --}}
                            <div id="orientation-box" class="alert" style="display:none;">
                                <strong><i class="fas fa-compass mr-2"></i> Orientation recommandée :</strong>
                                <span id="orientation-text"></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Total : </span>
                                    <strong id="total-recap" style="font-size:20px;">0/100</strong>
                                    <span class="mx-3 text-muted">→</span>
                                    <span class="text-muted">Note finale : </span>
                                    <strong id="note-recap" style="font-size:20px; color:#006b08;">0/20</strong>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle mr-2"></i> Enregistrer le rapport
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @include('section.foot')
</body>


<script>
    // Scores par bloc
    const blocScores = {
        A: 0,
        B: 0,
        C: 0,
        D: 0,
        E: 0
    };

    function recalcBloc(bloc) {
        const radios = document.querySelectorAll(`.bloc-radio[data-bloc="${bloc}"]:checked`);
        let total = 0;
        radios.forEach(r => total += parseInt(r.value) || 0);
        blocScores[bloc] = total;

        // Mettre à jour affichage
        document.getElementById(`score-bloc-${bloc}`).textContent = `${total}/20`;
        document.getElementById(`display-bloc-${bloc}`).textContent = `${total}/20`;

        // Recalc total
        const totalGeneral = Object.values(blocScores).reduce((a, b) => a + b, 0);
        const noteFinale = Math.round(totalGeneral / 5);

        document.getElementById('score-total').textContent = `${totalGeneral}/100`;
        document.getElementById('note-finale').textContent = `${noteFinale}/20`;
        document.getElementById('total-recap').textContent = `${totalGeneral}/100`;
        document.getElementById('note-recap').textContent = `${noteFinale}/20`;

        // Orientation automatique
        const box = document.getElementById('orientation-box');
        const txt = document.getElementById('orientation-text');
        box.style.display = 'block';

        if (noteFinale <= 9) {
            box.className = 'alert alert-danger';
            txt.textContent = 'Renforcement compétences (formation de base)';
        } else if (noteFinale <= 14) {
            box.className = 'alert alert-warning';
            txt.textContent = 'Stage / immersion professionnelle';
        } else if (noteFinale <= 17) {
            box.className = 'alert alert-info';
            txt.textContent = 'Insertion emploi accompagnée(Insertion à l\'emploi)';
        } else {
            box.className = 'alert alert-success'; 
            txt.textContent = 'Insertion rapide / autonomie (Auto emploi)';
        }
    }

    document.querySelectorAll('.bloc-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            recalcBloc(this.dataset.bloc);
        });
    });
</script>

</html>
