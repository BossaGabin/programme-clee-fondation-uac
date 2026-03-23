{{-- resources/views/coach/interviews/report.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Rapport d'entretien</title>
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
        @php
            $candidat = $interview->appointment->coachAssignment->candidat;
            $noteFinale = round($interview->total_score / 5);
            $scoreColor =
                $noteFinale >= 16
                    ? '#1cc88a'
                    : ($noteFinale >= 12
                        ? '#36b9cc'
                        : ($noteFinale >= 8
                            ? '#f6c23e'
                            : '#e74a3b'));
            $orientation = match (true) {
                $noteFinale <= 9 => 'Renforcement compétences (formation de base)',
                $noteFinale <= 14 => 'Stage / immersion professionnelle',
                $noteFinale <= 17 => 'Insertion emploi accompagnée(Insertion à l\'emploi)',
                default => 'Insertion rapide / autonomie (Auto emploi)',
            };
            $blocColors = [
                1 => '#006b08',
                2 => '#4e73df',
                3 => '#1cc88a',
                4 => '#f6c23e',
                5 => '#e74a3b',
            ];
        @endphp

        <div class="content-body">
            <div class="container-fluid">

                <div class="row mb-3">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-file-alt mr-2"></i> Rapport d'entretien
                        </h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                            class="btn btn-sm btn-outline-secondary mr-2">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                        @if (Auth::user()->role == 'coach')
                            <a href="{{ route('coach.projects.create', $candidat) }}"
                                class="btn btn-sm btn-success mr-1">
                                <i class="fas fa-plus mr-1"></i> Créer le projet professionnel
                            </a>
                            <a href="{{ route('coach.interviews.pdf', $interview) }}" class="btn btn-sm btn-danger"
                                target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Exporter PDF
                            </a>
                        @endif
                        @if (Auth::user()->role == 'admin')
                            <a href="{{ route('admin.interviews.pdf', $interview) }}" class="btn btn-sm btn-danger"
                                target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Exporter PDF
                            </a>
                        @endif

                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button> <strong>Bravo!</strong> {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    {{-- Score global --}}
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                @if ($candidat->avatar)
                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                        style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid #006b08;"
                                        class="mb-2">
                                @endif
                                <h5 class="font-weight-bold">{{ $candidat->name }}</h5>
                                <small class="text-muted d-block mb-3">
                                    {{ $interview->completed_at?->format('d/m/Y à H:i') }}
                                </small>

                                <div
                                    style="width:110px;height:110px;border-radius:50%;margin:0 auto 15px;
                                            background:{{ $scoreColor }};display:flex;flex-direction:column;
                                            align-items:center;justify-content:center;">
                                    <span style="color:#fff;font-size:26px;font-weight:bold;line-height:1;">
                                        {{ $noteFinale }}/20
                                    </span>
                                    <small style="color:rgba(255,255,255,0.85);font-size:11px;">
                                        {{ $interview->total_score }}/100
                                    </small>
                                </div>

                                <div
                                    class="alert {{ $noteFinale >= 16 ? 'alert-success' : ($noteFinale >= 12 ? 'alert-info' : ($noteFinale >= 8 ? 'alert-warning' : 'alert-danger')) }}">
                                    <strong><i class="fas fa-compass mr-1"></i> Orientation :</strong><br>
                                    {{ $orientation }}
                                </div>
                                @if (Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.interviews.pdf', $interview) }}"
                                        class="btn btn-sm btn-danger" target="_blank">
                                        <i class="fas fa-file-pdf mr-1"></i> Exporter PDF
                                    </a>
                                @endif
                                @if (Auth::user()->role == 'coach')

                                    <a href="{{ route('coach.interviews.pdf', $interview) }}" class="btn btn-sm btn-danger"
                                        target="_blank">
                                        <i class="fas fa-file-pdf mr-1"></i> Exporter PDF
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Synthèse --}}
                        @if ($interview->coach_summary)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-comment-dots mr-2 text-info"></i> Synthèse
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p style="font-size:13px; line-height:1.7; color:#555;">
                                        {{ $interview->coach_summary }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Détail par bloc --}}
                    <div class="col-md-8">

                        {{-- Graphique barres --}}
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-chart-bar mr-2 text-primary"></i> Scores par bloc
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach ($interview->scores->sortBy('competence.order') as $score)
                                    @php
                                        $color = $blocColors[$score->competence->order] ?? '#006b08';
                                        $pct = ($score->note / 20) * 100;
                                    @endphp
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="font-weight-bold">{{ $score->competence->name }}</small>
                                            <small class="font-weight-bold">{{ $score->note }}/20</small>
                                        </div>
                                        <div class="progress" style="height:18px;border-radius:8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width:{{ $pct }}%; background:{{ $color }}; border-radius:8px;">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Total général</strong>
                                    <strong style="font-size:18px;">{{ $interview->total_score }}/100</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <strong>Note finale (/20)</strong>
                                    <strong
                                        style="font-size:22px; color:{{ $scoreColor }};">{{ $noteFinale }}/20</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Points forts / faibles --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card" style="border-left:4px solid #1cc88a;">
                                    <div class="card-header">
                                        <h6 class="mb-0 font-weight-bold text-success">
                                            <i class="fas fa-plus-circle mr-1"></i> Points forts
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p style="font-size:13px; color:#555; line-height:1.7;">
                                            {{ $interview->strengths }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card" style="border-left:4px solid #e74a3b;">
                                    <div class="card-header">
                                        <h6 class="mb-0 font-weight-bold text-danger">
                                            <i class="fas fa-minus-circle mr-1"></i> Points à améliorer
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p style="font-size:13px; color:#555; line-height:1.7;">
                                            {{ $interview->weaknesses }}
                                        </p>
                                    </div>
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
