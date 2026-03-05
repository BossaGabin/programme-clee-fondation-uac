<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Demandes de diagnostic</title>
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
                            <i class="fas fa-clipboard-list mr-2"></i> Demandes de diagnostic
                        </h4>
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


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Filtres et recherche --}}
                                <form method="GET" action="{{ route('admin.demandes.index') }}" class="mb-4">
                                    <div class="row justify-content-between">
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
                                        <div class="col-md-3">
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="">-- Tous les statuts --</option>
                                                <option value="pending"
                                                    {{ request('status') === 'pending' ? 'selected' : '' }}>En attente
                                                </option>
                                                <option value="validated"
                                                    {{ request('status') === 'validated' ? 'selected' : '' }}>Validées
                                                </option>
                                                <option value="rejected"
                                                    {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetées
                                                </option>
                                            </select>
                                        </div>
                                        @if (request('search') || request('status'))
                                            <div class="col-md-2">
                                                <a href="{{ route('admin.demandes.index') }}"
                                                    class="btn btn-outline-danger btn-block">
                                                    <i class="fas fa-times mr-1"></i> Réinitialiser
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </form>

                                {{-- Tableau --}}
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Candidat</th>
                                                <th>Parcours professionnel</th>
                                                <th>Date</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($demandes as $demande)
                                                <tr>
                                                    <td>
                                                        {{-- <div class="d-flex align-items-center" style="gap:10px;">
                                                            @if ($demande->candidat->avatar)
                                                                <img src="{{ Storage::url($demande->candidat->avatar) }}"
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
                                                                    {{ $demande->candidat->name }}</p>
                                                                <small
                                                                    class="text-muted">{{ $demande->candidat->email }}</small>
                                                            </div>
                                                        </div> --}}
                                                        <div class="d-flex align-items-center" style="gap:10px;">
                                                            @if ($demande->candidat?->avatar)
                                                                <img src="{{ Storage::url($demande->candidat->avatar) }}"
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
                                                                    {{ $demande->candidat?->name ?? 'Candidat supprimé' }}
                                                                </p>
                                                                <small class="text-muted">
                                                                    {{ $demande->candidat?->email ?? '—' }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="max-width:250px;">
                                                        <p class="mb-0 text-truncate" style="max-width:230px;"
                                                            title="{{ $demande->parcours_professionnel }}">
                                                            {{ $demande->parcours_professionnel ?? '—' }}
                                                        </p>
                                                    </td>
                                                    <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if ($demande->status === 'pending')
                                                            <span class="badge badge-warning">En attente</span>
                                                        @elseif($demande->status === 'validated')
                                                            <span class="badge badge-success">Validée</span>
                                                        @else
                                                            <span class="badge badge-danger">Rejetée</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.demandes.show', $demande) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        Aucune demande trouvée.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination --}}
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $demandes->withQueryString()->links() }}
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
