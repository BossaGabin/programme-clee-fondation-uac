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

                <div class="row mb-3">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-users mr-2"></i> Gestion des utilisateurs
                        </h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus mr-1"></i> Créer un utilisateur
                        </a>
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
                {{-- LES UTILISATEURS --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="admins-tab" data-toggle="tab" href="#admins" role="tab">
                                            <i class="fas fa-user-shield mr-1 text-danger"></i>
                                            Administrateurs
                                            <span class="badge badge-danger ml-1">{{ $admins->count() }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="coachs-tab" data-toggle="tab" href="#coachs"
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

                                    {{-- ONGLET ADMINS --}}
                                    <div class="tab-pane fade show active" id="admins" role="tabpanel">
                                        <table class="table table-hover w-100" id="table-users-admins">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Administrateur</th>
                                                    <th>Téléphone</th>
                                                    <th>Statut</th>
                                                    <th>Créé le</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($admins as $admin)
                                                    <tr class="data-row">
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if($admin->avatar)
                                                                    <img src="{{ Storage::url($admin->avatar) }}"
                                                                        style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                                @else
                                                                    <div style="width:38px;height:38px;border-radius:50%;flex-shrink:0;
                                                                        background:#e74a3b;display:flex;align-items:center;justify-content:center;">
                                                                        <i class="fas fa-user-shield text-white"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="mb-0 font-weight-bold">{{ $admin->name }}</p>
                                                                    <small class="text-muted">{{ $admin->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $admin->phone ?? '—' }}</td>
                                                        <td>
                                                            @if($admin->is_active)
                                                                <span class="badge badge-success" style="font-size:12px; padding:5px 10px;">
                                                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger" style="font-size:12px; padding:5px 10px;">
                                                                    <i class="fas fa-times-circle mr-1"></i> Désactivé
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                                                        <td>
                                                            @if($admin->id !== auth()->id())
                                                                <form method="POST"
                                                                    action="{{ route('admin.users.toggleActive', $admin) }}"
                                                                    class="form-toggle d-inline">
                                                                    @csrf
                                                                    <div class="d-flex align-items-center" style="gap:8px;">
                                                                        <div class="toggle-switch btn-toggle"
                                                                            data-name="{{ $admin->name }}"
                                                                            data-active="{{ $admin->is_active ? '1' : '0' }}"
                                                                            style="
                                                                                width:46px; height:24px; border-radius:12px; cursor:pointer;
                                                                                background: {{ $admin->is_active ? '#1cc88a' : '#e74a3b' }};
                                                                                position:relative; transition: background 0.3s;
                                                                                flex-shrink:0;
                                                                            ">
                                                                            <div style="
                                                                                width:18px; height:18px; border-radius:50%; background:#fff;
                                                                                position:absolute; top:3px;
                                                                                {{ $admin->is_active ? 'right:4px;' : 'left:4px;' }}
                                                                                transition: all 0.3s;
                                                                                box-shadow: 0 1px 3px rgba(0,0,0,0.3);
                                                                            "></div>
                                                                        </div>
                                                                        <small class="{{ $admin->is_active ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                                            {{ $admin->is_active ? 'Actif' : 'Inactif' }}
                                                                        </small>
                                                                    </div>
                                                                </form>
                                                            @else
                                                                <span class="badge badge-secondary">
                                                                    <i class="fas fa-user-circle mr-1"></i> Votre compte
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-4">
                                                            Aucun administrateur enregistré.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- ONGLET COACHS --}}
                                    <div class="tab-pane fade" id="coachs" role="tabpanel">
                                        <table class="table table-hover w-100" id="table-users-coachs">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Coach</th>
                                                    <th>Téléphone</th>
                                                    <th>Spécialité</th>
                                                    {{-- <th>Candidats affectés</th> --}}
                                                    <th>Statut</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($coachs as $coach)
                                                    <tr class="data-row">
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if($coach->avatar)
                                                                    <img src="{{ Storage::url($coach->avatar) }}"
                                                                        style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                                @else
                                                                    <div style="width:38px;height:38px;border-radius:50%;flex-shrink:0;
                                                                        background:#f4a900;display:flex;align-items:center;justify-content:center;">
                                                                        <i class="fas fa-user text-white"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="mb-0 font-weight-bold">{{ $coach->name }}</p>
                                                                    <small class="text-muted">{{ $coach->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $coach->phone ?? '—' }}</td>
                                                        <td>{{ $coach->coachProfile?->speciality ?? '—' }}</td>
                                                        {{-- <td>
                                                            <span class="badge badge-primary" style="font-size:13px;">
                                                                {{ $coach->assignments_count }}
                                                            </span>
                                                        </td> --}}
                                                        <td>
                                                            @if($coach->is_active)
                                                                <span class="badge badge-success" style="font-size:12px; padding:5px 10px;">
                                                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger" style="font-size:12px; padding:5px 10px;">
                                                                    <i class="fas fa-times-circle mr-1"></i> Désactivé
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.coachs.show', $coach) }}"
                                                                class="btn btn-sm btn-rounded btn-primary mr-1">
                                                                <span class="btn-icon-left"><i class="fas fa-eye color-primary"></i></span>
                                                                Voir
                                                            </a>

                                                            <form method="POST"
                                                                action="{{ route('admin.users.toggleActive', $coach) }}"
                                                                class="form-toggle d-inline">
                                                                @csrf
                                                                <div class="d-flex align-items-center d-inline-flex" style="gap:8px; vertical-align:middle;">
                                                                    <div class="toggle-switch btn-toggle"
                                                                        data-name="{{ $coach->name }}"
                                                                        data-active="{{ $coach->is_active ? '1' : '0' }}"
                                                                        style="
                                                                            width:46px; height:24px; border-radius:12px; cursor:pointer;
                                                                            background: {{ $coach->is_active ? '#1cc88a' : '#e74a3b' }};
                                                                            position:relative; transition: background 0.3s;
                                                                            flex-shrink:0;
                                                                        ">
                                                                        <div style="
                                                                            width:18px; height:18px; border-radius:50%; background:#fff;
                                                                            position:absolute; top:3px;
                                                                            {{ $coach->is_active ? 'right:4px;' : 'left:4px;' }}
                                                                            transition: all 0.3s;
                                                                            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
                                                                        "></div>
                                                                    </div>
                                                                    <small class="{{ $coach->is_active ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                                        {{ $coach->is_active ? 'Actif' : 'Inactif' }}
                                                                    </small>
                                                                </div>
                                                            </form>
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

                                    {{-- ONGLET CANDIDATS --}}
                                    <div class="tab-pane fade" id="candidats" role="tabpanel">
                                        <table class="table table-hover w-100" id="table-users-candidats">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Candidat</th>
                                                    <th>Téléphone</th>
                                                    <th>Profil</th>
                                                    <th>Coach assigné</th>
                                                    <th>Statut</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($candidats as $candidat)
                                                    <tr class="data-row">
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if($candidat->avatar)
                                                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                                                        style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                                @else
                                                                    <div style="width:38px;height:38px;border-radius:50%;flex-shrink:0;
                                                                        background:#006b08;display:flex;align-items:center;justify-content:center;">
                                                                        <i class="fas fa-user text-white"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="mb-0 font-weight-bold">{{ $candidat->name }}</p>
                                                                    <small class="text-muted">{{ $candidat->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $candidat->phone ?? '—' }}</td>
                                                        <td>
                                                            @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                                                            <div class="d-flex align-items-center" style="gap:8px;">
                                                                <div class="progress flex-grow-1" style="height:6px;border-radius:6px;min-width:60px;">
                                                                    <div class="progress-bar
                                                                        @if($completion < 50) bg-danger
                                                                        @elseif($completion < 100) bg-warning
                                                                        @else bg-success @endif"
                                                                        style="width:{{ $completion }}%">
                                                                    </div>
                                                                </div>
                                                                <small>{{ $completion }}%</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($candidat->candidatAssignment?->coach)
                                                                <span class="badge badge-success">
                                                                    {{ $candidat->candidatAssignment->coach->name }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">Non affecté</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($candidat->is_active)
                                                                <span class="badge badge-success" style="font-size:12px; padding:5px 10px;">
                                                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger" style="font-size:12px; padding:5px 10px;">
                                                                    <i class="fas fa-times-circle mr-1"></i> Désactivé
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.candidats.show', $candidat) }}"
                                                                class="btn btn-sm btn-rounded btn-primary mr-1">
                                                                <span class="btn-icon-left"><i class="fas fa-eye color-primary"></i></span>
                                                                Voir
                                                            </a>

                                                            <form method="POST"
                                                                action="{{ route('admin.users.toggleActive', $candidat) }}"
                                                                class="form-toggle d-inline">
                                                                @csrf
                                                                <div class="d-flex align-items-center d-inline-flex" style="gap:8px; vertical-align:middle;">
                                                                    <div class="toggle-switch btn-toggle"
                                                                        data-name="{{ $candidat->name }}"
                                                                        data-active="{{ $candidat->is_active ? '1' : '0' }}"
                                                                        style="
                                                                            width:46px; height:24px; border-radius:12px; cursor:pointer;
                                                                            background: {{ $candidat->is_active ? '#1cc88a' : '#e74a3b' }};
                                                                            position:relative; transition: background 0.3s;
                                                                            flex-shrink:0;
                                                                        ">
                                                                        <div style="
                                                                            width:18px; height:18px; border-radius:50%; background:#fff;
                                                                            position:absolute; top:3px;
                                                                            {{ $candidat->is_active ? 'right:4px;' : 'left:4px;' }}
                                                                            transition: all 0.3s;
                                                                            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
                                                                        "></div>
                                                                    </div>
                                                                    <small class="{{ $candidat->is_active ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                                        {{ $candidat->is_active ? 'Actif' : 'Inactif' }}
                                                                    </small>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">
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
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const form = this.closest('.form-delete');

                Swal.fire({
                    title: 'Confirmer l\'archivage',
                    html: `Voulez-vous archiver <strong>${name}</strong> ?<br>
                       <small class="text-muted">Cette action est réversible.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-archive"></i> Oui, archiver',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>


    <script>
        document.querySelectorAll('.btn-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const name     = this.getAttribute('data-name');
                const isActive = this.getAttribute('data-active') === '1';
                const form     = this.closest('.form-toggle');

                Swal.fire({
                    title: isActive ? 'Désactiver ce compte ?' : 'Activer ce compte ?',
                    html: isActive
                        ? `<strong>${name}</strong> ne pourra plus se connecter à la plateforme.`
                        : `<strong>${name}</strong> pourra de nouveau se connecter à la plateforme.`,
                    icon: isActive ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonColor: isActive ? '#e74a3b' : '#1cc88a',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: isActive
                        ? '<i class="fas fa-ban mr-1"></i> Oui, désactiver'
                        : '<i class="fas fa-check-circle mr-1"></i> Oui, activer',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
    @include('section.foot')
</body>

</html>
