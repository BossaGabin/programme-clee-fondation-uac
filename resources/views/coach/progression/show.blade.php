{{-- resources/views/coach/progression/show.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Progression du candidat</title>
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
                    <div class="col-md-8 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-chart-line mr-2"></i> Progression de
                            <strong>{{ $assignment->candidat->name }}</strong>
                        </h4>
                    </div>
                    
                    <div class="col-md-4 align-self-center text-right">
                        @php $tousA20 = collect($scores['current'])->every(fn($v) => $v === 20); @endphp
                        @if (!$tousA20 && auth()->id() === $assignment->coach_id)
                            <a href="{{ route('coach.progression.create', $assignment) }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> Nouvelle mise à jour
                            </a>
                        @endif
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <strong>Bravo!</strong> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($tousA20)
                    <div class="alert alert-success">
                        <i class="fas fa-trophy mr-2"></i>
                        <strong>{{ $assignment->candidat->name }}</strong> a validé les 5 blocs à
                        <strong>20/20</strong>. Progression complète !
                    </div>
                @endif

                @php
                    $blocColors = [
                        1 => '#006b08',
                        2 => '#4e73df',
                        3 => '#1cc88a',
                        4 => '#f6c23e',
                        5 => '#e74a3b',
                    ];
                    $blocs = [
                        'bloc_a' => [
                            'label' => 'Bloc A — Clarté du projet professionnel',
                            'color' => '#006b08',
                            'icon' => 'fa-bullseye',
                            'order' => 1,
                        ],
                        'bloc_b' => [
                            'label' => 'Bloc B — Motivation & engagement',
                            'color' => '#4e73df',
                            'icon' => 'fa-fire',
                            'order' => 2,
                        ],
                        'bloc_c' => [
                            'label' => 'Bloc C — Compétences & savoir-faire',
                            'color' => '#1cc88a',
                            'icon' => 'fa-tools',
                            'order' => 3,
                        ],
                        'bloc_d' => [
                            'label' => 'Bloc D — Soft skills & posture professionnelle',
                            'color' => '#f6c23e',
                            'icon' => 'fa-user-tie',
                            'order' => 4,
                        ],
                        'bloc_e' => [
                            'label' => 'Bloc E — Autonomie & préparation à l\'insertion',
                            'color' => '#e74a3b',
                            'icon' => 'fa-rocket',
                            'order' => 5,
                        ],
                    ];

                    $interview = $scores['interview'];
                    $noteFinale = $interview ? round($interview->total_score / 5) : 0;
                    $scoreColor =
                        $noteFinale <= 9
                            ? '#e74a3b'
                            : ($noteFinale <= 14
                                ? '#f6c23e'
                                : ($noteFinale <= 17
                                    ? '#17a2b8'
                                    : '#1cc88a'));
                @endphp

                {{-- ============================================ --}}
                {{-- INFO CANDIDAT                               --}}
                {{-- ============================================ --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card" style="border-left:4px solid #006b08;">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center" style="gap:14px;">
                                        @if ($assignment->candidat->avatar)
                                            <img src="{{ Storage::url($assignment->candidat->avatar) }}"
                                                style="width:50px;height:50px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                        @else
                                            <div
                                                style="width:50px;height:50px;border-radius:50%;background:#006b08;
                                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="fas fa-user text-white fa-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="mb-0 font-weight-bold">{{ $assignment->candidat->name }}</h5>
                                            <small class="text-muted">{{ $assignment->candidat->email }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex" style="gap:30px;">
                                        <div class="text-center">
                                            <small class="text-muted d-block">Score initial</small>
                                            <span class="font-weight-bold" style="font-size:20px;">
                                                {{ $scores['total_initial'] }}/100
                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Score actuel</small>
                                            <span class="font-weight-bold text-success" style="font-size:20px;">
                                                {{ $scores['total_current'] }}/100
                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Progression</small>
                                            @php $prog = $scores['total_progression']; @endphp
                                            <span
                                                class="font-weight-bold {{ $prog > 0 ? 'text-success' : 'text-muted' }}"
                                                style="font-size:20px;">
                                                {{ $prog > 0 ? '+' : '' }}{{ $prog }} pts
                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Séances</small>
                                            <span class="badge badge-primary" style="font-size:16px; padding:6px 12px;">
                                                {{ $updates->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- DEUX COLONNES CÔTE À CÔTE                   --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- En-têtes --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="font-weight-bold mb-0">
                                            <i class="fas fa-clipboard-check mr-2 text-secondary"></i>
                                            Entretien de diagnostic — Scores initiaux
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="font-weight-bold mb-0">
                                            <i class="fas fa-chart-line mr-2 text-success"></i>
                                            Progression — Scores actuels
                                        </h6>
                                    </div>
                                </div>

                                <hr class="mt-0">

                                @if($interview)
                                    @foreach($interview->scores->sortBy('competence.order') as $score)
                                        @php
                                            $order      = $score->competence->order;
                                            $color      = $blocColors[$order] ?? '#006b08';
                                            $pctInitial = ($score->note / 20) * 100;

                                            // Correspondance order → clé bloc
                                            $blocKey    = ['1'=>'bloc_a','2'=>'bloc_b','3'=>'bloc_c','4'=>'bloc_d','5'=>'bloc_e'][$order] ?? null;
                                            $current    = $blocKey ? $scores['current'][$blocKey]    : $score->note;
                                            $diff       = $blocKey ? $scores['progression'][$blocKey] : 0;
                                            $pctCurrent = ($current / 20) * 100;
                                            $valide     = $current === 20;
                                            $bloc       = $blocKey ? $blocs[$blocKey] : null;
                                        @endphp

                                        <div class="row align-items-center mb-4">

                                            {{-- GAUCHE : barre initiale --}}
                                            <div class="col-md-6" style="border-right:1px dashed #dee2e6; padding-right:20px;">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small class="font-weight-bold">{{ $score->competence->name }}</small>
                                                    <small class="font-weight-bold">{{ $score->note }}/20</small>
                                                </div>
                                                <div class="progress" style="height:18px; border-radius:8px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width:{{ $pctInitial }}%; background:{{ $color }}; border-radius:8px;">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- DROITE : barre progression --}}
                                            <div class="col-md-6" style="padding-left:20px;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="font-weight-bold">
                                                        @if($bloc)
                                                            <i class="fas {{ $bloc['icon'] }} mr-1" style="color:{{ $color }}"></i>
                                                        @endif
                                                        {{ $score->competence->name }}
                                                        @if($valide)
                                                            <span class="badge badge-success ml-1">
                                                                <i class="fas fa-check"></i> Validé
                                                            </span>
                                                        @endif
                                                    </small>
                                                    @if($diff > 0)
                                                        <span class="badge badge-success">+{{ $diff }} pts</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inchangé</span>
                                                    @endif
                                                </div>

                                                {{-- Barre superposée --}}
                                                <div style="position:relative; height:18px; background:#e9ecef;
                                                    border-radius:8px; overflow:hidden;">

                                                    {{-- Gris = initial --}}
                                                    <div style="position:absolute; top:0; left:0; height:100%;
                                                        width:{{ $pctInitial }}%;
                                                        background:#ced4da;
                                                        border-radius:8px 0 0 8px;
                                                        z-index:1;">
                                                    </div>

                                                    {{-- Couleur = actuel --}}
                                                    <div class="bar-current-{{ $blocKey }}"
                                                        style="position:absolute; top:0; left:0; height:100%;
                                                        width:0%;
                                                        background:{{ $color }};
                                                        border-radius:8px;
                                                        z-index:2;
                                                        transition:width 1.2s ease;
                                                        opacity:0.9;">
                                                    </div>

                                                    {{-- Label --}}
                                                    <div style="position:absolute; top:0; left:0; width:100%; height:100%;
                                                        display:flex; align-items:center; justify-content:center; z-index:3;">
                                                        <small style="font-size:11px; font-weight:bold; color:#fff;
                                                            text-shadow:0 0 3px rgba(0,0,0,0.5);">
                                                            {{ $current }}/20
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-1">
                                                    <small class="text-muted" style="font-size:11px;">
                                                        Départ : {{ $score->note }}/20
                                                    </small>
                                                    <small style="font-size:11px; color:{{ $color }}; font-weight:bold;">
                                                        Actuel : {{ $current }}/20
                                                    </small>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach

                                    <hr>

                                    {{-- Totaux --}}
                                    <div class="row">
                                        <div class="col-md-6" style="border-right:1px dashed #dee2e6; padding-right:20px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Total général</strong>
                                                <strong style="font-size:18px;">{{ $interview->total_score }}/100</strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <strong>Note finale</strong>
                                                <strong style="font-size:22px; color:{{ $scoreColor }};">
                                                    {{ $noteFinale }}/20
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="padding-left:20px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Total actuel</strong>
                                                <strong style="font-size:18px; color:#006b08;">
                                                    {{ $scores['total_current'] }}/100
                                                </strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <strong>Progression globale</strong>
                                                @php $prog = $scores['total_progression']; @endphp
                                                <strong style="font-size:18px;"
                                                    class="{{ $prog > 0 ? 'text-success' : 'text-muted' }}">
                                                    {{ $prog > 0 ? '+' : '' }}{{ $prog }} pts
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Points forts / faibles --}}
                                    @if($interview->strengths || $interview->weaknesses)
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if($interview->strengths)
                                                    <small class="font-weight-bold text-success">
                                                        <i class="fas fa-plus-circle mr-1"></i> Points forts
                                                    </small>
                                                    <p class="text-muted mb-0" style="font-size:13px;">
                                                        {{ $interview->strengths }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if($interview->weaknesses)
                                                    <small class="font-weight-bold text-danger">
                                                        <i class="fas fa-minus-circle mr-1"></i> Points faibles
                                                    </small>
                                                    <p class="text-muted mb-0" style="font-size:13px;">
                                                        {{ $interview->weaknesses }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-clipboard fa-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted">Aucun entretien de diagnostic enregistré.</p>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- GRAPHIQUE RADAR + HISTORIQUE               --}}
                {{-- ============================================ --}}
                <div class="row mt-3">

                    {{-- Historique --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history mr-2 text-warning"></i>
                                    Historique des séances
                                    @if ($updates->isNotEmpty())
                                        <span class="badge badge-warning ml-2">{{ $updates->count() }}</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="card-body p-0" style="max-height:380px; overflow-y:auto;">
                                @if ($updates->isNotEmpty())
                                    <ul class="list-group list-group-flush">
                                        @foreach ($updates->sortByDesc('created_at') as $update)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="font-weight-bold">
                                                        <i class="fas fa-calendar-check mr-1 text-success"></i>
                                                        {{ $update->created_at->format('d/m/Y à H:i') }}
                                                    </span>
                                                    <span class="badge badge-light border">
                                                        Séance #{{ $loop->remaining + 1 }}
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-wrap mb-2" style="gap:6px;">
                                                    @foreach (['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E'] as $k => $l)
                                                        @php $val = $update->{'bloc_'.$k}; @endphp
                                                        @if (!is_null($val))
                                                            <span class="badge"
                                                                style="background:{{ $blocs['bloc_' . $k]['color'] }};
                                                                color:#fff; font-size:12px; padding:5px 10px;">
                                                                Bloc {{ $l }} : {{ $val }}/20
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <p class="mb-0 text-muted" style="font-size:13px;">
                                                    <i class="fas fa-comment mr-1"></i>
                                                    {{ $update->note_seance }}
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-history fa-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted mb-0">Aucune séance de suivi enregistrée.</p>
                                        <a href="{{ route('coach.progression.create', $assignment) }}"
                                            class="btn btn-sm btn-primary mt-3">
                                            <i class="fas fa-plus mr-1"></i> Ajouter une séance
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    @include('section.footer')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const scoresInitiaux = @json(array_values($scores['initial']));
        const scoresActuels = @json(array_values($scores['current']));
        const pctActuels = {
            bloc_a: {{ ($scores['current']['bloc_a'] / 20) * 100 }},
            bloc_b: {{ ($scores['current']['bloc_b'] / 20) * 100 }},
            bloc_c: {{ ($scores['current']['bloc_c'] / 20) * 100 }},
            bloc_d: {{ ($scores['current']['bloc_d'] / 20) * 100 }},
            bloc_e: {{ ($scores['current']['bloc_e'] / 20) * 100 }},
        };

        // Animation barres
        window.addEventListener('load', function() {
            setTimeout(() => {
                ['bloc_a', 'bloc_b', 'bloc_c', 'bloc_d', 'bloc_e'].forEach(bloc => {
                    const bar = document.querySelector(`.bar-current-${bloc}`);
                    if (bar) bar.style.width = pctActuels[bloc] + '%';
                });
            }, 300);
        });

        // Radar
        const ctx = document.getElementById('radarChart').getContext('2d');
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Bloc A', 'Bloc B', 'Bloc C', 'Bloc D', 'Bloc E'],
                datasets: [{
                        label: 'Initial',
                        data: scoresInitiaux,
                        backgroundColor: 'rgba(206,212,218,0.3)',
                        borderColor: '#ced4da',
                        borderWidth: 2,
                        pointBackgroundColor: '#ced4da',
                    },
                    {
                        label: 'Actuel',
                        data: scoresActuels,
                        backgroundColor: 'rgba(0,107,8,0.15)',
                        borderColor: '#006b08',
                        borderWidth: 2,
                        pointBackgroundColor: '#006b08',
                    }
                ]
            },
            options: {
                scales: {
                    r: {
                        min: 0,
                        max: 20,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @include('section.foot')
</body>

</html>
