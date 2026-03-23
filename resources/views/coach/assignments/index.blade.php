{{-- resources/views/coach/assignments/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Mes affectations</title>
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
                    <div class="col-md-12 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-user-clock mr-2"></i> Affectations en attente
                        </h4>
                        <small class="text-muted">Vous avez 24h pour accepter ou rejeter chaque affectation</small>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <strong>Bravo!</strong> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <strong>Erreur!</strong> {{ session('error') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('info') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        @forelse($pendingAssignments as $assignment)
                            <div class="card mb-3" style="border-left: 4px solid #f4a900;">
                                <div class="card-body">
                                    <div class="row align-items-center">

                                        {{-- Info candidat --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center" style="gap:14px;">
                                                @if($assignment->candidat->avatar)
                                                    <img src="{{ Storage::url($assignment->candidat->avatar) }}"
                                                        style="width:52px;height:52px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                                @else
                                                    <div style="width:52px;height:52px;border-radius:50%;flex-shrink:0;
                                                        background:#006b08;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-user text-white fa-lg"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="mb-0 font-weight-bold" style="font-size:15px;">
                                                        {{ $assignment->candidat->name }}
                                                    </p>
                                                    <small class="text-muted">{{ $assignment->candidat->email }}</small>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $assignment->candidat->phone ?? '—' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Délai restant --}}
                                        <div class="col-md-3 text-center">
                                            <small class="text-muted d-block">Expire le</small>
                                            <span class="font-weight-bold text-danger" style="font-size:14px;">
                                                {{ \Carbon\Carbon::parse($assignment->expires_at)->format('d/m/Y à H:i') }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                (dans {{ \Carbon\Carbon::parse($assignment->expires_at)->diffForHumans() }})
                                            </small>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="col-md-3 text-right">
                                            <form method="POST"
                                                action="{{ route('coach.assignments.accept', $assignment) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm mr-1">
                                                    <i class="fas fa-check mr-1"></i> Accepter
                                                </button>
                                            </form>

                                            {{-- Bouton qui ouvre le modal --}}
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-open-reject"
                                                data-id="{{ $assignment->id }}"
                                                data-name="{{ $assignment->candidat->name }}">
                                                <i class="fas fa-times mr-1"></i> Rejeter
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card">
                                <div class="card-body text-center text-muted py-5">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                    <p class="mb-0">Aucune affectation en attente.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL REJET                                 --}}
    {{-- ============================================ --}}
    <div class="modal fade" id="modalRejet" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle mr-2 text-danger"></i>
                        Rejeter l'affectation
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" id="formRejet" action="">
                    @csrf
                    <div class="modal-body">

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Vous êtes sur le point de rejeter l'affectation de
                            <strong id="modalCandidatName"></strong>.
                            L'administrateur sera notifié pour affecter un autre coach.
                        </div>

                        <div class="form-group">
                            <label for="reason" class="font-weight-bold">
                                Raison du rejet <span class="text-danger">*</span>
                            </label>
                            <textarea id="reason" name="reason" rows="5"
                                class="form-control"
                                placeholder="Expliquez pourquoi vous ne pouvez pas prendre en charge ce candidat...
Ex : Indisponibilité pour les prochaines semaines, surcharge de candidats, incompatibilité de profil..."></textarea>
                            <small class="text-muted">Minimum 10 caractères.</small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-arrow-left mr-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times mr-1"></i> Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('section.footer')
    @include('section.foot')

    <script>
        document.querySelectorAll('.btn-open-reject').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id   = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                // Mettre à jour le nom du candidat dans le modal
                document.getElementById('modalCandidatName').textContent = name;

                // Mettre à jour l'action du formulaire dynamiquement
                document.getElementById('formRejet').action = '/coach/assignments/' + id + '/reject';

                // Vider le textarea
                document.getElementById('reason').value = '';

                // Ouvrir le modal
                $('#modalRejet').modal('show');
            });
        });
    </script>

</body>
</html>