{{-- resources/views/coach/appointments/create.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Programmer un entretien</title>
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
            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-calendar-plus mr-2"></i> Programmer un entretien
                        </h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('coach.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">

                        {{-- Info candidat --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center" style="gap:15px;">
                                @if($assignment->candidat->avatar)
                                    <img src="{{ Storage::url($assignment->candidat->avatar) }}"
                                         style="width:55px;height:55px;border-radius:50%;object-fit:cover;border:3px solid #006b08;">
                                @else
                                    <div style="width:55px;height:55px;border-radius:50%;background:#006b08;
                                                display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-user text-white fa-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0 font-weight-bold">{{ $assignment->candidat->name }}</h5>
                                    <small class="text-muted">{{ $assignment->candidat->email }}</small><br>
                                    <small class="text-muted">{{ $assignment->candidat->phone }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Formulaire --}}
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-calendar mr-2 text-primary"></i> Détails de l'entretien
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('coach.appointments.store', $assignment) }}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="scheduled_date">
                                                    Date <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" id="scheduled_date" name="scheduled_date"
                                                       class="form-control @error('scheduled_date') is-invalid @enderror"
                                                       min="{{ date('Y-m-d') }}"
                                                       value="{{ old('scheduled_date') }}">
                                                @error('scheduled_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="scheduled_time">
                                                    Heure <span class="text-danger">*</span>
                                                </label>
                                                <input type="time" id="scheduled_time" name="scheduled_time"
                                                       class="form-control @error('scheduled_time') is-invalid @enderror"
                                                       value="{{ old('scheduled_time') }}">
                                                @error('scheduled_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Mode <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex" style="gap:20px;">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="mode_presentiel" name="mode"
                                                       value="presentiel" class="custom-control-input"
                                                       {{ old('mode', 'presentiel') === 'presentiel' ? 'checked' : '' }}
                                                       onchange="toggleModeFields()">
                                                <label class="custom-control-label" for="mode_presentiel">
                                                    <i class="fas fa-map-marker-alt mr-1 text-primary"></i> Présentiel
                                                </label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="mode_enligne" name="mode"
                                                       value="en_ligne" class="custom-control-input"
                                                       {{ old('mode') === 'en_ligne' ? 'checked' : '' }}
                                                       onchange="toggleModeFields()">
                                                <label class="custom-control-label" for="mode_enligne">
                                                    <i class="fas fa-video mr-1 text-info"></i> En ligne
                                                </label>
                                            </div>
                                        </div>
                                        @error('mode')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Champ lieu (présentiel) --}}
                                    <div id="field-location" class="form-group">
                                        <label for="location">
                                            Lieu <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="location" name="location"
                                               class="form-control @error('location') is-invalid @enderror"
                                               placeholder="Adresse ou salle de réunion"
                                               value="{{ old('location') }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Champ lien (en ligne) --}}
                                    <div id="field-link" class="form-group" style="display:none;">
                                        <label for="meeting_link">
                                            Lien de la réunion <span class="text-danger">*</span>
                                        </label>
                                        <input type="url" id="meeting_link" name="meeting_link"
                                               class="form-control @error('meeting_link') is-invalid @enderror"
                                               placeholder="https://meet.google.com/..."
                                               value="{{ old('meeting_link') }}">
                                        @error('meeting_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="text-right mt-4">
                                        <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-secondary mr-2">
                                            Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-calendar-check mr-1"></i> Programmer
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
    function toggleModeFields() {
        const mode = document.querySelector('input[name="mode"]:checked')?.value;
        document.getElementById('field-location').style.display = mode === 'presentiel' ? 'block' : 'none';
        document.getElementById('field-link').style.display     = mode === 'en_ligne'   ? 'block' : 'none';
    }

    // Init au chargement
    toggleModeFields();

    @if(old('mode') === 'en_ligne')
        toggleModeFields();
    @endif
</script>

</html>