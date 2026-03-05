{{-- resources/views/admin/candidats/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Liste des candidats</title>
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
                            <i class="fas fa-users mr-2"></i> Liste des candidats
                        </h4>
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

                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Filtres --}}
                                <form method="GET" action="{{ route('admin.candidats.index') }}" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control"
                                                    placeholder="Rechercher par nom ou email..."
                                                    value="{{ request('search') }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <select name="statut" class="form-control" onchange="this.form.submit()">
                                                <option value="">-- Tous les besoins --</option>
                                                <option value="stage"
                                                    {{ request('statut') === 'stage' ? 'selected' : '' }}>
                                                    Stage</option>
                                                <option value="insertion_emploi"
                                                    {{ request('statut') === 'insertion_emploi' ? 'selected' : '' }}>
                                                    Insertion emploi</option>
                                                <option value="auto_emploi"
                                                    {{ request('statut') === 'auto_emploi' ? 'selected' : '' }}>
                                                    Auto-emploi</option>
                                                <option value="formation"
                                                    {{ request('statut') === 'formation' ? 'selected' : '' }}>
                                                    Formation</option>
                                            </select> --}}
                                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus mr-1"></i> Créer un candidat
                                            </a>
                                        </div>
                                        @if (request('search') || request('statut'))
                                            <div class="col-md-2">
                                                <a href="{{ route('admin.candidats.index') }}"
                                                    class="btn btn-outline-secondary btn-block">
                                                    <i class="fas fa-times mr-1"></i> Réinitialiser
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </form>

                                {{-- Compteur --}}
                                <p class="text-muted mb-3">
                                    <strong>{{ $candidats->total() }}</strong> candidat(s) trouvé(s)
                                </p>

                                {{-- Tableau --}}
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Candidat</th>
                                                <th>Téléphone</th>
                                                <th>Niveau d'étude</th>
                                                <th>Coach assigné</th>
                                                <th>Besoin</th>
                                                {{-- <th>Profil</th> --}}
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($candidats as $candidat)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center" style="gap:10px;">
                                                            @if ($candidat->avatar)
                                                                <img src="{{ Storage::url($candidat->avatar) }}"
                                                                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                                                            @else
                                                                <div
                                                                    style="width:40px; height:40px; border-radius:50%;
                                                                            background:#006b08; display:flex;
                                                                            align-items:center; justify-content:center;">
                                                                    <i class="fas fa-user text-white"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="mb-0 font-weight-bold">{{ $candidat->name }}
                                                                </p>
                                                                <small class="text-muted">{{ $candidat->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $candidat->phone }}</td>
                                                    <td>{{ $candidat->candidatProfile?->niveau_etude ?? '—' }}</td>
                                                    <td>
                                                        @if ($candidat->candidatAssignment?->coach)
                                                            <div class="d-flex align-items-center" style="gap:8px;">
                                                                @if ($candidat->candidatAssignment->coach->avatar)
                                                                    <img src="{{ Storage::url($candidat->candidatAssignment->coach->avatar) }}"
                                                                        style="width:28px; height:28px; border-radius:50%; object-fit:cover;">
                                                                @else
                                                                    <div
                                                                        style="width:28px; height:28px; border-radius:50%;
                                                                                background:#f4a900; display:flex;
                                                                                align-items:center; justify-content:center;">
                                                                        <i class="fas fa-user text-white"
                                                                            style="font-size:11px;"></i>
                                                                    </div>
                                                                @endif
                                                                <small class="font-weight-bold">
                                                                    {{ $candidat->candidatAssignment->coach->name }}
                                                                </small>
                                                            </div>
                                                        @else
                                                            <span class="badge badge-secondary">Non affecté</span>
                                                        @endif
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
                                                            <span class="badge {{ $labels[$type]['class'] }}">
                                                                {{ $labels[$type]['label'] }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">—</span>
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                                                        <div class="d-flex align-items-center" style="gap:8px;">
                                                            <div class="progress flex-grow-1"
                                                                style="height:6px; border-radius:6px; min-width:70px;">
                                                                <div class="progress-bar
                                                                    @if ($completion < 50) bg-danger
                                                                    @elseif($completion < 100) bg-warning
                                                                    @else bg-success @endif"
                                                                    style="width:{{ $completion }}%">
                                                                </div>
                                                            </div>
                                                            <small>{{ $completion }}%</small>
                                                        </div>
                                                    </td> --}}
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
                                                            class="btn btn-sm btn-outline-danger" title="Exporter PDF"
                                                            target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                                        Aucun candidat trouvé.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination --}}
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        Page {{ $candidats->currentPage() }} sur {{ $candidats->lastPage() }}
                                    </small>
                                    {{ $candidats->withQueryString()->links() }}
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
