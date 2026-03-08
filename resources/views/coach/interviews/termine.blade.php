{{-- resources/views/coach/interviews/start.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Entretiens Terminés</title>
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

            </div>
        </div>
    </div>

    @include('section.foot')
</body>

</html>
