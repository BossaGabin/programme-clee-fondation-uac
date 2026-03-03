{{-- resources/views/profile/edit.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
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

        <div class="content-body">
            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-12 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-user-circle mr-2"></i> Mon profil
                        </h4>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('success_password'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <i class="fas fa-key mr-2"></i> {{ session('success_password') }}
                    </div>
                @endif

                <div class="row">

                    {{-- ===== COLONNE GAUCHE ===== --}}
                    <div class="col-md-4">

                        {{-- Carte avatar + identité --}}
                        <div class="card text-center">
                            <div class="card-body" style="padding:30px 20px;">

                                {{-- Avatar cliquable --}}
                                <div style="position:relative; display:inline-block; margin-bottom:15px;">
                                    @if ($user->avatar)
                                        <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}"
                                            style="width:100px;height:100px;border-radius:50%;object-fit:cover;
                                                    border:4px solid #006b08; cursor:pointer;"
                                            onclick="document.getElementById('avatar-input').click()"
                                            title="Cliquer pour changer la photo">
                                    @else
                                        <div id="avatar-placeholder"
                                            style="width:100px;height:100px;border-radius:50%;background:#006b08;
                                                    display:flex;align-items:center;justify-content:center;
                                                    margin:0 auto; cursor:pointer;"
                                            onclick="document.getElementById('avatar-input').click()"
                                            title="Cliquer pour ajouter une photo">
                                            <i class="fas fa-user text-white fa-3x"></i>
                                        </div>
                                        <img id="avatar-preview" src="" alt=""
                                            style="width:100px;height:100px;border-radius:50%;object-fit:cover;
                                                    border:4px solid #006b08; display:none; cursor:pointer;"
                                            onclick="document.getElementById('avatar-input').click()">
                                    @endif

                                    {{-- Bouton caméra --}}
                                    <div onclick="document.getElementById('avatar-input').click()"
                                        style="position:absolute;bottom:0;right:0;width:32px;height:32px;
                                                border-radius:50%;background:#006b08;display:flex;
                                                align-items:center;justify-content:center;
                                                cursor:pointer;border:2px solid #fff;">
                                        <i class="fas fa-camera text-white" style="font-size:12px;"></i>
                                    </div>
                                </div>

                                <small class="text-muted d-block mb-2" style="font-size:11px;">
                                    Cliquez sur la photo pour la modifier
                                </small>

                                <h5 class="font-weight-bold mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-1">{{ $user->email }}</p>
                                <span class="badge badge-{{ $user->role === 'coach' ? 'warning' : 'danger' }}"
                                    style="font-size:11px; padding:4px 10px;">
                                    {{ $user->role === 'coach' ? 'Coach' : 'Administrateur' }}
                                </span>

                                @if ($user->role === 'coach')
                                    @php
                                        $coachProfile = $user->coachProfile;
                                        $profileOk = $coachProfile?->speciality && $coachProfile?->bio;
                                    @endphp
                                    <div class="mt-3">
                                        @if ($profileOk)
                                            <span class="badge badge-primary" style="padding:5px 12px;">
                                                <i class="fas fa-check-circle mr-1"></i> Profil complet
                                            </span>
                                        @else
                                            <span class="badge badge-danger" style="padding:5px 12px;">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Profil incomplet
                                            </span>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>

                        {{-- Infos rapides --}}
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-info-circle mr-2 text-primary"></i> Informations
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Email</small>
                                        <small class="font-weight-bold">{{ $user->email }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Téléphone</small>
                                        <small class="font-weight-bold">{{ $user->phone ?? '—' }}</small>
                                    </li>
                                    @if ($user->role === 'coach' && $user->coachProfile?->speciality)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <small class="text-muted">Spécialité</small>
                                            <small
                                                class="font-weight-bold">{{ $user->coachProfile->speciality }}</small>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between">
                                        <small class="text-muted">Membre depuis</small>
                                        <small
                                            class="font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    {{-- ===== COLONNE DROITE : ONGLETS ===== --}}
                    <div class="col-md-8">
                        <div class="card">

                            {{-- Nav onglets --}}
                            <div class="card-header p-0" style="background:#fff; border-bottom:2px solid #e3e6f0;">
                                <ul class="nav nav-tabs" id="profileTabs" role="tablist"
                                    style="border-bottom:none; padding:0 20px; margin-bottom:-2px;">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $errors->has('current_password') || $errors->has('password') || session('success_password') ? '' : 'active' }}"
                                            id="infos-tab" data-toggle="tab" href="#tab-infos" role="tab">
                                            <i class="fas fa-user-edit mr-2"></i> Informations personnelles
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $errors->has('current_password') || $errors->has('password') || session('success_password') ? 'active' : '' }}"
                                            id="password-tab" data-toggle="tab" href="#tab-password" role="tab">
                                            <i class="fas fa-key mr-2"></i> Mot de passe
                                            @if ($errors->has('current_password') || $errors->has('password'))
                                                <span class="badge badge-danger ml-1"
                                                    style="font-size:9px; vertical-align:middle;">!</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content">

                                {{-- ===== ONGLET 1 : Informations personnelles ===== --}}
                                <div class="tab-pane fade {{ $errors->has('current_password') || $errors->has('password') || session('success_password') ? '' : 'show active' }}"
                                    id="tab-infos" role="tabpanel">
                                    <div class="card-body">

                                        {{-- <form method="POST" action="{{ route('profile.update') }}"
                                            enctype="multipart/form-data"> --}}
                                        <form method="POST"
                                            action="{{ route(auth()->user()->role . '.profile.update') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            {{-- Input avatar caché (déclenché par clic sur la photo) --}}
                                            <input type="file" id="avatar-input" name="avatar" accept="image/*"
                                                style="display:none;">

                                            {{-- Aperçu avatar dans le formulaire --}}
                                            <div class="text-center mb-4">
                                                <div style="position:relative; display:inline-block;">
                                                    <div id="form-avatar-wrap"
                                                        onclick="document.getElementById('avatar-input').click()"
                                                        style="cursor:pointer;">
                                                        @if ($user->avatar)
                                                            <img id="form-avatar-preview"
                                                                src="{{ Storage::url($user->avatar) }}"
                                                                style="width:80px;height:80px;border-radius:50%;
                                                                        object-fit:cover;border:3px solid #006b08;">
                                                        @else
                                                            <div id="form-avatar-placeholder"
                                                                style="width:80px;height:80px;border-radius:50%;
                                                                        background:#e9ecef;display:flex;
                                                                        align-items:center;justify-content:center;">
                                                                <i class="fas fa-user text-muted fa-2x"></i>
                                                            </div>
                                                            <img id="form-avatar-preview" src=""
                                                                alt=""
                                                                style="width:80px;height:80px;border-radius:50%;
                                                                        object-fit:cover;border:3px solid #006b08;
                                                                        display:none;">
                                                        @endif
                                                        <div
                                                            style="position:absolute;bottom:0;right:0;
                                                                    width:26px;height:26px;border-radius:50%;
                                                                    background:#006b08;display:flex;
                                                                    align-items:center;justify-content:center;
                                                                    border:2px solid #fff;">
                                                            <i class="fas fa-camera text-white"
                                                                style="font-size:10px;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="text-muted mt-2 mb-0" style="font-size:12px;">
                                                    Cliquez pour changer la photo de profil & <br> Cliquez ensuite sur
                                                    enregistrer les modifications
                                                </p>
                                                <small id="avatar-filename" class="text-success"
                                                    style="display:none;"></small>
                                            </div>

                                            <hr class="mb-4">

                                            {{-- Nom + Téléphone --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">
                                                            Nom complet <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            value="{{ old('name', $user->name) }}">
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Téléphone</label>
                                                        <input type="text" name="phone" class="form-control"
                                                            value="{{ old('phone', $user->phone) }}"
                                                            placeholder="+229 00 00 00 00">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Email uniquement pour admin --}}
                                            @if ($user->role === 'admin')
                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        Adresse email <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" name="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email', $user->email) }}"
                                                        placeholder="exemple@email.com">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif

                                            {{-- Champs spécifiques au coach --}}
                                            @if ($user->role === 'coach')
                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        Adresse email <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" name="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email', $user->email) }}"
                                                        placeholder="exemple@email.com">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        Spécialité <span class="text-danger">*</span>
                                                        <small class="text-muted font-weight-normal ml-1">
                                                            (visible par les candidats)
                                                        </small>
                                                    </label>
                                                    <input type="text" name="speciality"
                                                        class="form-control @error('speciality') is-invalid @enderror"
                                                        value="{{ old('speciality', $user->coachProfile?->speciality) }}"
                                                        placeholder="Ex: Insertion professionnelle, Orientation carrière...">
                                                    @error('speciality')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        Biographie <span class="text-danger">*</span>
                                                        <small class="text-muted font-weight-normal ml-1">
                                                            (max 1000 caractères)
                                                        </small>
                                                    </label>
                                                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="5" id="bio-textarea"
                                                        placeholder="Décrivez votre parcours, vos compétences, votre approche d'accompagnement..."
                                                        style="min-height: 100px">{{ old('bio', $user->coachProfile?->bio) }}</textarea>
                                                    <small class="text-muted">
                                                        <span
                                                            id="bio-count">{{ strlen($user->coachProfile?->bio ?? '') }}</span>/1000
                                                        caractères
                                                    </small>
                                                    @error('bio')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif

                                            <div class="text-right mt-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save mr-1"></i> Enregistrer les modifications
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                {{-- ===== ONGLET 2 : Mot de passe ===== --}}
                                <div class="tab-pane fade {{ $errors->has('current_password') || $errors->has('password') || session('success_password') ? 'show active' : '' }}"
                                    id="tab-password" role="tabpanel">
                                    <div class="card-body">

                                        {{-- <form method="POST" action="{{ route('profile.password') }}"> --}}
                                        <form method="POST"
                                            action="{{ route(auth()->user()->role . '.profile.password') }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    Mot de passe actuel <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="password" name="current_password"
                                                        id="current_password"
                                                        class="form-control @error('current_password') is-invalid @enderror"
                                                        placeholder="Votre mot de passe actuel">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-primary toggle-pw"
                                                            type="button" data-target="current_password">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('current_password')
                                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">
                                                            Nouveau mot de passe <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="password" name="password" id="new_password"
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                placeholder="Min. 8 caractères">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary toggle-pw"
                                                                    type="button" data-target="new_password">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @error('password')
                                                            <small
                                                                class="text-danger d-block mt-1">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">
                                                            Confirmer <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="password" name="password_confirmation"
                                                                id="confirm_password" class="form-control"
                                                                placeholder="Répéter le nouveau mot de passe">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary toggle-pw"
                                                                    type="button" data-target="confirm_password">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Indicateur de force --}}
                                            <div class="mb-3" id="pw-strength-wrap" style="display:none;">
                                                <small class="text-muted">Force du mot de passe :</small>
                                                <div class="progress mt-1" style="height:6px; border-radius:4px;">
                                                    <div id="pw-strength-bar" class="progress-bar"
                                                        style="width:0%; transition:width 0.3s;"></div>
                                                </div>
                                                <small id="pw-strength-label" class="text-muted"></small>
                                            </div>

                                            <div class="text-right mt-3">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-lock mr-1"></i> Mettre à jour le mot de passe
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
        </div>
    </div>

    @include('section.foot')
</body>

<style>
    #profileTabs .nav-link {
        font-weight: 600;
        padding: 14px 18px;
        border-radius: 0;
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
    }

    #profileTabs .nav-link.active {
        color: #006b08 !important;
        border-bottom: 3px solid #006b08 !important;
        background: transparent;
    }

    #profileTabs .nav-link:hover {
        color: #006b08 !important;
        border-bottom: 3px solid #dee2e6;
    }

    #form-avatar-wrap:hover>div:last-child {
        background: #004d06 !important;
    }
</style>

<script>
    // ===== AVATAR PREVIEW =====
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(ev) {
            // Preview colonne gauche
            const sidePreview = document.getElementById('avatar-preview');
            const sidePlaceholder = document.getElementById('avatar-placeholder');
            if (sidePreview) {
                sidePreview.src = ev.target.result;
                sidePreview.style.display = 'block';
            }
            if (sidePlaceholder) sidePlaceholder.style.display = 'none';

            // Preview formulaire
            const formPreview = document.getElementById('form-avatar-preview');
            const formPlaceholder = document.getElementById('form-avatar-placeholder');
            if (formPreview) {
                formPreview.src = ev.target.result;
                formPreview.style.display = 'block';
            }
            if (formPlaceholder) formPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);

        // Afficher le nom du fichier
        const filenameEl = document.getElementById('avatar-filename');
        if (filenameEl) {
            filenameEl.textContent = file.name;
            filenameEl.style.display = 'block';
        }
    });

    // ===== COMPTEUR BIO =====
    const bioTextarea = document.getElementById('bio-textarea');
    const bioCount = document.getElementById('bio-count');
    if (bioTextarea && bioCount) {
        bioTextarea.addEventListener('input', function() {
            bioCount.textContent = this.value.length;
            bioCount.style.color = this.value.length > 900 ? '#e74a3b' : '#6c757d';
        });
    }

    // ===== TOGGLE MOT DE PASSE =====
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // ===== FORCE MOT DE PASSE =====
    const pwInput = document.getElementById('new_password');
    const pwBar = document.getElementById('pw-strength-bar');
    const pwLabel = document.getElementById('pw-strength-label');
    const pwWrap = document.getElementById('pw-strength-wrap');

    if (pwInput) {
        pwInput.addEventListener('input', function() {
            const val = this.value;
            if (!val) {
                pwWrap.style.display = 'none';
                return;
            }
            pwWrap.style.display = 'block';

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [{
                    pct: 25,
                    color: '#e74a3b',
                    label: 'Très faible'
                },
                {
                    pct: 50,
                    color: '#f6c23e',
                    label: 'Faible'
                },
                {
                    pct: 75,
                    color: '#36b9cc',
                    label: 'Bon'
                },
                {
                    pct: 100,
                    color: '#1cc88a',
                    label: 'Excellent'
                },
            ];
            const lvl = levels[score - 1] || levels[0];
            pwBar.style.width = lvl.pct + '%';
            pwBar.style.background = lvl.color;
            pwLabel.textContent = lvl.label;
            pwLabel.style.color = lvl.color;
        });
    }
</script>

</html>
