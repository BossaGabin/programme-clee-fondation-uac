{{-- resources/views/admin/users/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Gestion des utilisateurs</title>
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
                            <i class="fas fa-users mr-2"></i> Gestion des utilisateurs
                        </h4>
                    </div>
                    {{-- <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus mr-1"></i> Créer un utilisateur
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Onglets --}}
                                <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="coachs-tab" data-toggle="tab" href="#coachs"
                                            role="tab">
                                            <i class="fas fa-chalkboard-teacher mr-1 text-warning"></i>
                                            Coachs
                                            <span class="badge badge-warning ml-1">{{ $coachs->count() }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="candidats-tab" data-toggle="tab" href="#candidats"
                                            role="tab">
                                            <i class="fas fa-user mr-1 text-info"></i>
                                            Candidats
                                            <span class="badge badge-info ml-1">{{ $candidats->count() }}</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    {{-- ======================== --}}
                                    {{-- ONGLET COACHS --}}
                                    {{-- ======================== --}}
                                    <div class="tab-pane fade show active" id="coachs" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Coach</th>
                                                        <th>Téléphone</th>
                                                        <th>Spécialité</th>
                                                        <th>Candidats affectés</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($coachs as $coach)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"
                                                                    style="gap:10px;">
                                                                    @if ($coach->avatar)
                                                                        <img src="{{ Storage::url($coach->avatar) }}"
                                                                            style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                                                                    @else
                                                                        <div
                                                                            style="width:38px; height:38px; border-radius:50%;
                                                                                    background:#f4a900; display:flex;
                                                                                    align-items:center; justify-content:center;">
                                                                            <i class="fas fa-user text-white"></i>
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <p class="mb-0 font-weight-bold">
                                                                            {{ $coach->name }}</p>
                                                                        <small
                                                                            class="text-muted">{{ $coach->email }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $coach->phone }}</td>
                                                            <td>{{ $coach->coachProfile?->speciality ?? '—' }}</td>
                                                            <td>
                                                                <span class="badge badge-primary"
                                                                    style="font-size:13px;">
                                                                    {{ $coach->assignments_count }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <form method="POST"
                                                                    action="{{ route('admin.users.destroy', $coach) }}"
                                                                    onsubmit="return confirm('Supprimer ce coach ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                Aucun coach enregistré.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- ======================== --}}
                                    {{-- ONGLET CANDIDATS --}}
                                    {{-- ======================== --}}
                                    <div class="tab-pane fade" id="candidats" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Candidat</th>
                                                        <th>Téléphone</th>
                                                        <th>Profil</th>
                                                        <th>Coach assigné</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($candidats as $candidat)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"
                                                                    style="gap:10px;">
                                                                    @if ($candidat->avatar)
                                                                        <img src="{{ Storage::url($candidat->avatar) }}"
                                                                            style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                                                                    @else
                                                                        <div
                                                                            style="width:38px; height:38px; border-radius:50%;
                                                                                    background:#006b08; display:flex;
                                                                                    align-items:center; justify-content:center;">
                                                                            <i class="fas fa-user text-white"></i>
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
                                                            <td>{{ $candidat->phone }}</td>
                                                            <td>
                                                                @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                                                                <div class="d-flex align-items-center" style="gap:8px;">
                                                                    <div class="progress flex-grow-1"
                                                                        style="height:6px; border-radius:6px; min-width:60px;">
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
                                                                @if ($candidat->candidatAssignment?->coach)
                                                                    <span class="badge badge-success">
                                                                        {{ $candidat->candidatAssignment->coach->name }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">Non
                                                                        affecté</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.candidats.show', $candidat) }}"
                                                                    class="btn btn-sm btn-primary mr-1">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <form method="POST" style="display:inline;"
                                                                    action="{{ route('admin.users.destroy', $candidat) }}"
                                                                    onsubmit="return confirm('Supprimer ce candidat ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                Aucun candidat enregistré.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
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
