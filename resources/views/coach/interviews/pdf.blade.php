{{-- resources/views/coach/interviews/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#333; }
        
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

        .header { background:#006b08; color:#fff; padding:18px 30px; }
        .header h1 { font-size:20px; margin-bottom:4px; }
        
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
            float:right; 
            text-align:right; 
            font-size:11px; 
            opacity:0.9;
            padding-top: 6px;
        }
        
        .content { padding:25px 30px; }

        .candidat-box { 
            display:table; 
            width:100%; 
            border-bottom:2px solid #006b08; 
            padding-bottom:15px; 
            margin-bottom:20px; 
            page-break-inside: avoid;
        }
        .candidat-left { display:table-cell; vertical-align:middle; width:60%; }
        .candidat-right { display:table-cell; text-align:right; vertical-align:middle; width:40%; }
        .candidat-name { font-size:18px; font-weight:bold; color:#006b08; }
        .candidat-info { font-size:11px; color:#666; margin-top:4px; }

        .score-circle { 
            display:inline-block; 
            width:80px; 
            height:80px; 
            min-width: 80px;
            min-height: 80px;
            max-width: 80px;
            max-height: 80px;
            border-radius:50%;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            background: #baa505;
            color:#fff;
            text-align:center; 
            font-size:16px; 
            font-weight:bold; 
            line-height: 1.2;
            padding: 0;
            margin: 0;
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
        
        .score-circle small {
            font-size: 9px;
            opacity: 0.9;
            display: block;
            margin-top: 3px;
        }

        .section-title { 
            background:#006b08; 
            color:#fff; 
            padding:6px 12px; 
            font-size:12px; 
            font-weight:bold; 
            margin-bottom:10px; 
            margin-top:15px;
            border-radius:3px; 
        }

        .bloc-row { margin-bottom:12px; page-break-inside: avoid; }
        .bloc-label { font-size:11px; margin-bottom:4px; }
        .progress-bg { background:#eee; height:14px; border-radius:6px; }
        .progress-fill { height:14px; border-radius:6px; }
        .score-label { float:right; font-weight:bold; font-size:11px; }

        .two-col { display:table; width:100%; margin-top:15px; }
        .col-half { display:table-cell; width:48%; vertical-align:top; }
        .col-half:first-child { padding-right:10px; }
        .col-half:last-child { padding-left:10px; }

        .box { padding:12px; border-radius:4px; margin-bottom:10px; page-break-inside: avoid; }
        .box-success { background:#d4edda; border-left:4px solid #1cc88a; }
        .box-danger  { background:#f8d7da; border-left:4px solid #e74a3b; }
        .box-info    { background:#d1ecf1; border-left:4px solid #36b9cc; }

        .orientation-box { 
            text-align:center; 
            padding:15px; 
            border-radius:6px; 
            margin:15px 0;
            page-break-inside: avoid;
        }

        table.info-table { width:100%; border-collapse:collapse; }
        table.info-table td { padding:5px 8px; border-bottom:1px solid #eee; font-size:11px; }
        table.info-table td:first-child { color:#888; width:45%; }
        table.info-table td:last-child { font-weight:bold; }

        .clearfix::after { content:''; display:table; clear:both; }
    </style>
</head>
<body>

    @php
        $candidat   = $interview->appointment->coachAssignment->candidat;
        $profile    = $candidat->candidatProfile;
        $noteFinale = round($interview->total_score / 5);
        $orientation = match(true) {
            $noteFinale <= 7  => 'Renforcement compétences (formation de base)',
            $noteFinale <= 11 => 'Stage / immersion professionnelle',
            $noteFinale <= 15 => 'Insertion emploi accompagnée',
            default           => 'Insertion rapide / autonomie',
        };
        $scoreColor = $noteFinale >= 16 ? '#1cc88a' : ($noteFinale >= 12 ? '#36b9cc' : ($noteFinale >= 8 ? '#f6c23e' : '#e74a3b'));
        $blocColors = ['#006b08','#4e73df','#1cc88a','#f6c23e','#e74a3b'];
    @endphp

    {{-- Pied de page fixe sur toutes les pages --}}
    <div class="page-footer clearfix">
        <div class="page-footer-left">
            Document confidentiel — Programme CLEE &copy; {{ date('Y') }} — Rapport d'entretien de diagnostic du candidat <strong> {{ $candidat->name }}</strong>
        </div>
        <div class="page-number"></div>
    </div>

    <div class="header clearfix">
        <div class="header-right">
            <p>Rapport généré le {{ now()->format('d/m/Y') }}</p>
            <p>Coach : {{ $interview->appointment->coachAssignment->coach->name }}</p>
        </div>
        <div class="header-logo">
            <img src="{{ public_path('assets/images/f-logo.png') }}" alt="Logo CLEE">
        </div>
        <div class="header-text">
            <h1>Rapport d'entretien de diagnostic</h1>
            <p style="font-size:12px; opacity:0.85;">Programme CLEE — Suivi de l'employabilité</p>
        </div>
    </div>

    <div class="content">

        {{-- Candidat --}}
        <div class="candidat-box clearfix">
            <div class="candidat-left">
                <div class="candidat-name">{{ $candidat->name }}</div>
                <div class="candidat-info">
                    {{ $candidat->email }} &nbsp;|&nbsp; {{ $candidat->phone }}
                </div>
                <div class="candidat-info" style="margin-top:4px;">
                    Niveau : {{ $profile?->niveau_etude ?? '—' }} &nbsp;|&nbsp;
                    Domaine : {{ $profile?->domaine_formation ?? '—' }} &nbsp;|&nbsp;
                    Expérience : {{ $profile?->experience_years ?? '0' }} ans
                </div>
                <div class="candidat-info" style="margin-top:4px;">
                    Date entretien : {{ $interview->completed_at?->format('d/m/Y à H:i') }}
                </div>
            </div>
            <div class="candidat-right">
                <div class="score-circle" style="background:{{ $scoreColor }};">
                    <div class="score-content">
                        {{ $noteFinale }}/20
                        <small>{{ $interview->total_score }}/100</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orientation --}}
        <div class="orientation-box" style="background:{{ $scoreColor }}20; border:2px solid {{ $scoreColor }};">
            <strong style="font-size:13px; color:{{ $scoreColor }};">
                ORIENTATION RECOMMANDÉE : {{ strtoupper($orientation) }}
            </strong>
        </div>

        {{-- Scores par bloc --}}
        <div class="section-title">Résultats par bloc de compétences</div>
        @foreach($interview->scores->sortBy('competence.order') as $index => $score)
            @php $color = $blocColors[$index] ?? '#006b08'; $pct = ($score->note / 20) * 100; @endphp
            <div class="bloc-row">
                <div class="bloc-label clearfix">
                    {{ $score->competence->name }}
                    <span class="score-label">{{ $score->note }}/20</span>
                </div>
                <div class="progress-bg">
                    <div class="progress-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
                </div>
            </div>
        @endforeach

        <table class="info-table" style="margin-top:15px;">
            <tr>
                <td><strong>Total général</strong></td>
                <td><strong style="font-size:14px;">{{ $interview->total_score }}/100</strong></td>
            </tr>
            <tr>
                <td><strong>Note finale</strong></td>
                <td><strong style="font-size:16px; color:{{ $scoreColor }};">{{ $noteFinale }}/20</strong></td>
            </tr>
        </table>

        {{-- Points forts / faibles --}}
        <div class="two-col" style="margin-top:20px;">
            <div class="col-half">
                <div class="box box-success">
                    <p style="font-weight:bold; color:#155724; margin-bottom:6px;">
                        Points forts
                    </p>
                    <p style="font-size:11px; color:#555; line-height:1.6;">{{ $interview->strengths }}</p>
                </div>
            </div>
            <div class="col-half">
                <div class="box box-danger">
                    <p style="font-weight:bold; color:#721c24; margin-bottom:6px;">
                        Points à améliorer
                    </p>
                    <p style="font-size:11px; color:#555; line-height:1.6;">{{ $interview->weaknesses }}</p>
                </div>
            </div>
        </div>

        @if($interview->coach_summary)
            <div class="box box-info">
                <p style="font-weight:bold; color:#0c5460; margin-bottom:6px;">
                    Synthèse du coach
                </p>
                <p style="font-size:11px; color:#555; line-height:1.6;">{{ $interview->coach_summary }}</p>
            </div>
        @endif

    </div>
</body>
</html>