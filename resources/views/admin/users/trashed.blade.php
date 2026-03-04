<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Utilisateurs archivés</title>
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
                            <i class="fas fa-archive mr-2"></i> Utilisateurs archivés
                        </h4>
                    </div>

                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>✓</strong> {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-7 align-self-end text-left mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour aux utilisateurs
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Onglets --}}
                                <ul class="nav nav-tabs mb-4" id="trashedTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#trashed-coachs"
                                            role="tab">
                                            <i class="fas fa-chalkboard-teacher mr-1 text-warning"></i>
                                            Coachs archivés
                                            <span class="badge badge-warning ml-1">{{ $coachs->count() }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#trashed-candidats" role="tab">
                                            <i class="fas fa-user mr-1 text-info"></i>
                                            Candidats archivés
                                            <span class="badge badge-info ml-1">{{ $candidats->count() }}</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    {{-- COACHS ARCHIVÉS --}}
                                    <div class="tab-pane fade show active" id="trashed-coachs" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Coach</th>
                                                        <th>Téléphone</th>
                                                        <th>Spécialité</th>
                                                        <th>Archivé le</th>
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
                                                                <span class="text-danger">
                                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                                    {{ $coach->deleted_at->format('d/m/Y à H:i') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{-- Restaurer --}}
                                                                <form method="POST" style="display:inline;"
                                                                    action="{{ route('admin.users.restore', $coach->id) }}"
                                                                    class="form-restore">
                                                                    @csrf
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success btn-restore"
                                                                        data-name="{{ $coach->name }}"
                                                                        data-role="coach">
                                                                        <i class="fas fa-undo mr-1"></i> Restaurer
                                                                    </button>
                                                                </form>

                                                                {{-- Supprimer définitivement --}}
                                                                {{-- <form method="POST" style="display:inline;"
                                                                    action="{{ route('admin.users.forceDelete', $coach->id) }}"
                                                                    class="form-force-delete">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger btn-force-delete"
                                                                        data-name="{{ $coach->name }}">
                                                                        <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                                                    </button>
                                                                </form> --}}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                <i class="fas fa-check-circle text-success mr-2"></i>
                                                                Aucun coach archivé.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- CANDIDATS ARCHIVÉS --}}
                                    <div class="tab-pane fade" id="trashed-candidats" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Candidat</th>
                                                        <th>Téléphone</th>
                                                        <th>Coach assigné</th>
                                                        <th>Archivé le</th>
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
                                                                <span class="text-danger">
                                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                                    {{ $candidat->deleted_at->format('d/m/Y à H:i') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{-- Restaurer --}}
                                                                <form method="POST" style="display:inline;"
                                                                    action="{{ route('admin.users.restore', $candidat->id) }}"
                                                                    class="form-restore">
                                                                    @csrf
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success btn-restore"
                                                                        data-name="{{ $candidat->name }}"
                                                                        data-role="candidat">
                                                                        <i class="fas fa-undo mr-1"></i> Restaurer
                                                                    </button>
                                                                </form>

                                                                {{-- Supprimer définitivement --}}
                                                                {{-- <form method="POST" style="display:inline;"
                                                                    action="{{ route('admin.users.forceDelete', $candidat->id) }}"
                                                                    class="form-force-delete">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger btn-force-delete"
                                                                        data-name="{{ $candidat->name }}">
                                                                        <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                                                    </button>
                                                                </form> --}}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                <i class="fas fa-check-circle text-success mr-2"></i>
                                                                Aucun candidat archivé.
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

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Restaurer
        document.querySelectorAll('.btn-restore').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const form = this.closest('.form-restore');

                Swal.fire({
                    title: 'Restaurer cet utilisateur ?',
                    html: `<strong>${name}</strong> sera de nouveau actif sur la plateforme.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-undo"></i> Oui, restaurer',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Supprimer définitivement
        document.querySelectorAll('.btn-force-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const form = this.closest('.form-force-delete');

                Swal.fire({
                    title: 'Suppression définitive',
                    html: `⚠️ <strong>${name}</strong> sera supprimé <u>définitivement</u>.<br>
                           <small class="text-danger">Cette action est irréversible.</small>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> Supprimer définitivement',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
</body>

</html>
