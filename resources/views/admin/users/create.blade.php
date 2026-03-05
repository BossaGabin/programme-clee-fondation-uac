{{-- resources/views/admin/users/create.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Créer un utilisateur</title>
@include('section.head')

<body class="v-light vertical-nav fix-header fix-sidebar">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <div id="main-wrapper">
        @include('section.header')
        @include('section.sidebar')

        <div class="content-body">
            <div class="container">

                <div class="row page-titles">
                    <div class="col-md-10 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-user-plus mr-2"></i> Créer un utilisateur
                        </h4>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle mr-2 text-primary"></i>
                                    Informations du compte
                                </h5>
                            </div>
                            <div class="card-body">

                                <div class="alert alert-info">
                                    <i class="fas fa-lock mr-2"></i>
                                    Le mot de passe sera <strong>généré automatiquement</strong> et envoyé
                                    par email à l'utilisateur avec ses identifiants de connexion.
                                </div>

                                <form method="POST" action="{{ route('admin.users.store') }}">
                                    @csrf

                                    {{-- Rôle --}}
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Rôle <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex" style="gap: 15px;">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="role_candidat" name="role"
                                                       value="candidat" class="custom-control-input"
                                                       {{ old('role', 'candidat') === 'candidat' ? 'checked' : '' }}
                                                       onchange="toggleCoachFields()">
                                                <label class="custom-control-label" for="role_candidat">
                                                    <i class="fas fa-user mr-1 text-info"></i> Candidat
                                                </label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="role_coach" name="role"
                                                       value="coach" class="custom-control-input"
                                                       {{ old('role') === 'coach' ? 'checked' : '' }}
                                                       onchange="toggleCoachFields()">
                                                <label class="custom-control-label" for="role_coach">
                                                    <i class="fas fa-chalkboard-teacher mr-1 text-warning"></i> Coach
                                                </label>
                                            </div>
                                        </div>
                                        @error('role')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <hr>

                                    {{-- Nom --}}
                                    <div class="form-group">
                                        <label for="name">
                                            Nom & Prénom(s) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="Nom & Prénom(s)"
                                               value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <label for="email">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="Email"
                                               value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Téléphone --}}
                                    <div class="form-group">
                                        <label for="phone">
                                            Téléphone <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="phone" name="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               placeholder="0197000000"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Champs coach (affichés uniquement si rôle = coach) --}}
                                    <div id="coach-fields" style="{{ old('role') === 'coach' ? '' : 'display:none;' }}">
                                        <hr>
                                        <h6 class="font-weight-bold mb-3">
                                            <i class="fas fa-chalkboard-teacher mr-1 text-warning"></i>
                                            Profil coach
                                        </h6>

                                        <div class="form-group">
                                            <label for="speciality">Spécialité</label>
                                            <input type="text" id="speciality" name="speciality"
                                                   class="form-control"
                                                   placeholder="Ex: Insertion emploi, Entrepreneuriat..."
                                                   value="{{ old('speciality') }}">
                                        </div>

                                        {{-- <div class="form-group">
                                            <label for="bio">Biographie</label>
                                            <textarea id="bio" name="bio" class="form-control" rows="3"
                                                      placeholder="Présentation du coach..." style="min-height: 100px">{{ old('bio') }}</textarea>
                                        </div> --}}
                                    </div>

                                    <div class="text-right mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus mr-1"></i> Créer le compte
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
    @include('section.footer')
    @include('section.foot')
</body>

<script>
    function toggleCoachFields() {
        const role = document.querySelector('input[name="role"]:checked').value;
        document.getElementById('coach-fields').style.display = role === 'coach' ? 'block' : 'none';
    }
</script>

</html>