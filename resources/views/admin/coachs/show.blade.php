{{-- resources/views/admin/coachs/show.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Fiche coach</title>
@include('section.head')

<body class="v-light vertical-nav fix-header fix-sidebar">
    <div id="preloader">
        <div class="loader"><svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg></div>
    </div>
    <div id="main-wrapper">
        @include('section.header')
        @include('section.sidebar')

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor"><i class="fas fa-chalkboard-teacher mr-2"></i> Fiche coach</h4>
                    </div>
                    {{-- <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('admin.coachs.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
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
                    {{-- Colonne gauche --}}
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                @if ($coach->avatar)
                                    <img src="{{ Storage::url($coach->avatar) }}"
                                        style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #f4a900;"
                                        class="mb-3">
                                @else
                                    <div
                                        style="width:90px;height:90px;border-radius:50%;background:#f4a900;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif
                                <h5 class="font-weight-bold mb-1">{{ $coach->name }}</h5>
                                <p class="text-muted mb-1"><i class="fas fa-envelope mr-1"></i> {{ $coach->email }}</p>
                                <p class="text-muted mb-3"><i class="fas fa-phone mr-1"></i> {{ $coach->phone }}</p>
                                <span class="badge badge-warning" style="font-size:13px;padding:6px 12px;">
                                    <i class="fas fa-chalkboard-teacher mr-1"></i> Coach
                                </span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold"><i class="fas fa-id-card mr-2 text-warning"></i>
                                    Profil</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><small class="text-muted">Specialite</small></p>
                                <p class="font-weight-bold">{{ $coach->coachProfile?->speciality ?? 'Non renseignee' }}
                                </p>
                                @if ($coach->coachProfile?->bio)
                                    <p class="mb-1 mt-3"><small class="text-muted">Biographie</small></p>
                                    <p style="font-size:13px;color:#555;line-height:1.6;">
                                        {{ $coach->coachProfile->bio }}</p>
                                @endif
                                <hr>
                                <div class="text-center">
                                    <span
                                        style="font-size:28px;font-weight:bold;color:#f4a900;">{{ $coach->assignments->count() }}</span><br>
                                    <small class="text-muted">Candidat(s) affecte(s)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Colonne droite --}}
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-users mr-2 text-primary"></i> Candidats affectes
                                    ({{ $coach->assignments->count() }})
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($coach->assignments->isEmpty())
                                    <p class="text-center text-muted py-4">Aucun candidat affecte.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Candidat</th>
                                                    <th>Niveau etude</th>
                                                    <th>Besoin</th>
                                                    <th>Profil</th>
                                                    <th>Suivi</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($coach->assignments as $assignment)
                                                    @php $candidat = $assignment->candidat; @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if ($candidat->avatar)
                                                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                                                        style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                                                                @else
                                                                    <div
                                                                        style="width:36px;height:36px;border-radius:50%;background:#006b08;display:flex;align-items:center;justify-content:center;">
                                                                        <i class="fas fa-user text-white"
                                                                            style="font-size:13px;"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="mb-0 font-weight-bold">
                                                                        {{ $candidat->name }}</p>
                                                                    <small
                                                                        class="text-muted">{{ $candidat->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $candidat->candidatProfile?->niveau_etude ?? 'Non renseigne' }}
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
                                                                        'class' => 'badge-info',
                                                                    ],
                                                                ];
                                                                $type = $candidat->needAssignment?->type;
                                                            @endphp
                                                            @if ($type && isset($labels[$type]))
                                                                <span
                                                                    class="badge {{ $labels[$type]['class'] }}">{{ $labels[$type]['label'] }}</span>
                                                            @else
                                                                <span class="badge badge-secondary">Non defini</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                                                            <div class="d-flex align-items-center" style="gap:6px;">
                                                                <div class="progress flex-grow-1"
                                                                    style="height:6px;min-width:60px;">
                                                                    <div class="progress-bar @if ($completion < 50) bg-danger @elseif($completion < 100) bg-warning @else bg-success @endif"
                                                                        style="width:{{ $completion }}%"></div>
                                                                </div>
                                                                <small>{{ $completion }}%</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $stepsTotal = $candidat->followUpSteps->count();
                                                                $stepsCompleted = $candidat->followUpSteps
                                                                    ->where('status', 'completed')
                                                                    ->count();
                                                            @endphp
                                                            <small>{{ $stepsCompleted }}/{{ $stepsTotal }}
                                                                etapes</small>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.candidats.show', $candidat) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i>
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
            </div>
        </div>
    </div>
    @include('section.footer')
    @include('section.foot')
</body>

</html>
