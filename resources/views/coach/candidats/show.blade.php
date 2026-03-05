{{-- resources/views/coach/candidats/show.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Fiche candidat</title>
@include('section.head')

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
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-user mr-2"></i> Fiche candidat
                        </h4>
                    </div>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                {{-- Programmer entretien --}}
                                @if ($assignment && !$interview)
                                    <a href="{{ route('coach.appointments.create', $assignment) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-calendar-plus mr-1"></i> Programmer un entretien
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-4 mb-2">
                                @if ($candidat->professionalProject)
                                    <a href="{{ route('coach.projects.edit', $candidat) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit mr-1"></i> Modifier le projet professionnel
                                    </a>
                                @else
                                    <a href="{{ route('coach.projects.create', $candidat) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fas fa-plus mr-1"></i> Définir le projet professionnel
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('coach.candidats.pdf', $candidat) }}" class="btn btn-sm btn-danger"
                                    target="_blank">
                                    <i class="fas fa-file-pdf mr-1"></i> Exporter en PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    {{-- ============================================ --}}
                    {{-- COLONNE GAUCHE --}}
                    {{-- ============================================ --}}
                    <div class="col-md-3">

                        {{-- Photo + identité --}}
                        <div class="card text-center">
                            <div class="card-body">
                                @if ($candidat->avatar)
                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                        style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #006b08;"
                                        class="mb-3">
                                @else
                                    <div
                                        style="width:90px;height:90px;border-radius:50%;background:#006b08;
                                                display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif
                                <h5 class="font-weight-bold mb-1">{{ $candidat->name }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-envelope mr-1"></i> {{ $candidat->email }}
                                </p>
                                <p class="text-muted mb-3">
                                    <i class="fas fa-phone mr-1"></i> {{ $candidat->phone }}
                                </p>

                                @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                                <div class="progress mb-1" style="height:8px;border-radius:8px;">
                                    <div class="progress-bar
                                        @if ($completion < 50) bg-danger
                                        @elseif($completion < 100) bg-warning
                                        @else bg-success @endif"
                                        style="width:{{ $completion }}%">
                                    </div>
                                </div>
                                <small class="text-muted">Profil complété à {{ $completion }}%</small>
                            </div>
                        </div>

                        {{-- Infos personnelles --}}
                        @php $profile = $candidat->candidatProfile; @endphp
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-id-card mr-2 text-primary"></i> Infos personnelles
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Date de naissance</small>
                                        <small class="font-weight-bold">{{ $profile?->date_of_birth ?? '—' }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Genre</small>
                                        <small class="font-weight-bold">{{ ucfirst($profile?->gender ?? '—') }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Adresse</small>
                                        <small class="font-weight-bold">{{ $profile?->address ?? '—' }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Niveau d'étude</small>
                                        <small class="font-weight-bold">{{ $profile?->niveau_etude ?? '—' }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Domaine</small>
                                        <small
                                            class="font-weight-bold">{{ $profile?->domaine_formation ?? '—' }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Expérience</small>
                                        <small class="font-weight-bold">{{ $profile?->experience_years ?? '0' }}
                                            ans</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Situation</small>
                                        <small
                                            class="font-weight-bold">{{ $profile?->situation_actuelle ?? '—' }}</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- CV --}}
                        @if ($profile?->cv_path)
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ Storage::url($profile->cv_path) }}" target="_blank"
                                        class="btn btn-outline-danger btn-block">
                                        <i class="fas fa-file-pdf mr-2"></i> Voir le CV
                                    </a>
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- ============================================ --}}
                    {{-- COLONNE DROITE --}}
                    {{-- ============================================ --}}
                    <div class="col-md-9">

                        {{-- Prochain entretien --}}
                        @php
                            $prochainEntretien = \App\Models\Appointment::whereHas('coachAssignment', function (
                                $q,
                            ) use ($candidat) {
                                $q->where('candidat_id', $candidat->id)->where('coach_id', auth()->id());
                            })
                                ->where('status', 'scheduled')
                                ->orderBy('scheduled_date')
                                ->first();
                        @endphp

                        @if ($prochainEntretien)
                            {{-- <div class="card" style="border-left:4px solid #006b08;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="font-weight-bold mb-1">
                                            <i class="fas fa-calendar-check text-success mr-2"></i>
                                            Prochain entretien
                                        </h6>
                                        <p class="mb-0">
                                            <strong>{{ \Carbon\Carbon::parse($prochainEntretien->scheduled_date)->format('d/m/Y') }}</strong>
                                            à <strong>{{ \Carbon\Carbon::parse($prochainEntretien->scheduled_time)->format('H:i') }}</strong>
                                            —
                                            @if ($prochainEntretien->mode === 'presentiel')
                                                <span class="badge badge-primary">Présentiel</span>
                                                <small class="text-muted ml-1">{{ $prochainEntretien->location }}</small>
                                            @else
                                                <span class="badge badge-info">En ligne</span>
                                                @if ($prochainEntretien->meeting_link)
                                                    <a href="{{ $prochainEntretien->meeting_link }}" target="_blank"
                                                       class="btn btn-sm btn-outline-info ml-2">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Rejoindre
                                                    </a>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                    @if ($assignment)
                                        <a href="{{ route('coach.appointments.create', $assignment) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-plus mr-1"></i> Nouveau
                                        </a>
                                    @endif
                                </div>
                            </div> --}}

                            <div class="card" style="border-left:4px solid #006b08;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="font-weight-bold mb-1">
                                            <i class="fas fa-calendar-check text-success mr-2"></i>
                                            Prochain entretien
                                        </h6>
                                        <p class="mb-0">
                                            <strong>{{ \Carbon\Carbon::parse($prochainEntretien->scheduled_date)->format('d/m/Y') }}</strong>
                                            à
                                            <strong>{{ \Carbon\Carbon::parse($prochainEntretien->scheduled_time)->format('H:i') }}</strong>
                                            —
                                            {{-- @if ($prochainEntretien->mode === 'presentiel') --}}
                                            <span class="badge badge-primary">Présentiel</span>
                                            <small class="text-muted ml-1">{{ $prochainEntretien->location }}</small>
                                            {{-- @else
                                                <span class="badge badge-info">En ligne</span>
                                                @if ($prochainEntretien->meeting_link)
                                                    <a href="{{ $prochainEntretien->meeting_link }}" target="_blank"
                                                        class="btn btn-sm btn-outline-info ml-2">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Rejoindre
                                                    </a>
                                                @endif
                                            @endif --}}
                                        </p>
                                    </div>
                                    <div class="d-flex" style="gap:8px;">

                                        <a href="{{ route('coach.interviews.start', $prochainEntretien) }}"
                                            class="btn btn-success">
                                            <i class="fas fa-play mr-1"></i> Commencer l'entretien
                                        </a>

                                        {{-- @if ($assignment)
                                            <a href="{{ route('coach.appointments.create', $assignment) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-plus mr-1"></i> Nouveau
                                            </a>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                        @else
                            @if (!$interview)
                                <div class="alert alert-warning d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Aucun entretien programmé pour ce candidat.
                                    </span>
                                    @if ($assignment)
                                        <a href="{{ route('coach.appointments.create', $assignment) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-calendar-plus mr-1"></i> Programmer
                                        </a>
                                    @endif
                                </div>

                            @endif
                        @endif

                        {{-- Besoin professionnel --}}
                        @if ($candidat->needAssignment)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-bullseye mr-2 text-success"></i> Besoin professionnel
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $besoin = $candidat->needAssignment;
                                        $labels = [
                                            'stage' => ['label' => 'Stage', 'class' => 'badge-primary'],
                                            'insertion_emploi' => [
                                                'label' => 'Insertion emploi',
                                                'class' => 'badge-success',
                                            ],
                                            'auto_emploi' => ['label' => 'Auto-emploi', 'class' => 'badge-warning'],
                                            'formation' => ['label' => 'Formation', 'class' => 'badge-info'],
                                        ];
                                        $info = $labels[$besoin->type] ?? [
                                            'label' => $besoin->type,
                                            'class' => 'badge-secondary',
                                        ];
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="badge {{ $info['class'] }}"
                                                style="font-size:14px;padding:8px 16px;">
                                                {{ $info['label'] }}
                                            </span>
                                        </div>
                                        <div class="col-md-9">
                                            @if ($besoin->description)
                                                <p class="mb-1">{{ $besoin->description }}</p>
                                            @endif
                                            @if ($besoin->duration)
                                                <small class="text-muted">
                                                    <i class="fas fa-clock mr-1"></i> Durée : {{ $besoin->duration }}
                                                </small>
                                            @endif
                                            @if ($besoin->program_start_date)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    Du {{ $besoin->program_start_date->format('d/m/Y') }}
                                                    @if ($besoin->program_end_date)
                                                        au {{ $besoin->program_end_date->format('d/m/Y') }}
                                                    @endif
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Projet professionnel --}}
                        @if ($candidat->professionalProject)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-project-diagram mr-2 text-info"></i> Projet professionnel
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php $projet = $candidat->professionalProject; @endphp
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">Titre du projet</small>
                                            <p class="font-weight-bold mb-2">{{ $projet->titre_projet }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Secteur cible</small>
                                            <p class="font-weight-bold mb-2">{{ $projet->secteur_cible ?? '—' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Poste visé</small>
                                            <p class="font-weight-bold mb-2">{{ $projet->poste_vise ?? '—' }}</p>
                                        </div>
                                        @if ($projet->description)
                                            <div class="col-md-12">
                                                <small class="text-muted">Description</small>
                                                <p class="mb-2">{{ $projet->description }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->objectif_court_terme)
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-flag text-warning mr-1"></i> Objectif court terme
                                                </small>
                                                <p class="mb-0">{{ $projet->objectif_court_terme }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->objectif_long_terme)
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-flag-checkered text-success mr-1"></i> Objectif
                                                    long terme
                                                </small>
                                                <p class="mb-0">{{ $projet->objectif_long_terme }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Résultats entretien --}}
                        @if ($interview)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-star mr-2 text-warning"></i> Résultats de l'entretien
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            @php
                                                $scoreColor =
                                                    $interview->total_score >= 60
                                                        ? '#1cc88a'
                                                        : ($interview->total_score >= 40
                                                            ? '#f6c23e'
                                                            : '#e74a3b');
                                            @endphp
                                            <div
                                                style="width:80px;height:80px;border-radius:50%;margin:0 auto;
                                                        background:{{ $scoreColor }};display:flex;
                                                        align-items:center;justify-content:center;">
                                                <span style="color:#fff;font-size:18px;font-weight:bold;">
                                                    {{ $interview->total_score }}/100
                                                </span>
                                            </div>
                                            <small class="text-muted mt-2 d-block">Score total</small>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Compétence</th>
                                                            <th>Note /20</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($interview->scores as $score)
                                                            <tr>
                                                                <td>{{ $score->competence->name }}</td>
                                                                <td><strong>{{ $score->note }}/20</strong></td>
                                                                <td style="width:100px;">
                                                                    <div class="progress" style="height:6px;">
                                                                        <div class="progress-bar
                                                                            @if ($score->note < 8) bg-danger
                                                                            @elseif($score->note < 14) bg-warning
                                                                            @else bg-success @endif"
                                                                            style="width:{{ ($score->note / 20) * 100 }}%">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($interview->strengths || $interview->weaknesses || $interview->coach_summary)
                                        <hr>
                                        <div class="row">
                                            @if ($interview->strengths)
                                                <div class="col-md-6">
                                                    <p class="mb-1 font-weight-bold text-success">
                                                        <i class="fas fa-plus-circle mr-1"></i> Points forts
                                                    </p>
                                                    <p class="text-muted">{{ $interview->strengths }}</p>
                                                </div>
                                            @endif
                                            @if ($interview->weaknesses)
                                                <div class="col-md-6">
                                                    <p class="mb-1 font-weight-bold text-danger">
                                                        <i class="fas fa-minus-circle mr-1"></i> Points faibles
                                                    </p>
                                                    <p class="text-muted">{{ $interview->weaknesses }}</p>
                                                </div>
                                            @endif
                                            @if ($interview->coach_summary)
                                                <div class="col-md-12">
                                                    <p class="mb-1 font-weight-bold text-info">
                                                        <i class="fas fa-comment mr-1"></i> Synthèse
                                                    </p>
                                                    <p class="text-muted">{{ $interview->coach_summary }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Suivi du parcours --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-chart-line mr-2 text-info"></i> Suivi du parcours
                                </h6>
                                <div>
                                    <small class="text-muted mr-3">
                                        {{ $candidat->followUpSteps->where('status', 'completed')->count() }} /
                                        {{ $candidat->followUpSteps->count() }} étapes terminées
                                    </small>
                                    {{-- Ajouter une étape --}}
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                        data-target="#modalAddStep">
                                        <i class="fas fa-plus mr-1"></i> Ajouter une étape
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($candidat->followUpSteps->isEmpty())
                                    <p class="text-center text-muted py-3">
                                        Aucune étape de suivi pour le moment.
                                    </p>
                                @else
                                    @foreach ($candidat->followUpSteps as $step)
                                        <div class="d-flex mb-4" style="gap:15px;">
                                            <div style="flex-shrink:0;">
                                                @if ($step->status === 'completed')
                                                    <div
                                                        style="width:38px;height:38px;border-radius:50%;background:#1cc88a;
                                                                display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-check text-white"></i>
                                                    </div>
                                                @else
                                                    <div
                                                        style="width:38px;height:38px;border-radius:50%;background:#4e73df;
                                                                display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-spinner text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="mb-1 font-weight-bold">{{ $step->title }} </h6>
                                                    <div style="display:flex;gap:6px;margin-left:10px">
                                                        <span
                                                            class="badge badge-{{ $step->status === 'completed' ? 'success' : 'primary' }}">
                                                            {{ $step->status === 'completed' ? 'Terminé' : 'En cours' }}
                                                        </span>
                                                        @if ($step->status !== 'completed')
                                                            <form method="POST"
                                                                action="{{ route('coach.followup.complete', $step) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-xs btn-success"
                                                                    style="padding:2px 8px;font-size:11px;">
                                                                    <i class="fas fa-check mr-1"></i> Terminer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($step->description)
                                                    <p class="text-muted mb-1" style="font-size:13px;">
                                                        {{ $step->description }}</p>
                                                @endif
                                                @if ($step->result)
                                                    <p class="mb-1" style="font-size:13px;">
                                                        <i class="fas fa-comment-alt text-info mr-1"></i>
                                                        <em>{{ $step->result }}</em>
                                                    </p>
                                                @endif
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $step->created_at->format('d/m/Y') }}
                                                    @if ($step->completed_date)
                                                        → Terminé le
                                                        {{ \Carbon\Carbon::parse($step->completed_date)->format('d/m/Y') }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Ajouter une étape --}}
    <div class="modal fade" id="modalAddStep">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2 text-success"></i> Ajouter une étape de suivi
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="POST" action="{{ route('coach.followup.store', $candidat) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title" class="font-weight-bold">
                                Titre de l'étape <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Ex: Rédaction du CV, Entretien simulé..." value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"
                                placeholder="Détails de l'étape...">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label for="result">Résultat / Observation</label>
                            <textarea id="result" name="result" class="form-control" rows="2"
                                placeholder="Résultat observé ou commentaire...">{{ old('result') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->has('title'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#modalAddStep').modal('show');
            });
        </script>
    @endif

    @include('section.foot')
</body>

</html>
