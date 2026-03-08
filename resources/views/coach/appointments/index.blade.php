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
                            <i class="fas fa-calendar-check mr-2"></i> Entretiens programmés
                        </h4>
                    </div>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if ($appointments->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">Aucun entretien programmé pour le moment.</p>
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
                                                    <th>Lieu / Lien</th>
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
                                                        <td>{{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i') }}
                                                        </td>
                                                        <td>
                                                            @if ($appointment->mode === 'presentiel')
                                                                <span class="badge badge-primary">
                                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                                    Présentiel
                                                                </span>
                                                            @else
                                                                <span class="badge badge-info">
                                                                    <i class="fas fa-video mr-1"></i> En ligne
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($appointment->mode === 'presentiel' && $appointment->location)
                                                                <small><i
                                                                        class="fas fa-map-marker-alt mr-1 text-muted"></i>{{ $appointment->location }}</small>
                                                            @elseif($appointment->mode === 'en_ligne' && $appointment->meeting_link)
                                                                <a href="{{ $appointment->meeting_link }}"
                                                                    target="_blank" class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                                    Rejoindre
                                                                </a>
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>
                                                        <td>
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
                                                                    data-name="{{ $appointment->coachAssignment->candidat->name }}">
                                                                    <i class="fas fa-times mr-1"></i> Annuler
                                                                </button>
                                                            </form>
                                                            {{-- <a href="{{ route('coach.interviews.start', $prochainEntretien) }}"
                                                                class="btn btn-success" title="Commencer l'entretien">
                                                                <i class="fas fa-play mr-1"></i> 
                                                            </a> --}}
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
