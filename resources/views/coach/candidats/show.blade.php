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

                <div class="row mb-3">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-user mr-2"></i> Fiche candidat
                        </h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">

                        {{-- @if (!$candidat->professionalProject)
                            <a href="{{ route('coach.projects.create', $candidat) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus mr-1"></i> Définir le projet professionnel
                            </a>
                        @endif --}}

                        <a href="{{ route('coach.candidats.pdf', $candidat) }}" class="btn btn-sm btn-danger"
                            target="_blank">
                            <i class="fas fa-file-pdf mr-1"></i> Exporter la fiche candidat en PDF
                        </a>
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

                            $entretienExistant = \App\Models\Appointment::where('coach_assignment_id', auth()->id())
                                ->where('status', 'scheduled')
                                ->first();
                        @endphp

                        @if ($prochainEntretien)
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
                        @endif
                        @if (!$interview)
                            <div class="alert alert-warning d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Le candidat est déjà promgrammé pour un entretien.
                                </span>
                                <a href="{{ route('coach.appointments.create', $assignment) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fas fa-calendar-plus mr-1"></i> Reporter l'entretien
                                </a>
                            </div>
                        @endif

                        {{-- Besoin professionnel --}}
                        @if ($candidat->needAssignment)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-bullseye mr-2 text-success"></i> Besoin professionnel
                                    </h6>
                                    @if (!$candidat->professionalProject)
                                        <div>
                                            <a href="{{ route('coach.projects.create', $candidat) }}"
                                                class="btn btn-sm btn-success mr-1">
                                                <i class="fas fa-plus mr-1"></i> Créer le projet professionnel
                                            </a>
                                        </div>
                                    @endif
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
                                            'formation' => ['label' => 'Formation', 'class' => 'badge-danger'],
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
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-project-diagram mr-2 text-info"></i> Projet professionnel
                                    </h6>
                                    <div>
                                        <a href="{{ route('coach.projects.edit', $candidat) }}"
                                            class="btn btn-sm btn-warning mr-1">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <a href="{{ route('coach.projects.pdf', $candidat) }}"
                                            class="btn btn-sm btn-danger d-none d-md-inline-block" target="_blank">
                                            <i class="fas fa-file-pdf mr-1"></i> Exporter en PDF
                                        </a>
                                    </div>
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

                        {{-- Progression des compétences --}}
                        @if ($interview && $assignment)
                            @php
                                $assignment->load('progressionUpdates');
                                $scores = $assignment->currentScores();

                                $blocColors = [
                                    1 => '#006b08',
                                    2 => '#4e73df',
                                    3 => '#1cc88a',
                                    4 => '#f6c23e',
                                    5 => '#e74a3b',
                                ];
                                $blocsIcons = [
                                    1 => 'fa-bullseye',
                                    2 => 'fa-fire',
                                    3 => 'fa-tools',
                                    4 => 'fa-user-tie',
                                    5 => 'fa-rocket',
                                ];
                                $noteFinale = round($interview->total_score / 5);
                                $scoreColor =
                                    $noteFinale <= 9
                                        ? '#e74a3b'
                                        : ($noteFinale <= 14
                                            ? '#f6c23e'
                                            : ($noteFinale <= 17
                                                ? '#17a2b8'
                                                : '#1cc88a'));
                                $blocKeys = [1 => 'bloc_a', 2 => 'bloc_b', 3 => 'bloc_c', 4 => 'bloc_d', 5 => 'bloc_e'];
                            @endphp

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-chart-line mr-2 text-info"></i> Progression des compétences
                                    </h6>
                                    @if (!collect($scores['current'])->every(fn($v) => $v === 20))
                                        <a href="{{ route('coach.progression.create', $assignment) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Nouvelle mise à jour
                                        </a>
                                    @endif
                                </div>
                                <div class="card-body">

                                    {{-- En-têtes --}}
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold text-secondary mb-0">
                                                <i class="fas fa-clipboard-check mr-1"></i> Scores initiaux
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold text-success mb-0">
                                                <i class="fas fa-chart-line mr-1"></i> Progression actuelle
                                            </h6>
                                        </div>
                                    </div>
                                    <hr class="mt-0 mb-3">

                                    {{-- Lignes par bloc --}}
                                    @foreach ($interview->scores->sortBy('competence.order') as $score)
                                        @php
                                            $order = $score->competence->order;
                                            $color = $blocColors[$order] ?? '#006b08';
                                            $icon = $blocsIcons[$order] ?? 'fa-star';
                                            $pctInitial = ($score->note / 20) * 100;
                                            $blocKey = $blocKeys[$order] ?? null;
                                            $current = $blocKey ? $scores['current'][$blocKey] : $score->note;
                                            $diff = $blocKey ? $scores['progression'][$blocKey] : 0;
                                            $pctCurrent = ($current / 20) * 100;
                                            $valide = $current === 20;
                                        @endphp

                                        <div class="row align-items-center mb-4">

                                            {{-- GAUCHE : score initial --}}
                                            <div class="col-md-6"
                                                style="border-right:1px dashed #dee2e6; padding-right:20px;">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small
                                                        class="font-weight-bold">{{ $score->competence->name }}</small>
                                                    <small class="font-weight-bold">{{ $score->note }}/20</small>
                                                </div>
                                                <div class="progress" style="height:16px; border-radius:8px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width:{{ $pctInitial }}%; background:{{ $color }}; border-radius:8px;">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- DROITE : progression --}}
                                            <div class="col-md-6" style="padding-left:20px;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="font-weight-bold">
                                                        <i class="fas {{ $icon }} mr-1"
                                                            style="color:{{ $color }}"></i>
                                                        {{ $score->competence->name }}
                                                        @if ($valide)
                                                            <span class="badge badge-success ml-1">
                                                                <i class="fas fa-check"></i> Validé
                                                            </span>
                                                        @endif
                                                    </small>
                                                    @if ($diff > 0)
                                                        <span class="badge badge-success">+{{ $diff }}
                                                            pts</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inchangé</span>
                                                    @endif
                                                </div>

                                                {{-- Barre superposée --}}
                                                <div
                                                    style="position:relative; height:16px; background:#e9ecef;
                            border-radius:8px; overflow:hidden;">
                                                    {{-- Gris = initial --}}
                                                    <div
                                                        style="position:absolute; top:0; left:0; height:100%;
                                width:{{ $pctInitial }}%; background:#ced4da;
                                border-radius:8px 0 0 8px; z-index:1;">
                                                    </div>
                                                    {{-- Couleur = actuel --}}
                                                    <div class="bar-show-{{ $blocKey }}"
                                                        style="position:absolute; top:0; left:0; height:100%;
                                width:0%; background:{{ $color }};
                                border-radius:8px; z-index:2;
                                transition:width 1.2s ease; opacity:0.9;">
                                                    </div>
                                                    {{-- Label --}}
                                                    <div
                                                        style="position:absolute; top:0; left:0; width:100%; height:100%;
                                display:flex; align-items:center; justify-content:center; z-index:3;">
                                                        <small
                                                            style="font-size:11px; font-weight:bold; color:#fff;
                                    text-shadow:0 0 3px rgba(0,0,0,0.5);">
                                                            {{ $current }}/20
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-1">
                                                    <small class="text-muted" style="font-size:11px;">
                                                        Départ : {{ $score->note }}/20
                                                    </small>
                                                    <small
                                                        style="font-size:11px; color:{{ $color }}; font-weight:bold;">
                                                        Actuel : {{ $current }}/20
                                                    </small>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach

                                    <hr>

                                    {{-- Totaux --}}
                                    <div class="row">
                                        <div class="col-md-6"
                                            style="border-right:1px dashed #dee2e6; padding-right:20px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Total général</strong>
                                                <strong
                                                    style="font-size:16px;">{{ $interview->total_score }}/100</strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <strong>Note finale</strong>
                                                <strong style="font-size:20px; color:{{ $scoreColor }};">
                                                    {{ $noteFinale }}/20
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="padding-left:20px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Total actuel</strong>
                                                <strong style="font-size:16px; color:#006b08;">
                                                    {{ $scores['total_current'] }}/100
                                                </strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <strong>Progression globale</strong>
                                                @php $prog = $scores['total_progression']; @endphp
                                                <strong style="font-size:16px;"
                                                    class="{{ $prog > 0 ? 'text-success' : 'text-muted' }}">
                                                    {{ $prog > 0 ? '+' : '' }}{{ $prog }} pts
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($interview->strengths || $interview->weaknesses || $interview->coach_summary)
                                        <hr>
                                        <div class="row mt-2">
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
<script>
    window.addEventListener('load', function() {
        setTimeout(() => {
            @if(isset($interview) && $interview && isset($scores) && $scores && isset($blocKeys))
                @foreach($interview->scores->sortBy('competence.order') as $score)
                    @php
                        $order = $score->competence->order;
                        $bKey  = $blocKeys[$order] ?? null;
                        $pct   = $bKey ? ($scores['current'][$bKey] / 20) * 100 : 0;
                    @endphp
                    @if($bKey)
                        (function() {
                            const bar = document.querySelector('.bar-show-{{ $bKey }}');
                            if (bar) bar.style.width = '{{ $pct }}%';
                        })();
                    @endif
                @endforeach
            @endif
        }, 300);
    });
</script>
    @include('section.foot')
</body>

</html>
