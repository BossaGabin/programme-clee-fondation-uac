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
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
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
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if ($demande->candidat->avatar)
                                                                    <img src="{{ Storage::url($demande->candidat->avatar) }}"
                                                                        style="width:35px; height:35px; border-radius:50%; object-fit:cover;">
                                                                @else
                                                                    <div
                                                                        style="width:35px; height:35px; border-radius:50%; background:#006b08;
                                                display:flex; align-items:center; justify-content:center;">
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
                                    </div>
                                @endif
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
