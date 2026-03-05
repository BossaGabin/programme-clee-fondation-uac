<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Reporter un entretien</title>
@include('section.head')

<body class="v-light vertical-nav fix-header fix-sidebar">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/>
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
                            <i class="fas fa-calendar-alt mr-2"></i> Reporter l'entretien
                        </h4>
                    </div>
                    <div class="col-md-6 align-self-center text-right ">
                        <a href="{{ route('coach.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- Infos actuelles --}}
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Entretien actuel avec
                                    <strong>{{ $appointment->coachAssignment->candidat->name }}</strong>
                                    prévu le
                                    <strong>{{ \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y') }}</strong>
                                    à
                                    <strong>{{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i') }}</strong>
                                </div>

                                <form method="POST" action="{{ route('coach.appointments.update.report', $appointment) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label>Nouvelle date <span class="text-danger">*</span></label>
                                        <input type="date" name="scheduled_date"
                                            class="form-control @error('scheduled_date') is-invalid @enderror"
                                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                            value="{{ old('scheduled_date') }}" required>
                                        @error('scheduled_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Nouvelle heure <span class="text-danger">*</span></label>
                                        <input type="time" name="scheduled_time"
                                            class="form-control @error('scheduled_time') is-invalid @enderror"
                                            value="{{ old('scheduled_time') }}" required>
                                        @error('scheduled_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Mode <span class="text-danger">*</span></label>
                                        <select name="mode" id="mode"
                                            class="form-control @error('mode') is-invalid @enderror" required>
                                            <option value="">-- Choisir --</option>
                                            <option value="presentiel" {{ old('mode', $appointment->mode) === 'presentiel' ? 'selected' : '' }}>
                                                Présentiel
                                            </option>
                                            <option value="en_ligne" {{ old('mode', $appointment->mode) === 'en_ligne' ? 'selected' : '' }}>
                                                En ligne
                                            </option>
                                        </select>
                                        @error('mode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="field-location"
                                        style="{{ old('mode', $appointment->mode) === 'presentiel' ? '' : 'display:none;' }}">
                                        <label>Lieu <span class="text-danger">*</span></label>
                                        <input type="text" name="location"
                                            class="form-control @error('location') is-invalid @enderror"
                                            value="{{ old('location', $appointment->location) }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="field-link"
                                        style="{{ old('mode', $appointment->mode) === 'en_ligne' ? '' : 'display:none;' }}">
                                        <label>Lien de réunion <span class="text-danger">*</span></label>
                                        <input type="url" name="meeting_link"
                                            class="form-control @error('meeting_link') is-invalid @enderror"
                                            value="{{ old('meeting_link', $appointment->meeting_link) }}">
                                        @error('meeting_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block mt-3">
                                        <i class="fas fa-calendar-alt mr-1"></i> Confirmer le report
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('section.foot')
    <script>
        document.getElementById('mode').addEventListener('change', function () {
            document.getElementById('field-location').style.display = this.value === 'presentiel' ? '' : 'none';
            document.getElementById('field-link').style.display = this.value === 'en_ligne' ? '' : 'none';
        });
    </script>
</body>
</html>