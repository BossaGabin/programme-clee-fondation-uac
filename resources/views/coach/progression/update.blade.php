{{-- resources/views/coach/progression/update.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Mise à jour progression</title>
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
                    <div class="col-md-8 align-self-center">
                        <h4 class="text-themecolor">
                            <i class="fas fa-chart-line mr-2"></i> Mise à jour — 
                            <strong>{{ $assignment->candidat->name }}</strong>
                        </h4>
                        <small class="text-muted">
                            Renseignez uniquement les blocs qui ont évolué lors de cette séance.
                        </small>
                    </div>
                    <div class="col-md-4 align-self-center text-right">
                        <a href="{{ route('coach.progression.show', $assignment) }}"
                            class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('error') }}
                    </div>
                @endif

                @php
                    $blocs = [
                        'bloc_a' => ['label' => 'Bloc A — Clarté du projet professionnel',        'color' => '#006b08', 'icon' => 'fa-bullseye'],
                        'bloc_b' => ['label' => 'Bloc B — Motivation & engagement',                'color' => '#4e73df', 'icon' => 'fa-fire'],
                        'bloc_c' => ['label' => 'Bloc C — Compétences & savoir-faire',             'color' => '#1cc88a', 'icon' => 'fa-tools'],
                        'bloc_d' => ['label' => 'Bloc D — Soft skills & posture professionnelle',  'color' => '#f6c23e', 'icon' => 'fa-user-tie'],
                        'bloc_e' => ['label' => 'Bloc E — Autonomie & préparation à l\'insertion', 'color' => '#e74a3b', 'icon' => 'fa-rocket'],
                    ];
                @endphp

                <form method="POST" action="{{ route('coach.progression.store', $assignment) }}">
                    @csrf

                    <div class="row justify-content-center">
                        <div class="col-md-10">

                            {{-- Scores actuels rappel --}}
                            <div class="card mb-4" style="border-left:4px solid #006b08;">
                                <div class="card-body py-3">
                                    <p class="font-weight-bold mb-2 text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Scores actuels du candidat
                                    </p>
                                    <div class="d-flex flex-wrap" style="gap:10px;">
                                        @foreach($blocs as $key => $bloc)
                                            @php
                                                $val    = $scores['current'][$key];
                                                $valide = $val === 20;
                                            @endphp
                                            <div class="text-center px-3 py-2"
                                                style="background:#f8f9fa; border-radius:8px;
                                                border-left:3px solid {{ $bloc['color'] }};">
                                                <small class="text-muted d-block">
                                                    {{ substr($bloc['label'], 0, 6) }}
                                                </small>
                                                <span class="font-weight-bold"
                                                    style="font-size:18px; color:{{ $bloc['color'] }};">
                                                    {{ $val }}/20
                                                </span>
                                                @if($valide)
                                                    <br><small class="text-success">
                                                        <i class="fas fa-check"></i> Validé
                                                    </small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Blocs --}}
                            @foreach($blocs as $key => $bloc)
                                @php
                                    $currentVal = $scores['current'][$key];
                                    $valide     = $currentVal === 20;
                                @endphp
                                <div class="card mb-3 {{ $valide ? 'opacity-50' : '' }}"
                                    style="border-left:4px solid {{ $bloc['color'] }};">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="font-weight-bold mb-0">
                                                <i class="fas {{ $bloc['icon'] }} mr-2"
                                                    style="color:{{ $bloc['color'] }}"></i>
                                                {{ $bloc['label'] }}
                                            </h6>
                                            @if($valide)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i> Validé 20/20
                                                </span>
                                            @else
                                                <span class="badge badge-light">
                                                    Score actuel : <strong>{{ $currentVal }}/20</strong>
                                                </span>
                                            @endif
                                        </div>

                                        @if($valide)
                                            <p class="text-muted mb-0" style="font-size:13px;">
                                                <i class="fas fa-lock mr-1"></i>
                                                Ce bloc est validé à 20/20, aucune modification possible.
                                            </p>
                                        @else
                                            <div class="d-flex align-items-center" style="gap:15px;">
                                                <label class="mb-0 text-muted" style="white-space:nowrap;">
                                                    Nouveau score :
                                                </label>
                                                <div class="d-flex flex-wrap" style="gap:8px;">
                                                    @for($i = $currentVal; $i <= 20; $i++)
                                                        <label style="cursor:pointer;">
                                                            <input type="radio"
                                                                name="{{ $key }}"
                                                                value="{{ $i }}"
                                                                style="display:none;"
                                                                class="score-radio-{{ $key }}"
                                                                {{ old($key) == $i ? 'checked' : '' }}>
                                                            <span class="score-btn-{{ $key }}"
                                                                data-val="{{ $i }}"
                                                                data-current="{{ $currentVal }}"
                                                                style="display:inline-block; padding:6px 12px;
                                                                border:2px solid {{ $i === $currentVal ? '#ced4da' : $bloc['color'] }};
                                                                border-radius:6px; font-size:14px; font-weight:bold;
                                                                background:{{ $i === $currentVal ? '#f8f9fa' : '#fff' }};
                                                                color:{{ $i === $currentVal ? '#999' : $bloc['color'] }};
                                                                transition:all 0.2s;">
                                                                {{ $i }}
                                                            </span>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            @error($key)
                                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                            @enderror
                                        @endif

                                    </div>
                                </div>
                            @endforeach

                            {{-- Note de séance --}}
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-comment-dots mr-2 text-info"></i>
                                        Observations de la séance <span class="text-danger">*</span>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <textarea name="note_seance" rows="4"
                                        class="form-control @error('note_seance') is-invalid @enderror"
                                        placeholder="Décrivez ce qui a été observé, travaillé ou discuté lors de cette séance..."
                                        style="min-height:100px;">{{ old('note_seance') }}</textarea>
                                    @error('note_seance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-right mt-3 mb-4">
                                <a href="{{ route('coach.progression.show', $assignment) }}"
                                    class="btn btn-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Enregistrer la progression
                                </button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @include('section.footer')

    <script>
        // Style boutons score au clic
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const key   = this.name;
                const val   = parseInt(this.value);
                const color = this.closest('.card').style.borderLeftColor;

                // Reset tous les boutons du même bloc
                document.querySelectorAll(`.score-btn-${key}`).forEach(btn => {
                    const btnVal     = parseInt(btn.dataset.val);
                    const currentVal = parseInt(btn.dataset.current);
                    if (btnVal === currentVal) {
                        btn.style.background   = '#f8f9fa';
                        btn.style.borderColor  = '#ced4da';
                        btn.style.color        = '#999';
                    } else {
                        btn.style.background  = '#fff';
                        btn.style.borderColor = color;
                        btn.style.color       = color;
                    }
                });

                // Activer le bouton sélectionné
                const selected = document.querySelector(
                    `.score-btn-${key}[data-val="${val}"]`
                );
                if (selected) {
                    selected.style.background  = color;
                    selected.style.borderColor = color;
                    selected.style.color       = '#fff';
                }
            });
        });
    </script>

    @include('section.foot')
</body>
</html>