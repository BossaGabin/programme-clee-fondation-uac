<!DOCTYPE html>
<html lang="en">

<title>CLEE - Mon profil</title>
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

        <!-- content body -->
        <div class="content-body">
            <div class="container">

                {{-- Titre --}}
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-user-edit mr-2"></i> Mon Profil
                        </h4>
                    </div>
                    {{-- <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('candidat.dashboard') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-arrow-left mr-1"></i> Retour au dashboard
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
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button> <strong>Erreur!</strong> {{ session('error') }}
                    </div>
                @endif
                <div class="row">

                    {{-- ============================================ --}}
                    {{-- COLONNE GAUCHE : Photo + Progression --}}
                    {{-- ============================================ --}}
                    <div class="col-md-3">

                        {{-- Photo de profil --}}
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="mb-3" style="position:relative; display:inline-block;">
                                    @if (auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" id="avatar-preview"
                                            style="width:110px; height:110px; border-radius:50%; object-fit:cover; border:3px solid #006b08;">
                                    @else
                                        <img src="{{ asset('assets/images/avatar/default.png') }}" id="avatar-preview"
                                            style="width:110px; height:110px; border-radius:50%; object-fit:cover; border:3px solid #006b08;">
                                    @endif
                                    <label for="avatar-input"
                                        style="position:absolute; bottom:0; right:0; background:#006b08; color:#fff;
                                          width:30px; height:30px; border-radius:50%; display:flex;
                                          align-items:center; justify-content:center; cursor:pointer;">
                                        <i class="fas fa-camera" style="font-size:12px;"></i>
                                    </label>
                                </div>

                                <h6 class="mb-0 font-weight-bold">{{ auth()->user()->name }}</h6>
                                <small class="text-muted">{{ auth()->user()->email }}</small>

                                {{-- Upload avatar (formulaire séparé) --}}
                                <form method="POST" action="{{ route('candidat.profile.avatar') }}"
                                    enctype="multipart/form-data" id="avatar-form">
                                    @csrf
                                    <input type="file" id="avatar-input" name="avatar" accept="image/*"
                                        style="display:none;">
                                </form>
                            </div>
                        </div>

                        {{-- Barre de progression --}}
                        <div class="card">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3">
                                    <i class="fas fa-tasks mr-1 text-primary"></i>
                                    Complétion du profil
                                </h6>

                                @php
                                    $completion = $profile->profile_completion ?? 0;
                                    $fields = [
                                        'date_of_birth' => ['label' => 'Date de naissance', 'weight' => 20],
                                        'gender' => ['label' => 'Genre', 'weight' => 10],
                                        'address' => ['label' => 'Adresse', 'weight' => 10],
                                        'niveau_etude' => ['label' => 'Niveau d\'étude', 'weight' => 20],
                                        'domaine_formation' => ['label' => 'Domaine', 'weight' => 20],
                                        'experience_years' => ['label' => 'Expérience', 'weight' => 10],
                                        'situation_actuelle' => ['label' => 'Situation', 'weight' => 10],
                                    ];
                                @endphp

                                <div class="progress mb-3" style="height:10px; border-radius:10px;">
                                    <div class="progress-bar
                                @if ($completion < 50) bg-danger
                                @elseif($completion < 100) bg-warning
                                @else bg-success @endif"
                                        style="width:{{ $completion }}%; border-radius:10px;">
                                    </div>
                                </div>
                                <div class="text-center mb-3">
                                    <span class="font-weight-bold" style="font-size:22px;">{{ $completion }}%</span>
                                </div>

                                {{-- Checklist des champs --}}
                                <ul class="list-unstyled mb-0">
                                    @foreach ($fields as $field => $info)
                                        <li class="mb-1 d-flex align-items-center justify-content-between">
                                            <small>{{ $info['label'] }}</small>
                                            @if (!is_null($profile->$field) && $profile->$field !== '')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Upload CV --}}
                                <hr>
                                <h6 class="font-weight-bold">
                                    <i class="fas fa-file-pdf mr-1 text-danger"></i> CV
                                    <small class="text-muted">(optionnel)</small>
                                </h6>
                                @if ($profile->cv_path)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($profile->cv_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-warning btn-block">
                                            <i class="fas fa-eye mr-1"></i> Voir mon CV
                                        </a>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('candidat.profile.cv') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="cv"
                                                name="cv" accept=".pdf,.doc,.docx">
                                            <label class="custom-file-label" for="cv">Choisir un
                                                fichier</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-block mt-2">
                                        <i class="fas fa-upload mr-1"></i> Uploader
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                    {{-- ============================================ --}}
                    {{-- COLONNE DROITE : Formulaire avec onglets --}}
                    {{-- ============================================ --}}
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">

                                {{-- Onglets --}}
                                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="perso-tab" data-toggle="tab" href="#perso"
                                            role="tab">
                                            <i class="fas fa-user mr-1"></i> Infos personnelles
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pro-tab" data-toggle="tab" href="#pro"
                                            role="tab">
                                            <i class="fas fa-briefcase mr-1"></i> Infos professionnelles
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="password-tab" data-toggle="tab" href="#password"
                                            role="tab">
                                            <i class="fas fa-lock mr-1"></i> Mot de passe
                                        </a>
                                    </li>
                                </ul>

                                <form method="POST" action="{{ route('candidat.profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="tab-content" id="profileTabsContent">

                                        {{-- ======================== --}}
                                        {{-- ONGLET 1 : PERSO --}}
                                        {{-- ======================== --}}
                                        <div class="tab-pane fade show active" id="perso" role="tabpanel">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nom & Prénom(s) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ auth()->user()->name }}" disabled>
                                                        <small class="text-muted">Non modifiable.</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control"
                                                            value="{{ auth()->user()->email }}" disabled>
                                                        <small class="text-muted">Non modifiable.</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="date_of_birth">
                                                            Date de naissance <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="date" id="date_of_birth" name="date_of_birth"
                                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                                            value="{{ old('date_of_birth', $profile->date_of_birth) }}">
                                                        @error('date_of_birth')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="gender">
                                                            Genre <span class="text-danger">*</span>
                                                        </label>
                                                        <select id="gender" name="gender"
                                                            class="form-control @error('gender') is-invalid @enderror">
                                                            <option value="">-- Sélectionner --</option>
                                                            <option value="homme"
                                                                {{ old('gender', $profile->gender) === 'homme' ? 'selected' : '' }}>
                                                                Homme</option>
                                                            <option value="femme"
                                                                {{ old('gender', $profile->gender) === 'femme' ? 'selected' : '' }}>
                                                                Femme</option>
                                                        </select>
                                                        @error('gender')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="address">
                                                            Adresse <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" id="address" name="address"
                                                            class="form-control @error('address') is-invalid @enderror"
                                                            placeholder="Votre adresse complète"
                                                            value="{{ old('address', $profile->address) }}">
                                                        @error('address')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save mr-1"></i> Enregistrer
                                                </button>
                                            </div>
                                        </div>

                                        {{-- ======================== --}}
                                        {{-- ONGLET 2 : PRO --}}
                                        {{-- ======================== --}}
                                        <div class="tab-pane fade" id="pro" role="tabpanel">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="niveau_etude">
                                                            Niveau d'étude <span class="text-danger">*</span>
                                                        </label>
                                                        <select id="niveau_etude" name="niveau_etude"
                                                            class="form-control @error('niveau_etude') is-invalid @enderror">
                                                            <option value="">-- Sélectionner --</option>
                                                            @foreach (['Sans diplôme', 'BEPC / Brevet', 'BAC', 'BAC+2', 'BAC+3 / Licence', 'BAC+4', 'BAC+5 / Master', 'Doctorat'] as $niveau)
                                                                <option value="{{ $niveau }}"
                                                                    {{ old('niveau_etude', $profile->niveau_etude) === $niveau ? 'selected' : '' }}>
                                                                    {{ $niveau }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('niveau_etude')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="domaine_formation">
                                                            Domaine de formation <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" id="domaine_formation"
                                                            name="domaine_formation"
                                                            class="form-control @error('domaine_formation') is-invalid @enderror"
                                                            placeholder="Ex: Informatique, Commerce, BTP..."
                                                            value="{{ old('domaine_formation', $profile->domaine_formation) }}">
                                                        @error('domaine_formation')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="experience_years">
                                                            Années d'expérience <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" id="experience_years"
                                                            name="experience_years"
                                                            class="form-control @error('experience_years') is-invalid @enderror"
                                                            placeholder="0" min="0" max="50"
                                                            value="{{ old('experience_years', $profile->experience_years) }}">
                                                        @error('experience_years')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="situation_actuelle">
                                                            Situation actuelle <span class="text-danger">*</span>
                                                        </label>
                                                        <select id="situation_actuelle" name="situation_actuelle"
                                                            class="form-control @error('situation_actuelle') is-invalid @enderror">
                                                            <option value="">-- Sélectionner --</option>
                                                            <option value="en_emploi"
                                                                {{ old('situation_actuelle', $profile->situation_actuelle) === 'en_emploi' ? 'selected' : '' }}>
                                                                En emploi</option>
                                                            <option value="sans_emploi"
                                                                {{ old('situation_actuelle', $profile->situation_actuelle) === 'sans_emploi' ? 'selected' : '' }}>
                                                                Sans emploi</option>
                                                            <option value="etudiant"
                                                                {{ old('situation_actuelle', $profile->situation_actuelle) === 'etudiant' ? 'selected' : '' }}>
                                                                Étudiant(e)</option>
                                                        </select>
                                                        @error('situation_actuelle')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save mr-1"></i> Enregistrer
                                                </button>
                                            </div>
                                        </div>

                                        {{-- ONGLET 3 : MOT DE PASSE --}}
                                        <div class="tab-pane fade" id="password" role="tabpanel">
                                            <form method="POST" action="{{ route('candidat.profile.password') }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="current_password">
                                                                Mot de passe actuel <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="password" id="current_password"
                                                                    name="current_password"
                                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                                    placeholder="Mot de passe actuel">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="toggle-current"
                                                                        style="cursor:pointer;">👁</span>
                                                                </div>
                                                                @error('current_password')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="new_password">
                                                                Nouveau mot de passe <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="password" id="new_password"
                                                                    name="new_password"
                                                                    class="form-control @error('new_password') is-invalid @enderror"
                                                                    placeholder="Minimum 8 caractères">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="toggle-new"
                                                                        style="cursor:pointer;">👁</span>
                                                                </div>
                                                                @error('new_password')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="new_password_confirmation">
                                                                Confirmer le nouveau mot de passe <span
                                                                    class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="password" id="new_password_confirmation"
                                                                    name="new_password_confirmation"
                                                                    class="form-control"
                                                                    placeholder="Confirmer le mot de passe">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="toggle-confirm"
                                                                        style="cursor:pointer;">👁</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save mr-1"></i> Changer le mot de passe
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #/ container -->
    </div>
    <!-- #/ content body -->
    <!-- footer -->

    <!-- #/ footer -->
    </div>
    @include('section.footer')
    @include('section.foot')
    <script>
        const eyeOpen = '👁';
        const eyeClosed =
            `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.94 10.94 0 0112 19C7 19 2.73 15.11 1 12c.74-1.23 1.72-2.41 2.86-3.45M9.88 9.88A3 3 0 0114.12 14.12M1 1l22 22"/></svg>`;

        ['toggle-current', 'toggle-new', 'toggle-confirm'].forEach(id => {
            const btn = document.getElementById(id);
            if (!btn) return;
            btn.addEventListener('click', function() {
                const inputId = {
                    'toggle-current': 'current_password',
                    'toggle-new': 'new_password',
                    'toggle-confirm': 'new_password_confirmation'
                } [id];
                const input = document.getElementById(inputId);
                const hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';
                this.innerHTML = hidden ? eyeClosed : eyeOpen;
            });
        });
    </script>

</body>

</html>
