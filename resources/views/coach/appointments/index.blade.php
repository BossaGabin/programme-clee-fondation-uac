{{-- resources/views/coach/appointments/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Entretiens programmés</title>
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

                <div class="row page-titles">
                    <div class="col-md-12 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-calendar-check mr-2"></i> Entretiens
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

                {{-- ============================================ --}}
                {{-- PROPOSITIONS EN ATTENTE                     --}}
                {{-- ============================================ --}}
                @if ($proposals->isNotEmpty())
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="border-left:4px solid #f4a900;">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-hourglass-half mr-2 text-warning"></i>
                                        Propositions en attente de confirmation
                                        <span class="badge badge-warning ml-2">{{ $proposals->count() }}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Candidat</th>
                                                    <th>Horaire 1</th>
                                                    <th>Horaire 2</th>
                                                    <th>Horaire 3</th>
                                                    <th>Mode</th>
                                                    <th>Envoyé le</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($proposals as $proposal)
                                                    @php $candidat = $proposal->coachAssignment->candidat; @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if ($candidat->avatar)
                                                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                                                        style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                                                                @else
                                                                    <div
                                                                        style="width:38px;height:38px;border-radius:50%;
                                                                        background:#f4a900;display:flex;
                                                                        align-items:center;justify-content:center;">
                                                                        <i class="fas fa-user text-white"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="mb-0 font-weight-bold">
                                                                        {{ $candidat->name }}</p>
                                                                    <small
                                                                        class="text-muted">{{ $candidat->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <small class="font-weight-bold text-success">
                                                                {{ \Carbon\Carbon::parse($proposal->date_1)->format('d/m/Y') }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($proposal->heure_1)->format('H:i') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <small class="font-weight-bold text-info">
                                                                {{ \Carbon\Carbon::parse($proposal->date_2)->format('d/m/Y') }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($proposal->heure_2)->format('H:i') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <small class="font-weight-bold text-warning">
                                                                {{ \Carbon\Carbon::parse($proposal->date_3)->format('d/m/Y') }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($proposal->heure_3)->format('H:i') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @if ($proposal->mode === 'presentiel')
                                                                <span class="badge badge-primary">
                                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                                    Présentiel
                                                                </span>
                                                                @if ($proposal->location)
                                                                    <br><small
                                                                        class="text-muted">{{ $proposal->location }}</small>
                                                                @endif
                                                            @else
                                                                <span class="badge badge-info">
                                                                    <i class="fas fa-video mr-1"></i> En ligne
                                                                </span>
                                                                <br>
                                                                @if ($proposal->plateforme_enligne === 'whatsapp')
                                                                    <small><i
                                                                            class="fab fa-whatsapp mr-1 text-success"></i>{{ $proposal->numero_whatsapp }}</small>
                                                                @elseif($proposal->plateforme_enligne === 'appel_direct')
                                                                    <small><i
                                                                            class="fas fa-phone mr-1 text-info"></i>{{ $proposal->numero_appel }}</small>
                                                                @elseif($proposal->plateforme_enligne === 'google_meet')
                                                                    <small><i
                                                                            class="fas fa-video mr-1 text-danger"></i>Google
                                                                        Meet</small>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $proposal->created_at->format('d/m/Y à H:i') }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ============================================ --}}
                {{-- ENTRETIENS CONFIRMÉS                        --}}
                {{-- ============================================ --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-calendar-check mr-2 text-success"></i>
                                    Entretiens confirmés
                                    @if ($appointments->isNotEmpty())
                                        <span class="badge badge-success ml-2">{{ $appointments->count() }}</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($appointments->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">Aucun entretien confirmé pour le moment.</p>
                                        <a href="{{ route('coach.dashboard') }}" class="btn btn-primary">
                                            <i class="fas fa-users mr-1"></i> Voir mes candidats
                                        </a>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Candidat</th>
                                                    <th>Date</th>
                                                    <th>Heure</th>
                                                    <th>Mode</th>
                                                    <th>Moyens</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($appointments as $appointment)
                                                    @php $candidat = $appointment->coachAssignment->candidat; @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                                @if ($candidat->avatar)
                                                                    <img src="{{ Storage::url($candidat->avatar) }}"
                                                                        style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                                                                @else
                                                                    <div
                                                                        style="width:38px;height:38px;border-radius:50%;
                                                                        background:#006b08;display:flex;
                                                                        align-items:center;justify-content:center;">
                                                                        <i class="fas fa-user text-white"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="mb-0 font-weight-bold">
                                                                        {{ $candidat->name }}</p>
                                                                    <small
                                                                        class="text-muted">{{ $candidat->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="font-weight-bold">
                                                                {{ \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y') }}
                                                            </span>
                                                            @if (\Carbon\Carbon::parse($appointment->scheduled_date)->isToday())
                                                                <span
                                                                    class="badge badge-success ml-1">Aujourd'hui</span>
                                                            @elseif(\Carbon\Carbon::parse($appointment->scheduled_date)->isTomorrow())
                                                                <span class="badge badge-warning ml-1">Demain</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i') }}
                                                        </td>
                                                        <td>
                                                            @if ($appointment->mode === 'presentiel')
                                                                <span class="badge badge-primary">
                                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                                    Présentiel
                                                                </span>
                                                            @else
                                                                <span class="badge badge-primary">
                                                                    <i class="fas fa-video mr-1"></i> En ligne
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($appointment->mode === 'presentiel' && $appointment->location)
                                                                <small>
                                                                    <i
                                                                        class="fas fa-map-marker-alt mr-1 text-muted"></i>
                                                                    {{ $appointment->location }}
                                                                </small>
                                                            @elseif($appointment->mode === 'en_ligne')
                                                                @php $proposal = $appointment->coachAssignment->appointmentProposal; @endphp
                                                                @if ($proposal?->plateforme_enligne === 'whatsapp')
                                                                    <small>
                                                                        <i
                                                                            class="fab fa-whatsapp mr-1 text-success"></i>
                                                                        Whatsapp - {{ $proposal->numero_whatsapp }}
                                                                    </small>
                                                                @elseif($proposal?->plateforme_enligne === 'appel_direct')
                                                                    <small>
                                                                        <i class="fas fa-phone mr-1 text-info"></i>
                                                                        Appel - {{ $proposal->numero_appel }}
                                                                    </small>
                                                                @elseif($proposal?->plateforme_enligne === 'google_meet' && $appointment->meeting_link)
                                                                    <a href="{{ $appointment->meeting_link }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-danger">
                                                                        <i class="fas fa-video mr-1"></i> Rejoindre
                                                                        Meet
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('coach.interviews.start', $appointment) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fas fa-play mr-1"></i> Commencer l'entretien
                                                            </a>
                                                            {{-- Reporter --}}
                                                            <a href="{{ route('coach.appointments.report', $appointment) }}"
                                                                class="btn btn-sm btn-warning mr-1" title="Reporter">
                                                                <i class="fas fa-calendar-alt mr-1"></i> Reporter
                                                            </a>
                                                            {{-- Annuler --}}
                                                            <form method="POST"
                                                                action="{{ route('coach.appointments.destroy', $appointment) }}"
                                                                class="form-annuler d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger btn-annuler"
                                                                    data-name="{{ $candidat->name }}">
                                                                    <i class="fas fa-times mr-1"></i> Annuler
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('section.footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-annuler').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const form = this.closest('.form-annuler');
                Swal.fire({
                    title: 'Annuler l\'entretien ?',
                    html: `Le candidat <strong>${name}</strong> sera informé par email.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-times"></i> Oui, annuler',
                    cancelButtonText: 'Retour',
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
