{{-- resources/views/admin/candidats/show.blade.php --}}
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
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button> <strong>Bravo!</strong> {{ session('success') }}
                        </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-body d-flex justify-content-between">
                        {{-- <div class="col-md-7 align-self-center text-right"> --}}
                            <div class="">
                                <a href="{{ route('admin.candidats.index') }}" class="btn btn-sm btn-outline-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Retour
                                </a>

                            </div>
                            <div class="">
                                <a href="{{ route('admin.candidats.pdf', $candidat) }}" class="btn btn-sm btn-danger"
                                    target="_blank">
                                    <i class="fas fa-file-pdf mr-1"></i> Exporter en PDF
                                </a>
                            </div>
                        {{-- </div> --}}

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
                                        style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid #006b08;"
                                        class="mb-3">
                                @else
                                    <div
                                        style="width:90px; height:90px; border-radius:50%; background:#006b08;
                                                display:flex; align-items:center; justify-content:center; margin:0 auto 15px;">
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
                                <div class="progress mb-1" style="height:8px; border-radius:8px;">
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
                                <div class="card-body text-center">
                                    <a href="{{ Storage::url($profile->cv_path) }}" target="_blank"
                                        class="btn btn-outline-danger btn-block">
                                        <i class="fas fa-file-pdf mr-2"></i> Voir le CV
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Coach assigné --}}
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-chalkboard-teacher mr-2 text-warning"></i> Coach
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($candidat->candidatAssignment?->coach)
                                    @php $coach = $candidat->candidatAssignment->coach; @endphp
                                    <div class="d-flex align-items-center" style="gap:10px;">
                                        @if ($coach->avatar)
                                            <img src="{{ Storage::url($coach->avatar) }}"
                                                style="width:42px; height:42px; border-radius:50%; object-fit:cover;">
                                        @else
                                            <div
                                                style="width:42px; height:42px; border-radius:50%; background:#f4a900;
                                                        display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="mb-0 font-weight-bold">{{ $coach->name }}</p>
                                            <small class="text-muted">{{ $coach->phone }}</small>
                                            <small class="text-muted">{{ $coach->email }}</small>
                                            <small
                                                class="text-muted">{{ $coach->coachProfile?->speciality ?? '' }}</small>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0 text-center">Non affecté</p>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- ============================================ --}}
                    {{-- COLONNE DROITE --}}
                    {{-- ============================================ --}}
                    <div class="col-md-9">

                        {{-- Demande de diagnostic --}}
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-clipboard-list mr-2 text-primary"></i> Demande de diagnostic
                                </h6>
                            </div>
                            <div class="card-body">
                                @php $demande = $candidat->diagnosticRequests->first(); @endphp
                                @if ($demande)
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <small class="text-muted">Soumise le</small>
                                            <p class="mb-0 font-weight-bold">
                                                {{ $demande->created_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                        <div>
                                            @if ($demande->status === 'pending')
                                                <span class="badge badge-warning" style="font-size:13px;">En
                                                    attente</span>
                                            @elseif($demande->status === 'validated')
                                                <span class="badge badge-success"
                                                    style="font-size:13px;">Validée</span>
                                            @else
                                                <span class="badge badge-danger"
                                                    style="font-size:13px;">Rejetée</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($demande->parcours_professionnel)
                                        <p class="text-muted mb-1"><strong>Parcours professionnel :</strong></p>
                                        <p
                                            style="background:#f8f9fc; padding:12px; border-radius:6px; line-height:1.7; color:#555;">
                                            {{ $demande->parcours_professionnel }}
                                        </p>
                                    @endif
                                    @if ($demande->note_admin)
                                        <p class="text-muted mb-1"><strong>Note admin :</strong></p>
                                        <p class="text-info">{{ $demande->note_admin }}</p>
                                    @endif
                                @else
                                    <p class="text-muted text-center py-2">Aucune demande de diagnostic.</p>
                                @endif
                            </div>
                        </div>

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
                                        <div class="col-md-3 text-center">
                                            <span class="badge {{ $info['class'] }}"
                                                style="font-size:14px; padding:8px 16px;">
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
                                        <div class="col-md-6">
                                            <p class="mb-1"><small class="text-muted">Titre du projet</small></p>
                                            <p class="font-weight-bold">{{ $projet->titre_projet }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="mb-1"><small class="text-muted">Secteur cible</small></p>
                                            <p class="font-weight-bold">{{ $projet->secteur_cible ?? '—' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="mb-1"><small class="text-muted">Poste visé</small></p>
                                            <p class="font-weight-bold">{{ $projet->poste_vise ?? '—' }}</p>
                                        </div>
                                        @if ($projet->description)
                                            <div class="col-md-12">
                                                <p class="mb-1"><small class="text-muted">Description</small></p>
                                                <p>{{ $projet->description }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->objectif_court_terme)
                                            <div class="col-md-6">
                                                <p class="mb-1"><small class="text-muted">Objectif court
                                                        terme</small></p>
                                                <p>{{ $projet->objectif_court_terme }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->objectif_long_terme)
                                            <div class="col-md-6">
                                                <p class="mb-1"><small class="text-muted">Objectif long
                                                        terme</small></p>
                                                <p>{{ $projet->objectif_long_terme }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Résultats de l'entretien --}}
                        @if ($interview)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-star mr-2 text-warning"></i> Résultats de l'entretien
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3 text-center">
                                            <div
                                                style="width:80px; height:80px; border-radius:50%; margin:0 auto;
                                                        background: {{ $interview->total_score >= 60 ? '#1cc88a' : ($interview->total_score >= 40 ? '#f6c23e' : '#e74a3b') }};
                                                        display:flex; align-items:center; justify-content:center;">
                                                <span style="color:#fff; font-size:22px; font-weight:bold;">
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
                                                                <td style="width:120px;">
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
                                    @if ($interview->strengths)
                                        <p class="mb-1"><strong class="text-success"><i
                                                    class="fas fa-plus-circle mr-1"></i> Points forts</strong></p>
                                        <p class="mb-3">{{ $interview->strengths }}</p>
                                    @endif
                                    @if ($interview->weaknesses)
                                        <p class="mb-1"><strong class="text-danger"><i
                                                    class="fas fa-minus-circle mr-1"></i> Points faibles</strong></p>
                                        <p class="mb-3">{{ $interview->weaknesses }}</p>
                                    @endif
                                    @if ($interview->coach_summary)
                                        <p class="mb-1"><strong class="text-info"><i
                                                    class="fas fa-comment mr-1"></i> Synthèse du coach</strong></p>
                                        <p>{{ $interview->coach_summary }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Timeline suivi --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-chart-line mr-2 text-info"></i> Suivi du parcours
                                </h6>
                                <small class="text-muted">
                                    {{ $candidat->followUpSteps->where('status', 'completed')->count() }} /
                                    {{ $candidat->followUpSteps->count() }} étapes terminées
                                </small>
                            </div>
                            <div class="card-body">
                                @if ($candidat->followUpSteps->isEmpty())
                                    <p class="text-center text-muted py-3">Aucune étape de suivi.</p>
                                @else
                                    @foreach ($candidat->followUpSteps as $step)
                                        <div class="d-flex mb-4" style="gap:15px;">
                                            <div style="flex-shrink:0;">
                                                @if ($step->status === 'completed')
                                                    <div
                                                        style="width:38px; height:38px; border-radius:50%; background:#1cc88a;
                                                                display:flex; align-items:center; justify-content:center;">
                                                        <i class="fas fa-check text-white"></i>
                                                    </div>
                                                @else
                                                    <div
                                                        style="width:38px; height:38px; border-radius:50%; background:#4e73df;
                                                                display:flex; align-items:center; justify-content:center;">
                                                        <i class="fas fa-spinner text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="mb-1 font-weight-bold">{{ $step->title }}</h6>
                                                    <span
                                                        class="badge badge-{{ $step->status === 'completed' ? 'success' : 'primary' }}">
                                                        {{ $step->status === 'completed' ? 'Terminé' : 'En cours' }}
                                                    </span>
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
    @include('section.foot')
</body>

</html>
