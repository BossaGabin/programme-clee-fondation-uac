{{-- resources/views/admin/demandes/show.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Traiter la demande</title>
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
                            <i class="fas fa-clipboard-check mr-2"></i> Traiter la demande
                        </h4>
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
                        <strong>Erreur!</strong> {{ session('error') }}
                    </div>
                @endif

                @php
                    $pendingAssignment = $demande->candidat
                        ->coachAssignments()
                        ->where('status', 'pending')
                        ->with('coach')
                        ->first();

                    $rejectedAssignments = $demande->candidat
                        ->coachAssignments()
                        ->where('status', 'rejected')
                        ->with('coach')
                        ->get();

                    $activeAssignment = $demande->candidat->candidatAssignment;
                @endphp

                <div class="row">

                    {{-- ============================================ --}}
                    {{-- COLONNE GAUCHE : Infos candidat             --}}
                    {{-- ============================================ --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user mr-2 text-primary"></i> Candidat
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($demande->candidat->avatar)
                                    <img src="{{ Storage::url($demande->candidat->avatar) }}"
                                        style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #006b08;"
                                        class="mb-3">
                                @else
                                    <div
                                        style="width:80px;height:80px;border-radius:50%;background:#006b08;
                                        display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif

                                <h5 class="font-weight-bold mb-1">{{ $demande->candidat->name }}</h5>
                                <p class="text-muted mb-1">{{ $demande->candidat->email }}</p>
                                <p class="text-muted mb-3">{{ $demande->candidat->phone }}</p>

                                @php $profile = $demande->candidat->candidatProfile; @endphp
                                @if ($profile)
                                    <ul class="list-group list-group-flush text-left">
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <small class="text-muted">Niveau d'étude</small>
                                            <small class="font-weight-bold">{{ $profile->niveau_etude ?? '—' }}</small>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <small class="text-muted">Domaine</small>
                                            <small
                                                class="font-weight-bold">{{ $profile->domaine_formation ?? '—' }}</small>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <small class="text-muted">Expérience</small>
                                            <small class="font-weight-bold">{{ $profile->experience_years ?? '0' }}
                                                ans</small>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <small class="text-muted">Situation</small>
                                            <small class="font-weight-bold">
                                                {{ $profile->situation_actuelle === 'autre' ? $profile->situation_autre : $profile->situation_actuelle ?? '—' }}
                                            </small>
                                        </li>
                                    </ul>
                                @endif

                                @if ($profile && $profile->cv_path)
                                    <a href="{{ Storage::url($profile->cv_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success btn-block mt-3">
                                        <i class="fas fa-file-pdf mr-1"></i> Voir le CV
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- COLONNE DROITE                              --}}
                    {{-- ============================================ --}}
                    <div class="col-md-8">

                        {{-- Parcours professionnel --}}
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-briefcase mr-2 text-info"></i> Parcours professionnel
                                </h5>
                            </div>
                            <div class="card-body">
                                <p style="line-height:1.8; color:#555;">
                                    {{ $demande->parcours_professionnel ?? 'Non renseigné.' }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Demande soumise le {{ $demande->created_at->format('d/m/Y à H:i') }}
                                </small>
                            </div>
                        </div>

                        {{-- Statut de la demande --}}
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-tasks mr-2 text-warning"></i> Statut de la demande
                                </h5>
                            </div>
                            <div class="card-body">

                                @if ($demande->status === 'pending')
                                    <div class="row">
                                        {{-- Valider --}}
                                        <div class="col-md-6">
                                            <form method="POST"
                                                action="{{ route('admin.demandes.validate', $demande) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-danger">
                                                        <i class="fas fa-times-circle mr-1"></i> Accepter la demande
                                                    </label>
                                                    <textarea name="note_admin" class="form-control" rows="3" placeholder="Motif de l'acceptation (obligatoire)" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-success">
                                                        <i class="fas fa-check-circle mr-1"></i> Valider la demande
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fas fa-check mr-1"></i> Valider
                                                </button>
                                            </form>
                                        </div>
                                        {{-- Rejeter --}}
                                        <div class="col-md-6">
                                            <form method="POST"
                                                action="{{ route('admin.demandes.reject', $demande) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-danger">
                                                        <i class="fas fa-times-circle mr-1"></i> Rejeter la demande
                                                    </label>
                                                    <textarea name="note_admin" class="form-control" rows="3" placeholder="Motif du rejet (obligatoire)" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger btn-block">
                                                    <i class="fas fa-times mr-1"></i> Rejeter
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($demande->status === 'validated')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Demande validée le {{ $demande->validated_at?->format('d/m/Y') }}
                                        @if ($demande->note_admin)
                                            <br><small>Note : {{ $demande->note_admin }}</small>
                                        @endif
                                    </div>
                                @elseif($demande->status === 'rejected')
                                    <div class="alert alert-danger mb-0">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Demande rejetée.
                                        @if ($demande->note_admin)
                                            <br><small>Motif : {{ $demande->note_admin }}</small>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>

                        {{-- Affectation en attente --}}
                        @if ($pendingAssignment)
                            <div class="card" style="border-left:4px solid #f6c23e;">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-hourglass-half mr-2 text-warning"></i>
                                        Affectation en attente de validation
                                    </h5>
                                </div>
                                <div class="card-body d-flex align-items-center" style="gap:15px;">
                                    @if ($pendingAssignment->coach->avatar)
                                        <img src="{{ Storage::url($pendingAssignment->coach->avatar) }}"
                                            style="width:50px;height:50px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                    @else
                                        <div
                                            style="width:50px;height:50px;border-radius:50%;background:#f4a900;
                                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="fas fa-user text-white fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="mb-0 font-weight-bold">{{ $pendingAssignment->coach->name }}</p>
                                        <small class="text-muted">{{ $pendingAssignment->coach->email }}</small>
                                        <br>
                                        <small class="text-warning font-weight-bold">
                                            <i class="fas fa-clock mr-1"></i>
                                            Expire le
                                            {{ \Carbon\Carbon::parse($pendingAssignment->expires_at)->format('d/m/Y à H:i') }}
                                            {{-- ({{ \Carbon\Carbon::parse($pendingAssignment->expires_at )->diffForHumans() }}) --}}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Affectations rejetées --}}
                        @if ($rejectedAssignments->isNotEmpty())
                            <div class="card" style="border-left:4px solid #e74a3b;">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-times-circle mr-2 text-danger"></i>
                                        Affectations rejetées
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($rejectedAssignments as $rejected)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-0 font-weight-bold">{{ $rejected->coach->name }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-comment mr-1"></i>
                                                        {{ $rejected->rejected_reason ?? 'Aucune raison fournie' }}
                                                    </small>
                                                </div>
                                                <span class="badge badge-danger">Rejeté</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Formulaire affecter un coach --}}
                        @if ($demande->status === 'validated' && !$activeAssignment && !$pendingAssignment)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chalkboard-teacher mr-2 text-primary"></i>
                                        Affecter un coach
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.assignments.store', $demande) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="coach_id" class="font-weight-bold">
                                                Choisir un coach <span class="text-danger">*</span>
                                            </label>
                                            <select name="coach_id" id="coach_id"
                                                class="form-control @error('coach_id') is-invalid @enderror" required>
                                                <option value="">-- Sélectionner un coach --</option>
                                                @foreach ($coachs as $coach)
                                                    <option value="{{ $coach->id }}">
                                                        {{ $coach->name }}
                                                        @if ($coach->coachProfile?->speciality)
                                                            — {{ $coach->coachProfile->speciality }}
                                                        @endif
                                                        ({{ $coach->assignments_count }} candidat(s))
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('coach_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-check mr-1"></i> Affecter ce coach
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Coach actif affecté --}}
                        @if ($activeAssignment)
                            <div class="card" style="border-left:4px solid #1cc88a;">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user-check mr-2 text-success"></i> Coach affecté
                                    </h5>
                                </div>
                                <div class="card-body d-flex align-items-center" style="gap:15px;">
                                    @if ($activeAssignment->coach->avatar)
                                        <img src="{{ Storage::url($activeAssignment->coach->avatar) }}"
                                            style="width:55px;height:55px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                    @else
                                        <div
                                            style="width:55px;height:55px;border-radius:50%;background:#f4a900;
                                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="fas fa-user text-white fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="mb-0 font-weight-bold">{{ $activeAssignment->coach->name }}</p>
                                        <small class="text-muted">{{ $activeAssignment->coach->email }}</small>
                                        <br>
                                        <small class="text-success font-weight-bold">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Accepté le
                                            {{ \Carbon\Carbon::parse($activeAssignment->accepted_at)->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('section.footer')
    @include('section.foot')
</body>

</html>
