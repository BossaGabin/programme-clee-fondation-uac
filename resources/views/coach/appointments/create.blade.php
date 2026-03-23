{{-- resources/views/coach/appointments/create.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Proposer des horaires</title>
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
                    <div class="col-md-12 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-calendar-plus mr-2"></i> Proposer des horaires d'entretien
                        </h4>
                        <small class="text-muted">
                            Proposez 3 horaires à
                            <strong>{{ $assignment->candidat->name }}</strong>
                            — il choisira celui qui lui convient
                        </small>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <strong>Erreur!</strong> {{ session('error') }}
                    </div>
                @endif

                {{-- Proposition déjà envoyée --}}
                @if($proposalExistante)
                    <div class="alert alert-warning">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        Vous avez déjà envoyé une proposition à ce candidat.
                        En attente de sa réponse.
                    </div>
                @endif

                {{-- Entretien déjà schedulé --}}
                @if($existant)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        Un entretien est déjà programmé avec ce candidat le
                        <strong>{{ \Carbon\Carbon::parse($existant->scheduled_date)->format('d/m/Y') }}</strong>
                        à <strong>{{ \Carbon\Carbon::parse($existant->scheduled_time)->format('H:i') }}</strong>.
                    </div>
                @endif

                @if(!$proposalExistante && !$existant)
                <div class="row justify-content-center">
                    <div class="col-md-12">

                        {{-- Info candidat --}}
                        <div class="card mb-4" style="border-left:4px solid #006b08;">
                            <div class="card-body">
                                <div class="d-flex align-items-center" style="gap:14px;">
                                    @if($assignment->candidat->avatar)
                                        <img src="{{ Storage::url($assignment->candidat->avatar) }}"
                                            style="width:50px;height:50px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                    @else
                                        <div style="width:50px;height:50px;border-radius:50%;flex-shrink:0;
                                            background:#006b08;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user text-white fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="mb-0 font-weight-bold">{{ $assignment->candidat->name }}</p>
                                        <small class="text-muted">{{ $assignment->candidat->email }}</small>
                                    </div>

                                    {{-- Mode souhaité par le candidat --}}
                                    <div class="ml-auto">
                                        @php $demande = $assignment->diagnosticRequest; @endphp
                                        @if($demande?->mode_entretien === 'presentiel')
                                            <span class="badge badge-primary" style="font-size:12px; padding:6px 12px;">
                                                <i class="fas fa-map-marker-alt mr-1"></i>  Le candidat souhaite un entretien en présentiel
                                            </span>
                                        @elseif($demande?->mode_entretien === 'en_ligne')
                                            <span class="badge badge-primary" style="font-size:12px; padding:6px 12px;">
                                                @if($demande->plateforme_enligne === 'whatsapp')
                                                    <i class="fab fa-whatsapp mr-1"></i>
                                                   Le candidat souhaite un entretien via WhatsApp — {{ $demande->numero_whatsapp }}
                                                @elseif($demande->plateforme_enligne === 'appel_direct')
                                                    <i class="fas fa-phone mr-1"></i>
                                                     Le candidat souhaite un entretien via Appel direct — {{ $demande->numero_appel }}
                                                @elseif($demande->plateforme_enligne === 'google_meet')
                                                    <i class="fas fa-video mr-1"></i>
                                                      Le candidat souhaite un entretien via Google Meet
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Formulaire --}}
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clock mr-2 text-primary"></i>
                                    Les 3 horaires proposés
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST"
                                    action="{{ route('coach.appointments.store', $assignment) }}">
                                    @csrf

                                    {{-- Champ location si présentiel --}}
                                    @if($demande?->mode_entretien === 'presentiel')
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                <i class="fas fa-map-marker-alt mr-1 text-success"></i>
                                                Lieu de l'entretien <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="location"
                                                class="form-control @error('location') is-invalid @enderror"
                                                placeholder="Ex : Bureau principal, Salle de réunion 2..."
                                                value="{{ old('location') }}">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <hr>
                                    @endif

                                    {{-- Champ meeting_link si google meet --}}
                                    @if($demande?->plateforme_enligne === 'google_meet')
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                <i class="fas fa-video mr-1 text-danger"></i>
                                                Lien Google Meet <span class="text-danger">*</span>
                                            </label>
                                            <input type="url" name="meeting_link"
                                                class="form-control @error('meeting_link') is-invalid @enderror"
                                                placeholder="https://meet.google.com/xxx-xxxx-xxx"
                                                value="{{ old('meeting_link') }}">
                                            @error('meeting_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <hr>
                                    @endif

                                    {{-- Horaire 1 --}}
                                    <div class="card mb-3" style="border-left:4px solid #006b08;">
                                        <div class="card-body">
                                            <h6 class="font-weight-bold mb-3">
                                                <i class="fas fa-calendar-day mr-2 text-success"></i>
                                                Horaire 1
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="date_1"
                                                            class="form-control @error('date_1') is-invalid @enderror"
                                                            min="{{ now()->format('Y-m-d') }}"
                                                            value="{{ old('date_1') }}">
                                                        @error('date_1')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Heure <span class="text-danger">*</span></label>
                                                        <input type="time" name="heure_1"
                                                            class="form-control @error('heure_1') is-invalid @enderror"
                                                            value="{{ old('heure_1') }}">
                                                        @error('heure_1')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Horaire 2 --}}
                                    <div class="card mb-3" style="border-left:4px solid #17a2b8;">
                                        <div class="card-body">
                                            <h6 class="font-weight-bold mb-3">
                                                <i class="fas fa-calendar-day mr-2 text-info"></i>
                                                Horaire 2
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="date_2"
                                                            class="form-control @error('date_2') is-invalid @enderror"
                                                            min="{{ now()->format('Y-m-d') }}"
                                                            value="{{ old('date_2') }}">
                                                        @error('date_2')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Heure <span class="text-danger">*</span></label>
                                                        <input type="time" name="heure_2"
                                                            class="form-control @error('heure_2') is-invalid @enderror"
                                                            value="{{ old('heure_2') }}">
                                                        @error('heure_2')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Horaire 3 --}}
                                    <div class="card mb-3" style="border-left:4px solid #f4a900;">
                                        <div class="card-body">
                                            <h6 class="font-weight-bold mb-3">
                                                <i class="fas fa-calendar-day mr-2 text-warning"></i>
                                                Horaire 3
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="date_3"
                                                            class="form-control @error('date_3') is-invalid @enderror"
                                                            min="{{ now()->format('Y-m-d') }}"
                                                            value="{{ old('date_3') }}">
                                                        @error('date_3')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Heure <span class="text-danger">*</span></label>
                                                        <input type="time" name="heure_3"
                                                            class="form-control @error('heure_3') is-invalid @enderror"
                                                            value="{{ old('heure_3') }}">
                                                        @error('heure_3')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right mt-3">
                                        <a href="{{ route('coach.dashboard') }}"
                                            class="btn btn-secondary mr-2">
                                            <i class="fas fa-arrow-left mr-1"></i> Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane mr-1"></i>
                                            Envoyer les 3 horaires au candidat
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    @include('section.footer')
    @include('section.foot')
</body>
</html>