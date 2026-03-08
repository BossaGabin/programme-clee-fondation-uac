{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Dashboard Admin</title>
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
                        <h4 class="text-themecolor">Dashboard</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <small class="text-muted">{{ now()->format('d/m/Y') }}</small>
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
                {{-- ============================================ --}}
                {{-- STATISTIQUES PRINCIPALES --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="card" style="border-left: 4px solid #006b08;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1">Candidats</h6>
                                    <h3 class="mb-0 font-weight-bold">{{ $stats['total_candidats'] }}</h3>
                                </div>
                                <div
                                    style="background:#006b08; width:50px; height:50px; border-radius:50%;
                              display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-users text-white fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="border-left: 4px solid #f4a900;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1">Coachs</h6>
                                    <h3 class="mb-0 font-weight-bold">{{ $stats['total_coachs'] }}</h3>
                                </div>
                                <div
                                    style="background:#f4a900; width:50px; height:50px; border-radius:50%;
                              display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-chalkboard-teacher text-white fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="border-left: 4px solid #ffc107;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1">En attente</h6>
                                    <h3 class="mb-0 font-weight-bold">{{ $stats['demandes_pending'] }}</h3>
                                </div>
                                <div
                                    style="background:#ffc107; width:50px; height:50px; border-radius:50%;
                              display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-clock text-white fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="border-left: 4px solid #1cc88a;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1">Validées</h6>
                                    <h3 class="mb-0 font-weight-bold">{{ $stats['demandes_validated'] }}</h3>
                                </div>
                                <div
                                    style="background:#1cc88a; width:50px; height:50px; border-radius:50%;
                              display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-check text-white fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--                   
                  <div class="col-md-2">
                     <div class="card" style="border-left: 4px solid #e74a3b;">
                        <div class="card-body d-flex align-items-center justify-content-between">
                           <div>
                              <h6 class="text-muted mb-1">Rejetées</h6>
                              <h3 class="mb-0 font-weight-bold">{{ $stats['demandes_rejected'] }}</h3>
                           </div>
                           <div style="background:#e74a3b; width:50px; height:50px; border-radius:50%;
                              display:flex; align-items:center; justify-content:center;">
                              <i class="fas fa-times text-white fa-lg"></i>
                           </div>
                        </div>
                     </div>
                  </div>
                  --}}
                </div>


                {{-- ============================================ --}}
                {{-- CANDIDATS PAR BESOIN --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                                <h5 class="font-weight-bold">{{ $stats['par_besoin']['stage'] }}</h5>
                                <small class="text-muted">Stage</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                                <h5 class="font-weight-bold">{{ $stats['par_besoin']['insertion_emploi'] }}</h5>
                                <small class="text-muted">Insertion emploi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-store fa-2x text-warning mb-2"></i>
                                <h5 class="font-weight-bold">{{ $stats['par_besoin']['auto_emploi'] }}</h5>
                                <small class="text-muted">Auto-emploi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-graduation-cap fa-2x text-info mb-2"></i>
                                <h5 class="font-weight-bold">{{ $stats['par_besoin']['formation'] }}</h5>
                                <small class="text-muted">Formation</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- DEMANDES RÉCENTES --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clock text-warning mr-2"></i>
                                    Demandes en attente
                                </h5>
                                <a href="{{ route('admin.demandes.index') }}" class="btn btn-sm btn-outline-primary">
                                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                @if ($demandes_recentes->isEmpty())
                                    <p class="text-center text-muted py-3">Aucune demande en attente.</p>
                                @else
                                    <table class="table table-hover w-100" id="table-demandes">
                                        <thead>
                                            <tr>
                                                <th>Candidat</th>
                                                <th>Email</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($demandes_recentes as $demande)
                                                <tr class="data-row">
                                                    <td>
                                                        <div class="d-flex align-items-center" style="gap:10px;">
                                                            @if ($demande->candidat->avatar)
                                                                <img src="{{ Storage::url($demande->candidat->avatar) }}"
                                                                    style="width:35px;height:35px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                            @else
                                                                <div
                                                                    style="width:35px;height:35px;border-radius:50%;flex-shrink:0;
                                                    background:#006b08;display:flex;align-items:center;justify-content:center;">
                                                                    <i class="fas fa-user text-white"
                                                                        style="font-size:14px;"></i>
                                                                </div>
                                                            @endif
                                                            <span
                                                                class="font-weight-bold">{{ $demande->candidat->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $demande->candidat->email }}</td>
                                                    <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.demandes.show', $demande) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i> Traiter
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

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

                                            <table class="table table-hover w-100"
                                                id="admin-entretiens-{{ $key }}">
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
                                                            $orientation = match (true) {
                                                                $noteFinale <= 7 => [
                                                                    'label' => 'Formation',
                                                                    'class' => 'badge-danger',
                                                                ],
                                                                $noteFinale <= 11 => [
                                                                    'label' => 'Stage',
                                                                    'class' => 'badge-warning',
                                                                ],
                                                                $noteFinale <= 15 => [
                                                                    'label' => 'Insertion emploi',
                                                                    'class' => 'badge-primary',
                                                                ],
                                                                default => [
                                                                    'label' => 'Auto-emploi',
                                                                    'class' => 'badge-success',
                                                                ],
                                                            };
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
                                                                    style="color:#006b08;font-size:14px;">
                                                                    {{ $noteFinale }}/20
                                                                </span>
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ $interview->total_score }}/100</small>
                                                            </td>

                                                            <td>
                                                                <span class="badge {{ $orientation['class'] }}"
                                                                    style="font-size:11px;">
                                                                    {{ $orientation['label'] }}
                                                                </span>
                                                            </td>

                                                            <td>
                                                                <small>{{ \Carbon\Carbon::parse($interview->completed_at)->format('d/m/Y à H:i') }}</small>
                                                            </td>

                                                            <td>
                                                                <a href="{{ route('admin.candidats.show', $candidat) }}"
                                                                    class="btn btn-sm btn-outline-primary mr-1"
                                                                    title="Voir la fiche">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.candidats.rapport', $candidat) }}"
                                                                    class="btn btn-sm btn-outline-warning"
                                                                    title="Rapport entretien">
                                                                    <i class="fas fa-chart-bar"></i>
                                                                </a>
                                                                <a href="{{ route('admin.candidats.pdf', $candidat) }}"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    title="Exporter PDF" target="_blank">
                                                                    <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
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

                {{-- ══════════════════════════════════════ --}}
                {{-- GRAPHIQUES                            --}}
                {{-- ══════════════════════════════════════ --}}

                <div class="row page-titles">
                    <div class="col-md-12 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques & Graphiques
                        </h4>
                        {{-- <small class="text-muted">Vue d'ensemble de la plateforme CLEE</small> --}}
                    </div>
                </div>

                {{-- Ligne 1 : Orientations + Demandes --}}
                <div class="row mt-4">

                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-bullseye mr-2 text-success"></i> Répartition des orientations
                                </h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <canvas id="orientationsChart" style="max-height:250px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-file-alt mr-2 text-warning"></i> Statut des demandes
                                </h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <canvas id="demandesChart" style="max-height:250px;"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Ligne 2 : Candidats par coach --}}
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-users mr-2 text-primary"></i> Candidats par coach
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="candidatsParCoachChart" style="max-height:280px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ligne 3 : Évolution inscriptions --}}
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-chart-line mr-2 text-info"></i> Évolution des inscriptions
                                    candidats
                                </h6>
                                <div class="btn-group btn-group-sm">
                                    @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $label)
                                        <a href="{{ request()->fullUrlWithQuery(['periode' => $key]) }}"
                                            class="btn {{ $inscriptionsChart['periode'] === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                                            {{ $label }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="inscriptionsChart" style="max-height:280px;"></canvas>
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

        // 2. Donut Demandes
        new Chart(document.getElementById('demandesChart'), {
            type: 'doughnut',
            data: {
                labels: @json($demandesChart['labels']),
                datasets: [{
                    data: @json($demandesChart['data']),
                    backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b'],
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

        // 3. Bar Candidats par coach
        new Chart(document.getElementById('candidatsParCoachChart'), {
            type: 'bar',
            data: {
                labels: @json($candidatsParCoachChart['labels']),
                datasets: [{
                    label: 'Candidats actifs',
                    data: @json($candidatsParCoachChart['data']),
                    backgroundColor: '#006b08cc',
                    borderColor: '#006b08',
                    borderWidth: 1,
                    borderRadius: 4,
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
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // 4. Line Inscriptions
        new Chart(document.getElementById('inscriptionsChart'), {
            type: 'line',
            data: {
                labels: @json($inscriptionsChart['labels']),
                datasets: [{
                    label: 'Candidats inscrits',
                    data: @json($inscriptionsChart['data']),
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
    @include('section.footer')
    @include('section.foot')
</body>

</html>
