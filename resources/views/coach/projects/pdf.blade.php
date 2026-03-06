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

        @page {
            margin-bottom: 50px;
        }

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

        .objectif-box {
            border-left: 4px solid #006b08;
            padding: 10px 14px;
            margin-bottom: 12px;
            background: #f0f9f0;
            border-radius: 3px;
        }

        .objectif-box.orange {
            border-left-color: #f4a900;
            background: #fdf6e3;
        }

        .objectif-box.blue {
            border-left-color: #4e73df;
            background: #f0f4ff;
        }

        .objectif-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 5px;
        }

        .objectif-text {
            font-size: 11px;
            color: #444;
            line-height: 1.6;
        }

        .titre-projet {
            text-align: center;
            padding: 15px;
            background: #f0f9f0;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .titre-projet h2 {
            font-size: 16px;
            color: #006b08;
            margin-bottom: 8px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: bold;
            margin: 2px;
        }

        .badge-success {
            background: #1cc88a;
            color: #fff;
        }

        .badge-primary {
            background: #4e73df;
            color: #fff;
        }

        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }
    </style>
</head>

<body>

    <div class="page-footer clearfix">
        <div class="page-footer-left">
            Document confidentiel — Programme CLEE &copy; {{ date('Y') }} — Projet-{{ Str::slug($candidat->name) }}
        </div>
        <div class="page-number"></div>
    </div>

    {{-- EN-TÊTE --}}
    <div class="header clearfix">
        <div class="header-right">
            <p>Généré le {{ now()->format('d/m/Y') }}</p>
            <p>Programme CLEE</p>
        </div>
        <div class="header-logo">
            <img src="{{ public_path('assets/images/f-logo.png') }}" alt="Logo CLEE">
        </div>
        <div class="header-text">
            <h1>Projet Professionnel</h1>
            <p>Programme CLEE — Suivi de l'employabilité</p>
        </div>
    </div>

    <div class="content">

        {{-- IDENTITÉ --}}
        <div class="identity">
            <div class="identity-left">
                <div class="candidat-name">{{ $candidat->name }}</div>
                <div class="candidat-info">
                    {{ $candidat->email }} &nbsp;&nbsp; / &nbsp;&nbsp; {{ $candidat->phone }}
                </div>
                @if ($candidat->candidatAssignment?->coach)
                    <div class="candidat-info" style="margin-top:5px;">
                        Coach : <strong>{{ $candidat->candidatAssignment->coach->name }}</strong>
                        <p>
                            <small>{{ $candidat->candidatAssignment?->coach?->email ?? '—' }}  &nbsp;&nbsp;/ &nbsp;&nbsp;
                                {{ $candidat->candidatAssignment?->coach?->phone ?? '—' }}</small>
                        </p>
                    </div>
                @endif
            </div>
            <div class="identity-right">
                <span style="font-size:11px; color:#888;">Projet enregistré le</span><br>
                <span style="font-size:14px; font-weight:bold; color:#006b08;">
                    {{ $project->created_at->format('d/m/Y') }}
                </span>
                @if ($project->updated_at->ne($project->created_at))
                    <br>
                    <small style="font-size:10px; color:#aaa;">
                        Modifié le {{ $project->updated_at->format('d/m/Y') }}
                    </small>
                @endif
            </div>
        </div>

        {{-- INFOS PRINCIPALES --}}
        <div class="section">
            <div class="section-title">Projet professionnel</div>
            <table class="info-table">
                <tr>
                    <td>Titre du projet</td>
                    <td>{{ $project->titre_projet }}</td>
                </tr>
                <tr>
                    <td>Secteur cible</td>
                    <td>{{ $project->secteur_cible }}</td>
                </tr>
                <tr>
                    <td>Poste visé</td>
                    <td>{{ $project->poste_vise }}</td>
                </tr>
            </table>
        </div>

        {{-- DESCRIPTION --}}
        @if ($project->description)
            <div class="section">
                <div class="section-title">Description du projet</div>
                <div class="objectif-box">
                    <div class="objectif-text">{{ $project->description }}</div>
                </div>
            </div>
        @endif

        {{-- OBJECTIFS --}}
        @if ($project->objectif_court_terme || $project->objectif_long_terme)
            <div class="section">
                <div class="section-title">Objectifs</div>

                @if ($project->objectif_court_terme)
                    <div class="objectif-box orange">
                        <div class="objectif-label">⚡ Court terme</div>
                        <div class="objectif-text">{{ $project->objectif_court_terme }}</div>
                    </div>
                @endif

                @if ($project->objectif_long_terme)
                    <div class="objectif-box blue">
                        <div class="objectif-label">🎯 Long terme</div>
                        <div class="objectif-text">{{ $project->objectif_long_terme }}</div>
                    </div>
                @endif
            </div>
        @endif

    </div>
</body>

</html>
