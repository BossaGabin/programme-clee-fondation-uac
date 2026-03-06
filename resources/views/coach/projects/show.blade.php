<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Projet professionnel</title>
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
            <div class="container">

                <div class="row mb-3">
                    <div class="col-md-6 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-briefcase mr-2"></i> Projet professionnel
                        </h4>
                    </div>
                    <div class="col-md-6 align-self-center text-right">
                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                            class="btn btn-sm btn-outline-secondary mr-2">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                        <a href="{{ route('coach.projects.edit', $candidat) }}" class="btn btn-sm btn-warning mr-2">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                        <a href="{{ route('coach.projects.pdf', $candidat) }}"
                            class="btn btn-sm btn-danger d-none d-md-inline-block" target="_blank">
                            <i class="fas fa-file-pdf mr-1"></i> Exporter PDF
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>Bravo!</strong> {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Identité candidat --}}
                                <div class="d-flex align-items-center mb-4 pb-3"
                                    style="border-bottom: 2px solid #006b08;">
                                    @if ($candidat->avatar)
                                        <img src="{{ Storage::url($candidat->avatar) }}"
                                            style="width:55px;height:55px;border-radius:50%;object-fit:cover;margin-right:15px;">
                                    @else
                                        <div
                                            style="width:55px;height:55px;border-radius:50%;
                                            background:#006b08;display:flex;align-items:center;
                                            justify-content:center;margin-right:15px;">
                                            <i class="fas fa-user text-white fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0 font-weight-bold">{{ $candidat->name }}</h5>
                                        <small class="text-muted">{{ $candidat->email }}</small>
                                    </div>
                                </div>

                                {{-- Infos principales --}}
                                <div class="row mb-3">
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100" style="border-left: 4px solid #006b08;">
                                            <div class="card-body py-3">
                                                <h6 class="font-weight-bold mb-2" style="color:#006b08;">
                                                    <i class="fas fa-briefcase mr-2"></i>Titre du projet
                                                </h6>
                                                <p class="mb-0 text-muted">{{ $project->titre_projet }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100" style="border-left: 4px solid #1cc88a;">
                                            <div class="card-body py-3">
                                                <h6 class="font-weight-bold mb-2" style="color:#1cc88a;">
                                                    <i class="fas fa-map-marker-alt mr-2"></i>Secteur cible
                                                </h6>
                                                <p class="mb-0 text-muted">{{ $project->secteur_cible }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100" style="border-left: 4px solid #4e73df;">
                                            <div class="card-body py-3">
                                                <h6 class="font-weight-bold mb-2" style="color:#4e73df;">
                                                    <i class="fas fa-user-tie mr-2"></i>Poste visé
                                                </h6>
                                                <p class="mb-0 text-muted">{{ $project->poste_vise }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    {{-- Description --}}
                                    @if ($project->description)
                                        <div class="col-md-12 mb-3">
                                            <div class="card" style="border-left: 4px solid #006b08;">
                                                <div class="card-body py-3">
                                                    <h6 class="font-weight-bold mb-2" style="color:#006b08;">
                                                        <i class="fas fa-align-left mr-2"></i>Description
                                                    </h6>
                                                    <p class="mb-0 text-muted">{{ $project->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Objectif court terme --}}
                                    @if ($project->objectif_court_terme)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100" style="border-left: 4px solid #f4a900;">
                                                <div class="card-body py-3">
                                                    <h6 class="font-weight-bold mb-2" style="color:#f4a900;">
                                                        <i class="fas fa-bolt mr-2"></i>Objectif court terme
                                                    </h6>
                                                    <p class="mb-0 text-muted">{{ $project->objectif_court_terme }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Objectif long terme --}}
                                    @if ($project->objectif_long_terme)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100" style="border-left: 4px solid #4e73df;">
                                                <div class="card-body py-3">
                                                    <h6 class="font-weight-bold mb-2" style="color:#4e73df;">
                                                        <i class="fas fa-flag mr-2"></i>Objectif long terme
                                                    </h6>
                                                    <p class="mb-0 text-muted">{{ $project->objectif_long_terme }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                {{-- Infos meta --}}
                                <div class="mt-3 text-muted" style="font-size:12px;">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Créé le {{ $project->created_at->format('d/m/Y') }}
                                    @if ($project->updated_at != $project->created_at)
                                        — Modifié le {{ $project->updated_at->format('d/m/Y') }}
                                    @endif
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
