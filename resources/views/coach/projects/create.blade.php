{{-- resources/views/coach/projects/create.blade.php --}}
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
            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-project-diagram mr-2"></i> Projet professionnel
                        </h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                            class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button> <strong>Erreur!</strong> {{ session('error') }}
                    </div>
                @endif

                {{-- Bannière candidat --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card" style="border-left:4px solid #006b08;">
                            <div class="card-body d-flex align-items-center" style="gap:15px; padding:12px 20px;">
                                @if ($candidat->avatar)
                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                        style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div
                                        style="width:45px;height:45px;border-radius:50%;background:#006b08;
                                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="mb-0 font-weight-bold">{{ $candidat->name }}</p>
                                    <small class="text-muted">Définition du projet professionnel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-briefcase mr-2 text-primary"></i>
                                    Renseigner le projet professionnel
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('coach.projects.store', $candidat) }}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    Titre du projet <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="titre_projet"
                                                    class="form-control @error('titre_projet') is-invalid @enderror"
                                                    placeholder="Ex: Développeur web freelance, Comptable d'entreprise..."
                                                    value="{{ old('titre_projet') }}">
                                                @error('titre_projet')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    Secteur cible <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="secteur_cible"
                                                    class="form-control @error('secteur_cible') is-invalid @enderror"
                                                    placeholder="Ex: Informatique, Finance, Santé..."
                                                    value="{{ old('secteur_cible') }}">
                                                @error('secteur_cible')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    Poste visé <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="poste_vise"
                                                    class="form-control @error('poste_vise') is-invalid @enderror"
                                                    placeholder="Ex: Développeur fullstack, Comptable junior..."
                                                    value="{{ old('poste_vise') }}">
                                                @error('poste_vise')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Description du projet</label>
                                                <textarea name="description" class="form-control" rows="4"
                                                    placeholder="Décrivez le projet professionnel, le contexte, les motivations...">{{ old('description') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    <i class="fas fa-flag mr-1 text-warning"></i>
                                                    Objectif court terme
                                                </label>
                                                <textarea name="objectif_court_terme" class="form-control" rows="3"
                                                    placeholder="Objectif à atteindre dans les 3 à 6 mois...">{{ old('objectif_court_terme') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    <i class="fas fa-flag-checkered mr-1 text-success"></i>
                                                    Objectif long terme
                                                </label>
                                                <textarea name="objectif_long_terme" class="form-control" rows="3" placeholder="Vision à 1 an et plus...">{{ old('objectif_long_terme') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right mt-2">
                                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                                            class="btn btn-outline-secondary mr-2">
                                            Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Enregistrer le projet
                                        </button>
                                    </div>

                                </form>
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
