{{-- resources/views/coach/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Dashboard Coach</title>
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
                    <div class="col-md-6 align-self-center">
                        <h4 class="text-themecolor">
                            Bonjour, <strong>{{ auth()->user()->name }}</strong>
                        </h4>
                        {{-- <small class="text-muted">{{ now()->format('l d F Y') }}</small> --}}
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
                {{-- Alerte profil incomplet --}}
                @php
                    $coachProfile = auth()->user()->coachProfile;
                    $profileIncomplet = !$coachProfile?->speciality || !$coachProfile?->bio;
                @endphp

                @if ($profileIncomplet)
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert mb-0 d-flex align-items-center justify-content-between"
                                style="background:#fff8e1; border:1px solid #f6c23e; border-left:5px solid #f6c23e;
                        border-radius:6px; padding:15px 20px;">
                                <div class="d-flex align-items-center" style="gap:14px;">
                                    <div
                                        style="width:42px;height:42px;border-radius:50%;background:#f6c23e22;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-exclamation-triangle"
                                            style="color:#f6c23e; font-size:18px;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 font-weight-bold" style="color:#856404;">
                                            Votre profil coach est incomplet
                                        </p>
                                        <small style="color:#856404; opacity:0.85;">
                                            Ajoutez votre
                                            @if (!$coachProfile?->speciality && !$coachProfile?->bio)
                                                <strong>spécialité</strong> et votre <strong>biographie</strong>
                                            @elseif(!$coachProfile?->speciality)
                                                <strong>spécialité</strong>
                                            @else
                                                <strong>biographie</strong>
                                            @endif
                                            pour être visible par les candidats.
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('coach.profile.edit') }}" class="btn btn-sm btn-warning ml-3"
                                    style="white-space:nowrap; flex-shrink:0;">
                                    <i class="fas fa-user-edit mr-1"></i> Compléter mon profil
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ============================================ --}}
                {{-- LIGNE 1 : STATISTIQUES PRINCIPALES --}}
                {{-- ============================================ --}}
                <div class="row">
                    {{-- Tous mes candidats --}}
                    <div class="col-md-6">
                        <a href="{{ route('coach.dashboard') }}" class="text-decoration-none">
                            <div class="card" style="border-left:4px solid #006b08;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-1">Tous mes candidats</h6>
                                        <h3 class="mb-0 font-weight-bold">{{ $compteurs['tous'] }}</h3>
                                    </div>
                                    <div
                                        style="background:#006b08;width:50px;height:50px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-users text-white fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Entretiens programmés --}}
                    <div class="col-md-6">
                        <a href="{{ route('coach.appointments.index') }}" class="text-decoration-none">
                            <div class="card" style="border-left:4px solid #f4a900;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-1">Entretiens programmés</h6>
                                        <h3 class="mb-0 font-weight-bold">
                                            {{ $entretiens }}
                                        </h3>
                                    </div>
                                    <div
                                        style="background:#f4a900;width:50px;height:50px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-calendar-check text-white fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>



                {{-- ============================================ --}}
                {{-- LIGNE 2 : STATISTIQUES PAR TYPE DE BESOIN --}}
                {{-- ============================================ --}}
                <div class="row">
                    {{-- Stage --}}
                    <div class="col-md-3">
                        <a href="{{ route('coach.dashboard', ['statut' => 'stage']) }}" class="text-decoration-none">
                            <div class="card" style="border-left:4px solid #4e73df;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-1">Stage</h6>
                                        <h3 class="mb-0 font-weight-bold">{{ $compteurs['stage'] }}</h3>
                                    </div>
                                    <div
                                        style="background:#4e73df;width:50px;height:50px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-briefcase text-white fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Insertion emploi --}}
                    <div class="col-md-3">
                        <a href="{{ route('coach.dashboard', ['statut' => 'insertion_emploi']) }}"
                            class="text-decoration-none">
                            <div class="card" style="border-left:4px solid #1cc88a;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-1">Insertion emploi</h6>
                                        <h3 class="mb-0 font-weight-bold">{{ $compteurs['insertion_emploi'] }}</h3>
                                    </div>
                                    <div
                                        style="background:#1cc88a;width:50px;height:50px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-handshake text-white fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Auto-emploi --}}
                    <div class="col-md-3">
                        <a href="{{ route('coach.dashboard', ['statut' => 'auto_emploi']) }}"
                            class="text-decoration-none">
                            <div class="card" style="border-left:4px solid #f6c23e;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-1">Auto-emploi</h6>
                                        <h3 class="mb-0 font-weight-bold">{{ $compteurs['auto_emploi'] }}</h3>
                                    </div>
                                    <div
                                        style="background:#f6c23e;width:50px;height:50px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-lightbulb text-white fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Formation --}}
                    <div class="col-md-3">
                        <a href="{{ route('coach.dashboard', ['statut' => 'formation']) }}"
                            class="text-decoration-none">
                            <div class="card" style="border-left:4px solid #36b9cc;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-1">Formation</h6>
                                        <h3 class="mb-0 font-weight-bold">{{ $compteurs['formation'] }}</h3>
                                    </div>
                                    <div
                                        style="background:#36b9cc;width:50px;height:50px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-graduation-cap text-white fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>


                {{-- ============================================ --}}
                {{-- FILTRES PAR BESOIN --}}
                {{-- ============================================ --}}
                <div class="row mb-3 mt-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap" style="gap:8px;">
                            <a href="{{ route('coach.dashboard') }}"
                                class="btn btn-sm {{ !$statut ? 'btn-primary' : 'btn-outline-primary' }}">
                                Tous <span class="badge badge-light ml-1">{{ $compteurs['tous'] }}</span>
                            </a>
                            <a href="{{ route('coach.dashboard', ['statut' => 'stage']) }}"
                                class="btn btn-sm {{ $statut === 'stage' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Stage <span class="badge badge-light ml-1">{{ $compteurs['stage'] }}</span>
                            </a>
                            <a href="{{ route('coach.dashboard', ['statut' => 'insertion_emploi']) }}"
                                class="btn btn-sm {{ $statut === 'insertion_emploi' ? 'btn-success' : 'btn-outline-success' }}">
                                Insertion emploi <span
                                    class="badge badge-light ml-1">{{ $compteurs['insertion_emploi'] }}</span>
                            </a>
                            <a href="{{ route('coach.dashboard', ['statut' => 'auto_emploi']) }}"
                                class="btn btn-sm {{ $statut === 'auto_emploi' ? 'btn-warning' : 'btn-outline-warning' }}">
                                Auto-emploi <span
                                    class="badge badge-light ml-1">{{ $compteurs['auto_emploi'] }}</span>
                            </a>
                            <a href="{{ route('coach.dashboard', ['statut' => 'formation']) }}"
                                class="btn btn-sm {{ $statut === 'formation' ? 'btn-info' : 'btn-outline-danger' }}">
                                Formation <span class="badge badge-light ml-1">{{ $compteurs['formation'] }}</span>
                            </a>
                        </div>
                    </div>
                </div>


                {{-- ============================================ --}}
                {{-- LISTE DES CANDIDATS --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users mr-2 text-primary"></i>
                                    Mes candidats
                                    @if ($statut)
                                        <small class="text-muted">— filtrés par : {{ $statut }}</small>
                                    @endif
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($assignments->isEmpty())
                                    <p class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                        Aucun candidat trouvé.
                                    </p>
                                @else
                                    {{-- ✅ Pas de div.table-responsive — DataTables Responsive s'en charge --}}
                                    <table class="table table-hover w-100" id="table-candidats">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Candidat</th>
                                                <th>Niveau d'étude</th>
                                                <th>Besoin</th>
                                                <th>Profil</th>
                                                <th>Suivi</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assignments as $assignment)
                                                @php $candidat = $assignment->candidat; @endphp
                                                {{-- ✅ classe data-row pour la détection JS --}}
                                                <tr class="data-row">
                                                    <td>
                                                        <div class="d-flex align-items-center" style="gap:10px;">
                                                            @if ($candidat->avatar)
                                                                <img src="{{ Storage::url($candidat->avatar) }}"
                                                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                            @else
                                                                <div
                                                                    style="width:40px;height:40px;border-radius:50%;flex-shrink:0;
                                                    background:#006b08;display:flex;align-items:center;justify-content:center;">
                                                                    <i class="fas fa-user text-white"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="mb-0 font-weight-bold">{{ $candidat->name }}
                                                                </p>
                                                                <small
                                                                    class="text-muted">{{ $candidat->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>{{ $candidat->candidatProfile?->niveau_etude ?? '—' }}</td>

                                                    <td>
                                                        @php
                                                            $labels = [
                                                                'stage' => [
                                                                    'label' => 'Stage',
                                                                    'class' => 'badge-primary',
                                                                ],
                                                                'insertion_emploi' => [
                                                                    'label' => 'Insertion emploi',
                                                                    'class' => 'badge-success',
                                                                ],
                                                                'auto_emploi' => [
                                                                    'label' => 'Auto-emploi',
                                                                    'class' => 'badge-warning',
                                                                ],
                                                                'formation' => [
                                                                    'label' => 'Formation',
                                                                    'class' => 'badge-danger',
                                                                ],
                                                            ];
                                                            $type = $candidat->needAssignment?->type;
                                                        @endphp
                                                        @if ($type && isset($labels[$type]))
                                                            <span class="badge {{ $labels[$type]['class'] }}">
                                                                {{ $labels[$type]['label'] }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">—</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                                                        <div class="d-flex align-items-center" style="gap:6px;">
                                                            <div class="progress flex-grow-1"
                                                                style="height:6px;min-width:70px;">
                                                                <div class="progress-bar
                                                    @if ($completion < 50) bg-danger
                                                    @elseif($completion < 100) bg-warning
                                                    @else bg-success @endif"
                                                                    style="width:{{ $completion }}%">
                                                                </div>
                                                            </div>
                                                            <small>{{ $completion }}%</small>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        @php
                                                            $total = $candidat->followUpSteps->count();
                                                            $completed = $candidat->followUpSteps
                                                                ->where('status', 'completed')
                                                                ->count();
                                                        @endphp
                                                        <small
                                                            class="{{ $total > 0 && $completed === $total ? 'text-success font-weight-bold' : 'text-muted' }}">
                                                            {{ $completed }}/{{ $total }} étapes
                                                        </small>
                                                    </td>

                                                    {{-- <td>
                                                        @if ($assignment && !in_array($assignment->id, $assignmentsAvecEntretien))
                                                            <a href="{{ route('coach.appointments.create', $assignment) }}"
                                                                class="btn btn-sm btn-outline-primary mr-1"
                                                                title="Programmer un entretien">
                                                                <i class="fas fa-calendar-plus"></i>
                                                            </a>
                                                        @endif

                                                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="Voir la fiche">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('coach.interviews.report.candidat', $candidat) }}"
                                                            class="btn btn-sm btn-outline-success"
                                                            title="Rapport entretien">
                                                            <i class="fas fa-chart-bar"></i>
                                                        </a>

                                                        <a href="{{ route('coach.candidats.pdf', $candidat) }}"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Télécharger la fiche candidat" target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    </td> --}}
                                                    <td>
                                                        {{-- Programmer entretien — uniquement si pas encore passé --}}
                                                        @if ($assignment && !in_array($assignment->id, $assignmentsAvecEntretien))
                                                            <a href="{{ route('coach.appointments.create', $assignment) }}"
                                                                class="btn btn-sm btn-outline-primary mr-1"
                                                                title="Programmer un entretien">
                                                                <i class="fas fa-calendar-plus"></i>
                                                            </a>
                                                        @endif

                                                        {{-- Voir la fiche — toujours visible --}}
                                                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="Voir la fiche">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        {{-- Rapport + Progression — uniquement si entretien passé --}}
                                                        @if (in_array($assignment->id, $assignmentsAvecEntretien))
                                                            <a href="{{ route('coach.interviews.report.candidat', $candidat) }}"
                                                                class="btn btn-sm btn-outline-success"
                                                                title="Rapport entretien">
                                                                <i class="fas fa-chart-bar"></i>
                                                            </a>

                                                            <a href="{{ route('coach.progression.show', $assignment) }}"
                                                                class="btn btn-sm btn-outline-info"
                                                                title="Voir la progression">
                                                                <i class="fas fa-chart-line"></i>
                                                            </a>
                                                        @endif

                                                        {{-- PDF — toujours visible --}}
                                                        <a href="{{ route('coach.candidats.pdf', $candidat) }}"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Télécharger la fiche candidat" target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════ --}}
                {{-- ENTRETIENS RÉCENTS                 --}}
                {{-- ══════════════════════════════════ --}}
                <div class="row page-titles mt-2">
                    <div class="col-md-12">
                        <h4 class="text-themecolor">
                            <i class="fas fa-clipboard-list mr-2"></i> Entretiens récents
                        </h4>
                        <small class="text-muted">Candidats ayant passé leur entretien de diagnostic</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Onglets --}}
                                <ul class="nav nav-tabs mb-4" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab-jour"
                                            role="tab">
                                            <i class="fas fa-calendar-day mr-1 text-success"></i>
                                            Aujourd'hui
                                            <span
                                                class="badge badge-success ml-1">{{ $interviewsAujourdhui->count() }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-semaine" role="tab">
                                            <i class="fas fa-calendar-week mr-1 text-primary"></i>
                                            Cette semaine
                                            <span
                                                class="badge badge-primary ml-1">{{ $interviewsSemaine->count() }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-mois" role="tab">
                                            <i class="fas fa-calendar-alt mr-1 text-warning"></i>
                                            Ce mois
                                            <span
                                                class="badge badge-warning ml-1">{{ $interviewsMois->count() }}</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    @foreach (['jour' => ['id' => 'tab-jour', 'data' => $interviewsAujourdhui, 'active' => true], 'semaine' => ['id' => 'tab-semaine', 'data' => $interviewsSemaine, 'active' => false], 'mois' => ['id' => 'tab-mois', 'data' => $interviewsMois, 'active' => false]] as $key => $tab)
                                        @php
                                            $isAdmin = auth()->user()->role === 'admin';
                                            $colCount = $isAdmin ? 6 : 5;
                                        @endphp

                                        <div class="tab-pane fade {{ $tab['active'] ? 'show active' : '' }}"
                                            id="{{ $tab['id'] }}" role="tabpanel">

                                            <table class="table table-hover w-100" id="table-{{ $key }}">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Candidat</th>
                                                        @if ($isAdmin)
                                                            <th>Coach</th>
                                                        @endif
                                                        <th>Score</th>
                                                        <th>Orientation</th>
                                                        <th>Date entretien</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($tab['data'] as $interview)
                                                        @php
                                                            $candidat =
                                                                $interview->appointment->coachAssignment->candidat;
                                                            $coach = $interview->appointment->coachAssignment->coach;
                                                            $noteFinale = round($interview->total_score / 5);
                                                        @endphp
                                                        <tr class="data-row">
                                                            <td>
                                                                <div class="d-flex align-items-center"
                                                                    style="gap:10px;">
                                                                    @if ($candidat->avatar)
                                                                        <img src="{{ Storage::url($candidat->avatar) }}"
                                                                            style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                                    @else
                                                                        <div
                                                                            style="width:36px;height:36px;border-radius:50%;flex-shrink:0;
                                        background:#006b08;display:flex;align-items:center;justify-content:center;">
                                                                            <i class="fas fa-user text-white"
                                                                                style="font-size:13px;"></i>
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <p class="mb-0 font-weight-bold"
                                                                            style="font-size:13px;">
                                                                            {{ $candidat->name }}
                                                                        </p>
                                                                        <small
                                                                            class="text-muted">{{ $candidat->email }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            @if ($isAdmin)
                                                                <td>
                                                                    <span class="badge badge-secondary"
                                                                        style="font-size:11px;">
                                                                        {{ $coach->name }}
                                                                    </span>
                                                                </td>
                                                            @endif

                                                            <td>
                                                                <span class="font-weight-bold"
                                                                    style="color:#006b08; font-size:14px;">
                                                                    {{ $noteFinale }}/20
                                                                </span>
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ $interview->total_score }}/100</small>
                                                            </td>

                                                            <td>
                                                                @php
                                                                    $labels = [
                                                                        'stage' => [
                                                                            'label' => 'Stage',
                                                                            'class' => 'badge-primary',
                                                                        ],
                                                                        'insertion_emploi' => [
                                                                            'label' => 'Insertion emploi',
                                                                            'class' => 'badge-success',
                                                                        ],
                                                                        'auto_emploi' => [
                                                                            'label' => 'Auto-emploi',
                                                                            'class' => 'badge-warning',
                                                                        ],
                                                                        'formation' => [
                                                                            'label' => 'Formation',
                                                                            'class' => 'badge-danger',
                                                                        ],
                                                                    ];
                                                                    $type = $candidat->needAssignment?->type;
                                                                @endphp
                                                                @if ($type && isset($labels[$type]))
                                                                    <span class="badge {{ $labels[$type]['class'] }}">
                                                                        {{ $labels[$type]['label'] }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">—</span>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                <small>{{ \Carbon\Carbon::parse($interview->completed_at)->format('d/m/Y à H:i') }}</small>
                                                            </td>

                                                            <td>
                                                                <a href="{{ route('coach.candidats.show', $candidat) }}"
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    title="Voir la fiche">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('coach.interviews.report.candidat', $candidat) }}"
                                                                    class="btn btn-sm btn-outline-success"
                                                                    title="Rapport entretien">
                                                                    <i class="fas fa-chart-bar"></i>

                                                                </a>
                                                                <a href="{{ route('coach.progression.show', $assignment) }}"
                                                                    class="btn btn-sm btn-outline-info"
                                                                    title="Voir la progression">
                                                                    <i class="fas fa-chart-line"></i>
                                                                </a>
                                                                <a href="{{ route('coach.candidats.pdf', $candidat) }}"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    title="Télécharger la fiche candidat"
                                                                    target="_blank">
                                                                    <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            {{-- ✅ $colCount défini dans le @php au début du foreach --}}
                                                            <td colspan="{{ $colCount }}"
                                                                class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox mr-2"></i> Aucun entretien sur
                                                                cette période.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ══════════════════════════════════ --}}
                {{--  GRAPHIQUES                 --}}
                {{-- ══════════════════════════════════ --}}
                <div class="row page-titles">
                    <div class="col-md-12 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques & Graphiques
                        </h4>
                        {{-- <small class="text-muted">Vue d'ensemble de la plateforme CLEE</small> --}}
                    </div>
                </div>
                <div class="row mt-4">
                    {{-- Donut Orientations --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-bullseye mr-2 text-success"></i> Orientations
                                </h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <canvas id="orientationsChart" style="max-height:220px;"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Donut Suivi étapes --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-tasks mr-2 text-warning"></i> Suivi du parcours
                                </h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <canvas id="stepsChart" style="max-height:220px;"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Donut Orientations
        new Chart(document.getElementById('orientationsChart'), {
            type: 'doughnut',
            data: {
                labels: @json($orientationsChart['labels']),
                datasets: [{
                    data: @json($orientationsChart['data']),
                    backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // 3. Donut Étapes
        new Chart(document.getElementById('stepsChart'), {
            type: 'doughnut',
            data: {
                labels: @json($stepsChart['labels']),
                datasets: [{
                    data: @json($stepsChart['data']),
                    backgroundColor: ['#1cc88a', '#f6c23e'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: @json($evolutionChart['labels']),
                datasets: [{
                    label: 'Candidats affectés',
                    data: @json($evolutionChart['data']),
                    borderColor: '#006b08',
                    backgroundColor: 'rgba(0, 107, 8, 0.08)',
                    pointBackgroundColor: '#006b08',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} candidat(s)`
                        }
                    }
                }
            }
        });
    </script>
    @include('section.foot')
</body>

</html>
