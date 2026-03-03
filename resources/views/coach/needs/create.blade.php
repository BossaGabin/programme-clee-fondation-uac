{{-- resources/views/coach/needs/create.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<title>CLEE - Assigner un besoin</title>
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
                            <i class="fas fa-bullseye mr-2"></i> Assigner un besoin professionnel
                        </h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>

                {{-- Bannière candidat + score --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card" style="border-left:4px solid #baa505;">
                            <div class="card-body d-flex align-items-center justify-content-between"
                                 style="padding:15px 20px;">
                                <div class="d-flex align-items-center" style="gap:14px;">
                                    @if($candidat->avatar)
                                        <img src="{{ Storage::url($candidat->avatar) }}"
                                             style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div style="width:50px;height:50px;border-radius:50%;background:#006b08;
                                                    display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user text-white fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0 font-weight-bold">{{ $candidat->name }}</h5>
                                        <small class="text-muted">
                                            {{ $candidat->candidatProfile?->niveau_etude ?? '—' }} —
                                            {{ $candidat->candidatProfile?->domaine_formation ?? '—' }}
                                        </small>
                                    </div>
                                </div>

                                {{-- Score entretien --}}
                                @if($interview)
                                    @php
                                        $noteFinale  = round($interview->total_score / 5);
                                        $scoreColor  = $noteFinale >= 16 ? '#1cc88a' : ($noteFinale >= 12 ? '#36b9cc' : ($noteFinale >= 8 ? '#f6c23e' : '#e74a3b'));
                                    @endphp
                                    <div class="text-center">
                                        <div style="width:65px;height:65px;border-radius:50%;background:{{ $scoreColor }};
                                                    display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                            <span style="color:#fff;font-size:16px;font-weight:bold;line-height:1.1;">
                                                {{ $noteFinale }}/20
                                            </span>
                                            <small style="color:rgba(255,255,255,0.85);font-size:10px;">
                                                {{ $interview->total_score }}/100
                                            </small>
                                        </div>
                                        <small class="text-muted d-block mt-1">Score entretien</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Suggestion automatique --}}
                @if($interview)
                    @php
                        $noteFinale  = round($interview->total_score / 5);
                        $suggestion  = match(true) {
                            $noteFinale <= 7  => 'formation',
                            $noteFinale <= 11 => 'stage',
                            $noteFinale <= 15 => 'insertion_emploi',
                            default           => 'auto_emploi',
                        };
                        $suggestionLabel = match($suggestion) {
                            'formation'        => 'Formation',
                            'stage'            => 'Stage / immersion',
                            'insertion_emploi' => 'Insertion emploi accompagnée',
                            'auto_emploi'      => 'Auto-emploi / insertion rapide',
                        };
                        $suggestionColor = match($suggestion) {
                            'formation'        => 'info',
                            'stage'            => 'primary',
                            'insertion_emploi' => 'success',
                            'auto_emploi'      => 'warning',
                        };
                    @endphp
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-{{ $suggestionColor }} d-flex align-items-center"
                                 style="gap:12px;">
                                <i class="fas fa-magic fa-lg"></i>
                                <div>
                                    <strong>Suggestion automatique basée sur la note {{ $noteFinale }}/20 :</strong>
                                    <span class="badge badge-{{ $suggestionColor }} ml-2"
                                          style="font-size:13px; padding:5px 12px;">
                                        {{ $suggestionLabel }}
                                    </span>
                                    <br>
                                    <small>Vous pouvez modifier si nécessaire avant de confirmer.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @php $suggestion = old('type', 'stage'); @endphp
                @endif

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bullseye mr-2 text-success"></i>
                                    Confirmer et l'orientation
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('coach.needs.store', $candidat) }}">
                                    @csrf

                                    {{-- Type de besoin --}}
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Type de besoin <span class="text-danger">*</span>
                                        </label>
                                        <div class="row mt-2">
                                            @foreach([
                                                'stage'            => ['label' => 'Stage',                   'icon' => 'fa-briefcase',       'color' => '#4e73df'],
                                                'insertion_emploi' => ['label' => 'Insertion emploi',         'icon' => 'fa-handshake',       'color' => '#1cc88a'],
                                                'formation'        => ['label' => 'Formation',                'icon' => 'fa-graduation-cap',  'color' => '#36b9cc'],
                                                'auto_emploi'      => ['label' => 'Auto-emploi',              'icon' => 'fa-store',           'color' => '#f6c23e'],
                                            ] as $val => $info)
                                                <div class="col-md-6 mb-2">
                                                    <label class="need-card w-100"
                                                           style="cursor:pointer; border:2px solid #dee2e6; border-radius:8px;
                                                                  padding:12px 15px; display:flex; align-items:center; gap:12px;
                                                                  transition:all 0.2s;">
                                                        <input type="radio" name="type" value="{{ $val }}"
                                                               class="need-radio"
                                                               style="display:none;"
                                                               {{ old('type', $suggestion) === $val ? 'checked' : '' }}>
                                                        <div style="width:40px;height:40px;border-radius:50%;
                                                                    background:{{ $info['color'] }}22;
                                                                    display:flex;align-items:center;justify-content:center;
                                                                    flex-shrink:0;">
                                                            <i class="fas {{ $info['icon'] }}"
                                                               style="color:{{ $info['color'] }};"></i>
                                                        </div>
                                                        <span class="font-weight-bold">{{ $info['label'] }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Description --}}
                                    <div class="form-group">
                                        <label class="font-weight-bold">Description / Appréciation</label>
                                        <textarea name="description" class="form-control" rows="3"
                                                  placeholder="Précisez le besoin, le contexte, les attentes..." style="min-height: 100px">{{ old('description') }}</textarea>
                                    </div>

                                    {{-- Durée --}}
                                    <div class="form-group">
                                        <label class="font-weight-bold">Durée estimée</label>
                                        <input type="text" name="duration" class="form-control"
                                               placeholder="Ex: 3 mois, 6 semaines, 1 an..."
                                               value="{{ old('duration') }}">
                                    </div>

                                    {{-- Dates --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    Date de début
                                                </label>
                                                <input type="date" name="program_start_date"
                                                       class="form-control @error('program_start_date') is-invalid @enderror"
                                                       value="{{ old('program_start_date', now()->format('Y-m-d')) }}">
                                                @error('program_start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    Date de fin <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" name="program_end_date"
                                                       class="form-control @error('program_end_date') is-invalid @enderror"
                                                       value="{{ old('program_end_date') }}">
                                                @error('program_end_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right mt-3">
                                        <a href="{{ route('coach.candidats.show', $candidat) }}"
                                           class="btn btn-outline-secondary mr-2">
                                            Annuler
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check-circle mr-1"></i> Confirmer le besoin
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
    @include('section.foot')
</body>

<script>
    // Style des cartes de choix
    function updateCards() {
        document.querySelectorAll('.need-card').forEach(card => {
            const radio = card.querySelector('.need-radio');
            if (radio.checked) {
                card.style.borderColor = '#006b08';
                card.style.background  = '#f0f9f0';
            } else {
                card.style.borderColor = '#dee2e6';
                card.style.background  = '#fff';
            }
        });
    }

    document.querySelectorAll('.need-radio').forEach(radio => {
        radio.addEventListener('change', updateCards);
    });

    // Init au chargement
    updateCards();
</script>

</html>