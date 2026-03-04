{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

<!DOCTYPE html>
<html lang="en">

<title>CLEE - Tableau de bord</title>
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

        <!-- content body -->
        <div class="content-body">
            <div class="container">
                <div class="row page-titles">
                    <div class="col p-0">
                        <h4>Salut, <span>{{ Auth::user()->name }}</span></h4>
                    </div>
                    {{-- <div class="col-md-7 align-self-center text-right">
                        <strong class="text-muted">
                            {{ \Carbon\Carbon::now('Africa/Porto-Novo')->locale('fr')->isoFormat('dddd D MMMM YYYY HH[h]mm') }}
                        </strong>
                    </div> --}}
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button> <strong>Bravo!</strong> {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        @if ($candidat->avatar)
                                            <img src="{{ Storage::url($candidat->avatar) }}"
                                                style="width:55px; height:55px; border-radius:50%; object-fit:cover;">
                                        @endif
                                        <div>
                                            <h5 class="mb-0">{{ $candidat->name }}</h5>
                                            <small class="text-muted">{{ $candidat->email }}</small>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-weight-bold" style="font-size: 22px;">
                                            {{ $profile->profile_completion ?? 0 }}%
                                        </span>
                                        <br>
                                        <small class="text-muted">Profil complété</small>
                                    </div>
                                </div>

                                <div class="progress" style="height: 10px; border-radius: 10px;">
                                    <div class="progress-bar
                                @if (($profile->profile_completion ?? 0) < 50) bg-danger
                                @elseif(($profile->profile_completion ?? 0) < 100) bg-warning
                                @else bg-success @endif"
                                        role="progressbar"
                                        style="width: {{ $profile->profile_completion ?? 0 }}%; border-radius: 10px;"
                                        aria-valuenow="{{ $profile->profile_completion ?? 0 }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                @if (($profile->profile_completion ?? 0) < 100)
                                    <div class="mt-2 d-flex align-items-center justify-content-between">
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle"></i>
                                            Complétez votre profil à 100% pour faire une demande de diagnostic.
                                        </small>
                                        <a href="{{ route('candidat.profile.edit') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-user-edit"></i> Compléter mon profil
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle"></i>
                                            Profil complet. Vous pouvez faire une demande de diagnostic.
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    {{-- Statut de la demande de diagnostic --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clipboard-list text-primary mr-2"></i>
                                    Demande de diagnostic
                                </h5>

                            </div>
                            <div class="card-body text-center">
                                @if (!$demande)
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune demande effectuée.</p>
                                    @if (($profile->profile_completion ?? 0) >= 100)
                                        {{-- Bouton déclencheur --}}
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                            data-target="#modalDiagnostic">
                                            <i class="fas fa-paper-plane mr-1"></i> Faire une demande
                                        </button>

                                        {{-- Modal --}}
                                        <div class="modal fade" id="modalDiagnostic">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-clipboard-list mr-2 text-primary"></i>
                                                            Demande de diagnostic
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <form method="POST"
                                                        action="{{ route('candidat.diagnostic.store') }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p class="text-muted mb-3">
                                                                Avant de soumettre votre demande, décrivez votre
                                                                parcours
                                                                professionnel.
                                                                Ces informations aideront l'administration à vous
                                                                affecter le
                                                                coach le plus adapté.
                                                            </p>
                                                            <div class="form-group">
                                                                <label for="parcours_professionnel"
                                                                    class="font-weight-bold">
                                                                    Votre parcours professionnel
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <textarea id="parcours_professionnel" name="parcours_professionnel"
                                                                    class="form-control @error('parcours_professionnel') is-invalid @enderror" rows="15" style="min-height: 150px;"
                                                                    placeholder="Décrivez votre formation, vos expériences, vos compétences et vos objectifs professionnels...">
    {{ old('parcours_professionnel') }}
</textarea>
                                                                @error('parcours_professionnel')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                                <small class="text-muted">Minimum 50 caractères.</small>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">
                                                                <i class="fas fa-times mr-1"></i> Annuler
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-paper-plane mr-1"></i> Envoyer ma
                                                                demande
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <button class="btn btn-secondary btn-block" disabled>
                                            <i class="fas fa-lock"></i> Profil incomplet
                                        </button>
                                    @endif
                                @elseif($demande->status === 'pending')
                                    <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                                    <p class="font-weight-bold text-warning">En attente de validation</p>
                                    <small class="text-muted">Votre demande est en cours de traitement par
                                        l'administration.</small>
                                @elseif($demande->status === 'validated')
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <p class="font-weight-bold text-success">Demande validée</p>
                                    <small class="text-muted">Un coach vous a été assigné.</small>
                                @elseif($demande->status === 'rejected')
                                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                    <p class="font-weight-bold text-danger">Demande rejetée</p>
                                    @if ($demande->note_admin)
                                        <small class="text-muted">{{ $demande->note_admin }}</small>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Informations du coach assigné --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chalkboard-teacher text-info mr-2"></i>
                                    Mon Coach
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($candidat->candidatAssignment && $candidat->candidatAssignment->coach)
                                    @php $coach = $candidat->candidatAssignment->coach; @endphp
                                    @if ($coach->avatar)
                                        <img src="{{ Storage::url($coach->avatar) }}"
                                            style="width:65px; height:65px; border-radius:50%; object-fit:cover;"
                                            class="mb-3">
                                    @else
                                        <i class="fas fa-user-circle fa-4x text-info mb-3"></i>
                                    @endif
                                    <p class="font-weight-bold mb-0">{{ $coach->name }}</p>
                                    @if ($coach->coachProfile?->speciality)
                                        <small class="text-muted">{{ $coach->coachProfile->speciality }}</small>
                                    @endif
                                    <p class="mt-2 mb-0">
                                        <i class="fas fa-phone text-muted mr-1"></i>
                                        <small>{{ $coach->phone ?? 'Non renseigné' }}</small>
                                    </p>
                                @else
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun coach assigné pour le moment.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Besoin professionnel --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bullseye text-success mr-2"></i>
                                    Mon Besoin Professionnel
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($candidat->needAssignment)
                                    @php
                                        $besoin = $candidat->needAssignment;
                                        $labels = [
                                            'stage' => [
                                                'label' => 'Stage',
                                                'icon' => 'fas fa-briefcase',
                                                'color' => 'primary',
                                            ],
                                            'insertion_emploi' => [
                                                'label' => 'Insertion emploi',
                                                'icon' => 'fas fa-handshake',
                                                'color' => 'success',
                                            ],
                                            'auto_emploi' => [
                                                'label' => 'Auto-emploi',
                                                'icon' => 'fas fa-store',
                                                'color' => 'warning',
                                            ],
                                            'formation' => [
                                                'label' => 'Formation',
                                                'icon' => 'fas fa-graduation-cap',
                                                'color' => 'info',
                                            ],
                                        ];
                                        $info = $labels[$besoin->type] ?? [
                                            'label' => $besoin->type,
                                            'icon' => 'fas fa-question',
                                            'color' => 'secondary',
                                        ];
                                    @endphp
                                    <i class="{{ $info['icon'] }} fa-3x text-{{ $info['color'] }} mb-3"></i>
                                    <p class="font-weight-bold text-{{ $info['color'] }}">{{ $info['label'] }}</p>
                                    @if ($besoin->duration)
                                        <small class="text-muted">
                                            <i class="fas fa-clock mr-1"></i> Durée : {{ $besoin->duration }}
                                        </small>
                                    @endif
                                    @if ($besoin->program_start_date)
                                        <p class="mt-2 mb-0">
                                            <small class="text-muted">
                                                Du {{ $besoin->program_start_date->format('d/m/Y') }}
                                                @if ($besoin->program_end_date)
                                                    au {{ $besoin->program_end_date->format('d/m/Y') }}
                                                @endif
                                            </small>
                                        </p>
                                    @endif
                                @else
                                    <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Besoin non encore défini par votre coach.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                {{-- ===== BLOC ENTRETIEN ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-handshake text-warning mr-2"></i> Mon Entretien
                                </h5>
                            </div>
                            <div class="card-body">

                                @if (!$entretien && !$interview)
                                    <div class="text-center py-3">
                                        <i class="fas fa-calendar fa-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted mb-0">Aucun entretien programmé pour le moment.</p>
                                    </div>
                                @elseif($entretien && !$interview)
                                    {{-- Entretien programmé mais pas encore passé --}}
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div
                                                style="background:#f0f9f0; border-left:4px solid #006b08;
                                        border-radius:4px; padding:15px 20px;">
                                                <p class="mb-2 font-weight-bold">
                                                    <i class="fas fa-calendar-check text-success mr-2"></i>
                                                    Entretien programmé
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-calendar mr-2 text-muted"></i>
                                                    <strong>{{ \Carbon\Carbon::parse($entretien->scheduled_date)->format('d/m/Y') }}</strong>
                                                    à
                                                    <strong>{{ \Carbon\Carbon::parse($entretien->scheduled_time)->format('H:i') }}</strong>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-map-marker-alt mr-2 text-muted"></i>
                                                    @if ($entretien->mode === 'presentiel')
                                                        <span class="badge badge-primary mr-1">Présentiel</span>
                                                        {{ $entretien->location }}
                                                    @else
                                                        <span class="badge badge-info mr-1">En ligne</span>
                                                        @if ($entretien->meeting_link)
                                                            <a href="{{ $entretien->meeting_link }}" target="_blank"
                                                                class="btn btn-sm btn-outline-info ml-1">
                                                                <i class="fas fa-external-link-alt mr-1"></i> Rejoindre
                                                            </a>
                                                        @endif
                                                    @endif
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-chalkboard-teacher mr-2 text-muted"></i>
                                                    Avec Coach
                                                    <strong>{{ $candidat->candidatAssignment?->coach?->name ?? 'votre coach' }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            @php
                                                $jours = \Carbon\Carbon::now()->diffInDays(
                                                    \Carbon\Carbon::parse($entretien->scheduled_date),
                                                    false,
                                                );
                                            @endphp
                                            @if ($jours >= 0)
                                                <div style="font-size:36px; font-weight:bold; color:#006b08;">
                                                    @if ($jours === 0)
                                                        {{ \Carbon\Carbon::parse($entretien->date_heure)->format('H:i') }}
                                                    @else
                                                        J-{{ $jours }}
                                                    @endif
                                                </div>
                                                <small class="text-muted">
                                                    @if ($jours === 0)
                                                        Entretien prévu aujourd'hui
                                                    @else
                                                        avant l'entretien
                                                    @endif
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- @elseif($interview) --}}
                                    {{-- Entretien passé avec notes --}}
                                    {{-- <div class="row">
                                        <div class="col-md-3 text-center">
                                            @php
                                                $scoreColor =
                                                    $interview->total_score >= 60
                                                        ? '#1cc88a'
                                                        : ($interview->total_score >= 40
                                                            ? '#f6c23e'
                                                            : '#e74a3b');
                                                $scoreLabel =
                                                    $interview->total_score >= 60
                                                        ? 'Bien'
                                                        : ($interview->total_score >= 40
                                                            ? 'Moyen'
                                                            : 'À améliorer');
                                            @endphp
                                            <div
                                                style="width:90px; height:90px; border-radius:50%; margin:0 auto;
                                        background:{{ $scoreColor }}; display:flex;
                                        align-items:center; justify-content:center;">
                                                <span style="color:#fff; font-size:20px; font-weight:bold;">
                                                    {{ $interview->total_score }}/100
                                                </span>
                                            </div>
                                            <p class="mt-2 mb-0 font-weight-bold" style="color:{{ $scoreColor }};">
                                                {{ $scoreLabel }}
                                            </p>
                                            <small class="text-muted">Score global</small>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Compétence</th>
                                                            <th>Note</th>
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

                                            @if ($interview->strengths || $interview->weaknesses)
                                                <div class="row mt-3">
                                                    @if ($interview->strengths)
                                                        <div class="col-md-6">
                                                            <small class="font-weight-bold text-success">
                                                                <i class="fas fa-plus-circle mr-1"></i> Points forts
                                                            </small>
                                                            <p class="text-muted mt-1" style="font-size:12px;">
                                                                {{ $interview->strengths }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    @if ($interview->weaknesses)
                                                        <div class="col-md-6">
                                                            <small class="font-weight-bold text-danger">
                                                                <i class="fas fa-minus-circle mr-1"></i> Points à
                                                                améliorer
                                                            </small>
                                                            <p class="text-muted mt-1" style="font-size:12px;">
                                                                {{ $interview->weaknesses }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div> --}}
                                @elseif($interview)
                                    @php
                                        $noteFinale = round($interview->total_score / 5);
                                        $scoreColor =
                                            $noteFinale >= 16
                                                ? '#1cc88a'
                                                : ($noteFinale >= 12
                                                    ? '#36b9cc'
                                                    : ($noteFinale >= 8
                                                        ? '#f6c23e'
                                                        : '#e74a3b'));
                                        $scoreLabel =
                                            $noteFinale >= 16
                                                ? 'Excellent'
                                                : ($noteFinale >= 12
                                                    ? 'Bien'
                                                    : ($noteFinale >= 8
                                                        ? 'Moyen'
                                                        : 'À améliorer'));
                                        $orientation = match (true) {
                                            $noteFinale <= 7 => 'Renforcement compétences',
                                            $noteFinale <= 11 => 'Stage / immersion',
                                            $noteFinale <= 15 => 'Insertion accompagnée',
                                            default => 'Insertion rapide / autonomie',
                                        };
                                        $blocColors = ['#006b08', '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'];
                                    @endphp

                                    <div class="row align-items-center">

                                        {{-- Cercle score --}}
                                        <div class="col-md-3 text-center">
                                            <div
                                                style="width:100px; height:100px; border-radius:50%; margin:0 auto;   background:{{ $scoreColor }}; display:flex; flex-direction:column;  align-items:center; justify-content:center;">
                                                <span
                                                    style="color:#fff; font-size:22px; font-weight:bold; line-height:1.1;">
                                                    {{ $noteFinale }}/20
                                                </span>
                                                <small style="color:rgba(255,255,255,0.85); font-size:10px;">
                                                    {{ $interview->total_score }}/100
                                                </small>
                                            </div>
                                            <p class="mt-2 mb-0 font-weight-bold" style="color:{{ $scoreColor }};">
                                                {{ $scoreLabel }}
                                            </p>
                                            <small class="text-muted d-block">Score global</small>
                                            <div class="mt-3 p-2 rounded text-center"
                                                style="background:{{ $scoreColor }}18; border:1px solid {{ $scoreColor }}; font-size:11px;">
                                                <i class="fas fa-compass mr-1"
                                                    style="color:{{ $scoreColor }};"></i>
                                                <strong>Orientation :</strong><br>
                                                <span
                                                    style="color:{{ $scoreColor }}; font-weight:bold;">{{ $orientation }}</span>
                                            </div>
                                        </div>

                                        {{-- Barres par bloc --}}
                                        <div class="col-md-9">
                                            @foreach ($interview->scores->sortBy('competence.order') as $index => $score)
                                                @php
                                                    $color = $blocColors[$index] ?? '#006b08';
                                                    $pct = ($score->note / 20) * 100;
                                                @endphp
                                                <div class="mb-3">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-1">
                                                        <small class="font-weight-bold" style="font-size:12px;">
                                                            {{ $score->competence->name }}
                                                        </small>
                                                        <small class="font-weight-bold"
                                                            style="color:{{ $color }};">
                                                            {{ $score->note }}/20
                                                            ({{ round($pct) }}%)
                                                        </small>
                                                    </div>
                                                    <div class="progress"
                                                        style="height:12px; border-radius:8px; background:#e9ecef;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width:{{ $pct }}%; background:{{ $color }}; border-radius:8px; transition:width 1s ease;">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Total général</small>
                                                <strong>{{ $interview->total_score }}/100</strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted">Note finale (/20)</small>
                                                <strong
                                                    style="font-size:18px; color:{{ $scoreColor }};">{{ $noteFinale }}/20</strong>
                                            </div>

                                            @if ($interview->strengths || $interview->weaknesses)
                                                <div class="row mt-3">
                                                    @if ($interview->strengths)
                                                        <div class="col-md-6">
                                                            <small class="font-weight-bold text-success">
                                                                <i class="fas fa-plus-circle mr-1"></i> Points forts
                                                            </small>
                                                            <p class="text-muted mt-1"
                                                                style="font-size:11px; line-height:1.5;">
                                                                {{ $interview->strengths }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    @if ($interview->weaknesses)
                                                        <div class="col-md-6">
                                                            <small class="font-weight-bold text-danger">
                                                                <i class="fas fa-minus-circle mr-1"></i> Points à
                                                                améliorer
                                                            </small>
                                                            <p class="text-muted mt-1"
                                                                style="font-size:11px; line-height:1.5;">
                                                                {{ $interview->weaknesses }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line text-primary mr-2"></i>
                                    Mon Parcours
                                </h5>
                                <small class="text-muted">
                                    {{ $steps->where('status', 'completed')->count() }} /
                                    {{ $steps->count() }} étapes terminées
                                </small>
                            </div>
                            <div class="card-body">
                                @if ($steps->isEmpty())
                                    <div class="text-center py-4">
                                        <i class="fas fa-road fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Votre parcours de suivi n'a pas encore commencé.</p>
                                    </div>
                                @else
                                    <div class="timeline">
                                        @foreach ($steps as $step)
                                            <div class="timeline-item d-flex mb-4">

                                                {{-- Icône statut --}}
                                                <div class="timeline-icon mr-3" style="flex-shrink:0;">
                                                    @if ($step->status === 'completed')
                                                        <div
                                                            style="width:40px; height:40px; border-radius:50%; background:#1cc88a; display:flex; align-items:center; justify-content:center;">
                                                            <i class="fas fa-check text-white"></i>
                                                        </div>
                                                    @else
                                                        <div
                                                            style="width:40px; height:40px; border-radius:50%; background:#4e73df; display:flex; align-items:center; justify-content:center;">
                                                            <i class="fas fa-spinner text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Contenu --}}
                                                <div class="timeline-content flex-grow-1">
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
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- #/ container -->
    </div>
    <!-- #/ content body -->
    <!-- footer -->

    <!-- #/ footer -->
    </div>
    @include('section.footer')
    @include('section.foot')
    {{-- Rouvrir le modal si erreur de validation --}}
    @if ($errors->has('parcours_professionnel'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#modalDiagnostic').modal('show');
            });
        </script>
    @endif

</body>

</html>
