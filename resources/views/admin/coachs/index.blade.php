{{-- resources/views/admin/coachs/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Liste des coachs</title>
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
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-chalkboard-teacher mr-2"></i> Liste des coachs
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
                    <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus mr-1"></i> Créer un coach
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Recherche --}}
                                <form method="GET" action="{{ route('admin.coachs.index') }}" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-5">
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
                                        @if (request('search'))
                                            <div class="col-md-2">
                                                <a href="{{ route('admin.coachs.index') }}"
                                                    class="btn btn-outline-secondary btn-block">
                                                    <i class="fas fa-times mr-1"></i> Réinitialiser
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Coach</th>
                                                <th>Téléphone</th>
                                                <th>Spécialité</th>
                                                <th>Biographie</th>
                                                <th>Candidats</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($coachs as $coach)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center" style="gap:10px;">
                                                            @if ($coach->avatar)
                                                                <img src="{{ Storage::url($coach->avatar) }}"
                                                                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                                                            @else
                                                                <div
                                                                    style="width:40px; height:40px; border-radius:50%;
                                                                            background:#f4a900; display:flex;
                                                                            align-items:center; justify-content:center;">
                                                                    <i class="fas fa-user text-white"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="mb-0 font-weight-bold">{{ $coach->name }}</p>
                                                                <small class="text-muted">{{ $coach->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $coach->phone }}</td>
                                                    <td>{{ $coach->coachProfile?->speciality ?? '—' }}</td>
                                                    <td style="max-width:200px;">
                                                        <p class="mb-0 text-truncate" style="max-width:180px;"
                                                            title="{{ $coach->coachProfile?->bio }}">
                                                            {{ $coach->coachProfile?->bio ?? '—' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary" style="font-size:13px;">
                                                            {{ $coach->assignments_count }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.coachs.show', $coach) }}"
                                                            class="btn btn-sm btn-primary mr-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        {{-- <form method="POST" style="display:inline;"
                                                            action="{{ route('admin.coachs.destroy', $coach) }}"
                                                            onsubmit="return confirm('Supprimer ce coach ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form> --}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        Aucun coach enregistré.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    {{ $coachs->withQueryString()->links() }}
                                </div>

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
