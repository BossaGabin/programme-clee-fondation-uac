<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* Pagination */
        @page {
            margin-bottom: 50px;
        }

        /* Pied de page fixe */
        .page-footer {
            position: fixed;
            bottom: 15px;
            left: 30px;
            right: 30px;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .page-footer-left {
            float: left;
            width: 85%;
        }

        .page-number {
            float: right;
            width: 15%;
            text-align: right;
            font-size: 10px;
            color: #888;
            font-weight: bold;
        }

        .page-number:before {
            content: counter(page);
        }

        .header {
            background: #006b08;
            color: #fff;
            padding: 18px 30px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.85;
        }

        .header-logo {
            float: left;
            margin-right: 15px;
        }

        .header-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 6px;
        }

        .header-text {
            overflow: hidden;
            padding-top: 6px;
        }

        .header-right {
            float: right;
            text-align: right;
            font-size: 11px;
            opacity: 0.9;
            padding-top: 6px;
        }

        .content {
            padding: 20px 30px;
        }

        /* Identité */
        .identity {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #006b08;
            padding-bottom: 15px;
        }

        .identity-left {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }

        .identity-right {
            display: table-cell;
            width: 30%;
            text-align: right;
            vertical-align: middle;
        }

        .candidat-name {
            font-size: 18px;
            font-weight: bold;
            color: #006b08;
        }

        .candidat-info {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }

        /* Sections */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
            padding-top: 15px;
        }

        .section-title {
            background: #006b08;
            color: #fff;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        /* Tableau infos */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        .info-table td:first-child {
            color: #888;
            width: 35%;
        }

        .info-table td:last-child {
            font-weight: bold;
        }

        /* Ligne infos perso + entretien */
        .row-two {
            width: 100%;
            margin-bottom: 20px;
        }

        .row-two-left {
            width: 100%;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .row-two-right {
            width: 100%;
            page-break-inside: avoid;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background: #1cc88a;
            color: #fff;
        }

        .badge-primary {
            background: #4e73df;
            color: #fff;
        }

        /* Score cercle */
        .score-box {
            text-align: center;
            margin-bottom: 10px;
        }

        .score-circle {
            display: inline-block;
            width: 80px;
            height: 80px;
            min-width: 80px;
            min-height: 80px;
            max-width: 80px;
            max-height: 80px;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            background: #baa505;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.2;
            padding: 0;
            margin: 0 auto;
            position: relative;
        }

        .score-content {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
        }

        .score-circle .score-small {
            font-size: 9px;
            opacity: 0.9;
            display: block;
            margin-top: 3px;
        }

        /* Compétences */
        .competence-row {
            display: table;
            width: 100%;
            margin-bottom: 7px;
        }

        .competence-name {
            display: table-cell;
            width: 48%;
            font-size: 11px;
            vertical-align: middle;
        }

        .competence-score {
            display: table-cell;
            width: 12%;
            font-size: 11px;
            font-weight: bold;
            vertical-align: middle;
            text-align: center;
        }

        .progress-bar-wrap {
            display: table-cell;
            width: 40%;
            vertical-align: middle;
            padding-left: 6px;
        }

        .progress-bg {
            background: #eee;
            height: 8px;
            border-radius: 4px;
        }

        .progress-fill {
            height: 8px;
            border-radius: 4px;
        }

        /* Timeline */
        .step {
            border-left: 3px solid #006b08;
            padding-left: 12px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .step-title {
            font-weight: bold;
            font-size: 11px;
        }

        .step-text {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
        }

        .footer-pdf {
            margin-top: 25px;
            padding-top: 10px;
            text-align: center;
            color: #aaa;
            font-size: 10px;
            /* Supprimé border-top car il est maintenant dans page-footer */
        }

        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }

        .page-spacer {
            height: 30px;
            page-break-after: avoid;
        }

        /* Progression */
        .progression-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .progression-table td {
            padding: 5px 6px;
            vertical-align: middle;
            font-size: 11px;
        }

        .prog-name {
            width: 36%;
        }

        .prog-initial {
            width: 10%;
            text-align: center;
            font-weight: bold;
            color: #888;
        }

        .prog-current {
            width: 10%;
            text-align: center;
            font-weight: bold;
        }

        .prog-diff {
            width: 10%;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        .prog-bar-wrap {
            width: 34%;
            padding-left: 6px;
        }

        .prog-bar-bg {
            background: #eee;
            height: 10px;
            border-radius: 5px;
            position: relative;
            overflow: hidden;
        }

        .prog-bar-initial {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            border-radius: 5px;
            background: #ced4da;
        }

        .prog-bar-current {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            border-radius: 5px;
            opacity: 0.9;
        }

        .prog-totaux {
            display: table;
            width: 100%;
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 8px;
        }

        .prog-totaux-left {
            display: table-cell;
            width: 50%;
            font-size: 11px;
            padding-right: 15px;
            border-right: 1px solid #eee;
        }

        .prog-totaux-right {
            display: table-cell;
            width: 50%;
            font-size: 11px;
            padding-left: 15px;
        }

        .prog-total-line {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .prog-total-label {
            display: table-cell;
            color: #555;
        }

        .prog-total-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .badge-prog {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-prog-success {
            background: #1cc88a;
            color: #fff;
        }

        .badge-prog-secondary {
            background: #ccc;
            color: #fff;
        }
    </style>
</head>

<body>

    {{-- Pied de page fixe sur toutes les pages --}}
    <div class="page-footer clearfix">
        <div class="page-footer-left">
            Document confidentiel — Programme CLEE &copy; {{ date('Y') }} —
            Fiche-Candidat-{{ Str::slug($candidat->name) }}
        </div>
        <div class="page-number"></div>
    </div>

    {{-- EN-TÊTE --}}
    <div class="header clearfix">
        <div class="header-right">
            <p>Fiche générée le {{ now()->format('d/m/Y') }}</p>
            <p>Programme CLEE</p>
        </div>
        <div class="header-logo">
            <img src="{{ public_path('assets/images/f-logo.png') }}" alt="Logo CLEE">
        </div>
        <div class="header-text">
            <h1>Fiche Candidat</h1>
            <p>Programme CLEE — Suivi de l'employabilité</p>
        </div>
    </div>

    <div class="content">

        {{-- IDENTITÉ --}}
        <div class="identity">
            <div class="identity-left">
                <div class="candidat-name">{{ $candidat->name }}</div>
                <div class="candidat-info">
                    {{ $candidat->email }} &nbsp;&nbsp; / {{ $candidat->phone }}
                </div><br>

                @if ($candidat->candidatAssignment?->coach)
                    <div class="candidat-info" style="margin-top:5px;">
                        Coach : <strong>{{ $candidat->candidatAssignment->coach->name }}</strong>
                    </div>
                    <div class="candidat-info">
                        {{ $candidat->candidatAssignment->coach->email }} &nbsp;&nbsp; /
                        {{ $candidat->candidatAssignment->coach->phone }}
                    </div>
                @endif
            </div>
            <div class="identity-right">
                @php $completion = $candidat->candidatProfile?->profile_completion ?? 0; @endphp
                <span style="font-size:24px; font-weight:bold; color:#006b08;">{{ $completion }}%</span><br>
                <small style="color:#888;">Profil complété</small>
            </div>
        </div>


        {{-- LIGNE 1 : Infos personnelles + Résultats entretien --}}
        <div class="row-two">

            {{-- Infos personnelles --}}
            <div class="row-two-left">
                <div class="section-title">Infos personnelles</div>
                @php $profile = $candidat->candidatProfile; @endphp
                <table class="info-table">
                    <tr>
                        <td>Date de naissance</td>
                        <td>
                            {{ $profile?->date_of_birth
                                ? ucfirst(\Carbon\Carbon::parse($profile->date_of_birth)->translatedFormat('d F Y'))
                                : '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Genre</td>
                        <td>{{ ucfirst($profile?->gender ?? '—') }}</td>
                    </tr>
                    <tr>
                        <td>Adresse</td>
                        <td>{{ $profile?->address ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td>Niveau d'étude</td>
                        <td>{{ $profile?->niveau_etude ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td>Domaine</td>
                        <td>{{ $profile?->domaine_formation ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td>Expérience</td>
                        <td>{{ $profile?->experience_years ?? '0' }} ans</td>
                    </tr>
                    <tr>
                        <td>Situation</td>
                        <td>{{ $profile?->situation_actuelle ?? '—' }}</td>
                    </tr>
                </table>
            </div>

            {{-- Résultats entretien --}}
            <div class="row-two-right">
                <div class="section-title">Résultats entretien</div>
                @if ($interview)
                    @php
                        $noteFinale = round($interview->total_score / 5);
                        $orientation = match (true) {
                            $noteFinale <= 7 => 'Renforcement compétences',
                            $noteFinale <= 11 => 'Stage / immersion',
                            $noteFinale <= 15 => 'Insertion accompagnée(Insertion à l\'emploi)',
                            default => 'Insertion rapide / autonomie (Auto emploi)',
                        };
                        $blocColors = ['#006b08', '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'];
                    @endphp
                    <div class="score-box">
                        <div class="score-circle">
                            <div class="score-content">
                                {{ $noteFinale }}/20
                                <span class="score-small">{{ $interview->total_score }}/100</span>
                            </div>
                        </div>
                        <p style="margin-top:6px; font-size:10px; font-weight:bold; color:#baa505;">
                            {{ $orientation }}
                        </p>
                    </div>
                    @foreach ($interview->scores->sortBy('competence.order') as $index => $score)
                        @php $color = $blocColors[$index] ?? '#006b08'; @endphp
                        <div class="competence-row">
                            <div class="competence-name">{{ $score->competence->name }}</div>
                            <div class="competence-score" style="color:{{ $color }};">{{ $score->note }}/20
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bg">
                                    <div class="progress-fill"
                                        style="width:{{ ($score->note / 20) * 100 }}%; background:{{ $color }};">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($interview->strengths)
                        <p style="margin-top:8px; font-size:10px;">
                            <strong style="color:#1cc88a;">Points forts :</strong> {{ $interview->strengths }}
                        </p>
                    @endif
                    @if ($interview->weaknesses)
                        <p style="margin-top:4px; font-size:10px;">
                            <strong style="color:#e74a3b;">Points faibles :</strong> {{ $interview->weaknesses }}
                        </p>
                    @endif
                    @if ($interview->coach_summary)
                        <p style="margin-top:4px; font-size:10px;">
                            <strong>Synthèse :</strong> {{ $interview->coach_summary }}
                        </p>
                    @endif
                @else
                    <p style="color:#aaa; font-size:11px; font-style:italic; margin-top:10px;">
                        Aucun entretien passé.
                    </p>
                @endif
            </div>

        </div>

        {{-- AJOUT : Espaceur avant les sections suivantes --}}
        <div class="page-spacer"></div>

        {{-- BESOIN PROFESSIONNEL --}}
        @if ($candidat->needAssignment)
            <div class="section">
                <div class="section-title">Besoin professionnel</div>
                @php
                    $besoin = $candidat->needAssignment;
                    $typeLabels = [
                        'stage' => 'Stage',
                        'insertion_emploi' => 'Insertion emploi',
                        'auto_emploi' => 'Auto-emploi',
                        'formation' => 'Formation',
                    ];
                @endphp
                <table class="info-table">
                    <tr>
                        <td>Type</td>
                        <td>{{ $typeLabels[$besoin->type] ?? $besoin->type }}</td>
                    </tr>
                    @if ($besoin->duration)
                        <tr>
                            <td>Durée</td>
                            <td>{{ $besoin->duration }}</td>
                        </tr>
                    @endif
                    @if ($besoin->program_start_date)
                        <tr>
                            <td>Début</td>
                            <td>{{ $besoin->program_start_date->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                    @if ($besoin->program_end_date)
                        <tr>
                            <td>Fin</td>
                            <td>{{ $besoin->program_end_date->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                </table>
                @if ($besoin->description)
                    <p style="margin-top:8px; font-size:11px; color:#555;">{{ $besoin->description }}</p>
                @endif
            </div>
        @endif

        {{-- PROJET PROFESSIONNEL --}}
        @if ($candidat->professionalProject)
            <div class="section">
                <div class="section-title">Projet professionnel</div>
                @php $projet = $candidat->professionalProject; @endphp
                <table class="info-table">
                    <tr>
                        <td>Titre</td>
                        <td>{{ $projet->titre_projet }}</td>
                    </tr>
                    <tr>
                        <td>Secteur cible</td>
                        <td>{{ $projet->secteur_cible ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td>Poste visé</td>
                        <td>{{ $projet->poste_vise ?? '—' }}</td>
                    </tr>
                </table>
                @if ($projet->description)
                    <p style="margin-top:8px; font-size:11px; color:#555;">{{ $projet->description }}</p>
                @endif
                @if ($projet->objectif_court_terme)
                    <p style="margin-top:6px; font-size:11px;">
                        <strong>Court terme :</strong> {{ $projet->objectif_court_terme }}
                    </p>
                @endif
                @if ($projet->objectif_long_terme)
                    <p style="margin-top:4px; font-size:11px;">
                        <strong>Long terme :</strong> {{ $projet->objectif_long_terme }}
                    </p>
                @endif
            </div>
        @endif

        {{-- PROGRESSION DES COMPÉTENCES --}}
        @if ($interview && $candidat->candidatAssignment)
            @php
                $assignment = $candidat->candidatAssignment;
                $assignment->load('progressionUpdates');
                $scores = $assignment->currentScores();

                $blocColors = [
                    1 => '#006b08',
                    2 => '#4e73df',
                    3 => '#1cc88a',
                    4 => '#f6c23e',
                    5 => '#e74a3b',
                ];
                $blocKeys = [
                    1 => 'bloc_a',
                    2 => 'bloc_b',
                    3 => 'bloc_c',
                    4 => 'bloc_d',
                    5 => 'bloc_e',
                ];
                $noteFinaleActuelle = $scores['total_current'];
                $prog = $scores['total_progression'];
            @endphp

            <div class="section">
                <div class="section-title">Progression des compétences</div>

                {{-- En-têtes colonnes --}}
                <table class="progression-table">
                    <tr>
                        <td class="prog-name">
                            <span style="font-size:10px; color:#888;">Compétence</span>
                        </td>
                        <td class="prog-initial">
                            <span style="font-size:10px; color:#888;">Initial</span>
                        </td>
                        <td class="prog-current">
                            <span style="font-size:10px; color:#888;">Actuel</span>
                        </td>
                        <td class="prog-diff">
                            <span style="font-size:10px; color:#888;">Évol.</span>
                        </td>
                        <td class="prog-bar-wrap">
                            <span style="font-size:10px; color:#888;">Progression</span>
                        </td>
                    </tr>
                </table>

                {{-- Ligne par bloc --}}
                @foreach ($interview->scores->sortBy('competence.order') as $score)
                    @php
                        $order = $score->competence->order;
                        $color = $blocColors[$order] ?? '#006b08';
                        $blocKey = $blocKeys[$order] ?? null;
                        $initial = $score->note;
                        $current = $blocKey ? $scores['current'][$blocKey] : $initial;
                        $diff = $blocKey ? $scores['progression'][$blocKey] : 0;
                        $pctInitial = ($initial / 20) * 100;
                        $pctCurrent = ($current / 20) * 100;
                        $valide = $current === 20;
                    @endphp

                    <table class="progression-table" style="border-bottom:1px solid #f5f5f5;">
                        <tr>
                            <td class="prog-name">
                                {{ $score->competence->name }}
                                @if ($valide)
                                    <span class="badge badge-success" style="margin-left:4px;">✓</span>
                                @endif
                            </td>
                            <td class="prog-initial" style="color:#888;">
                                {{ $initial }}/20
                            </td>
                            <td class="prog-current" style="color:{{ $color }};">
                                {{ $current }}/20
                            </td>
                            <td class="prog-diff">
                                @if ($diff > 0)
                                    <span class="badge-prog badge-prog-success">+{{ $diff }}</span>
                                @else
                                    <span class="badge-prog badge-prog-secondary">—</span>
                                @endif
                            </td>
                            <td class="prog-bar-wrap">
                                <div class="prog-bar-bg" style="height:10px;">
                                    {{-- Barre initiale gris --}}
                                    <div class="prog-bar-initial" style="width:{{ $pctInitial }}%;">
                                    </div>
                                    {{-- Barre actuelle colorée --}}
                                    <div class="prog-bar-current"
                                        style="width:{{ $pctCurrent }}%; background:{{ $color }};">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                @endforeach

                {{-- Totaux --}}
                <div class="prog-totaux">
                    <div class="prog-totaux-left">
                        <div class="prog-total-line">
                            <div class="prog-total-label">Score initial</div>
                            <div class="prog-total-value">{{ $scores['total_initial'] }}/100</div>
                        </div>
                    </div>
                    <div class="prog-totaux-right">
                        <div class="prog-total-line">
                            <div class="prog-total-label">Score actuel</div>
                            <div class="prog-total-value" style="color:#006b08;">
                                {{ $scores['total_current'] }}/100
                            </div>
                        </div>
                        <div class="prog-total-line">
                            <div class="prog-total-label">Progression globale</div>
                            <div class="prog-total-value" style="color:{{ $prog > 0 ? '#1cc88a' : '#888' }};">
                                {{ $prog > 0 ? '+' : '' }}{{ $prog }} pts
                            </div>
                        </div>
                        <div class="prog-total-line">
                            <div class="prog-total-label">Séances de suivi</div>
                            <div class="prog-total-value">
                                {{ $assignment->progressionUpdates->count() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        {{-- SUIVI DU PARCOURS --}}
        @if ($candidat->followUpSteps->isNotEmpty())
            <div class="section">
                <div class="section-title">Suivi du parcours</div>
                @foreach ($candidat->followUpSteps as $step)
                    <div class="step">
                        <div class="step-title">
                            {{ $step->title }}
                            <span
                                class="badge {{ $step->status === 'completed' ? 'badge-success' : 'badge-primary' }}"
                                style="margin-left:8px;">
                                {{ $step->status === 'completed' ? 'Terminé' : 'En cours' }}
                            </span>
                            <span style="color:#aaa; font-size:10px; margin-left:8px;">
                                {{ $step->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        @if ($step->description)
                            <div class="step-text">{{ $step->description }}</div>
                        @endif
                        @if ($step->result)
                            <div class="step-text" style="color:#006b08; font-style:italic;">
                                → {{ $step->result }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</body>

</html>
