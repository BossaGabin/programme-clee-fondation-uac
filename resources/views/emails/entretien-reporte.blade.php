<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { padding: 20px 10px; }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
        }
        .header { background: #006b08; padding: 30px 20px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 30px 25px; }
        .body p { color: #555; line-height: 1.7; margin: 0 0 12px 0; }
        .info-box {
            background: #f0f9f0;
            border-left: 4px solid #006b08;
            border-radius: 4px;
            padding: 18px 20px;
            margin: 15px 0;
        }
        .info-box-old {
            background: #fdf6e3;
            border-left: 4px solid #f4a900;
            border-radius: 4px;
            padding: 18px 20px;
            margin: 15px 0;
        }
        .info-row { display: table; width: 100%; margin-bottom: 10px; }
        .info-label {
            display: table-cell;
            width: 40%;
            color: #888;
            font-size: 13px;
            vertical-align: middle;
            padding-right: 8px;
        }
        .info-value {
            display: table-cell;
            font-weight: bold;
            color: #333;
            font-size: 13px;
            vertical-align: middle;
            word-break: break-word;
        }
        .info-value-old {
            display: table-cell;
            font-size: 13px;
            text-decoration: line-through;
            color: #aaa;
            vertical-align: middle;
        }
        .btn {
            display: block;
            margin: 20px auto;
            padding: 12px 30px;
            background: #006b08;
            color: #fff !important;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            width: fit-content;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #f4a900;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
            line-height: 1.6;
        }
        .section-label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #aaa;
            margin: 16px 0 4px 0;
        }
        .footer {
            background: #f8f9fc;
            padding: 15px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        @media screen and (max-width: 540px) {
            .wrapper { padding: 0; }
            .container { border-radius: 0; }
            .body { padding: 20px 15px; }
            .header { padding: 22px 15px; }
            .header h1 { font-size: 18px; }
            .info-label { font-size: 12px; width: 42%; }
            .info-value { font-size: 13px; }
            .btn { width: 100%; font-size: 14px; padding: 13px 20px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Programme CLEE</h1>
            </div>
            <div class="body">
                <p>Bonjour <strong>{{ $candidatName }}</strong>,</p>
                <p>
                    Votre coach <strong>{{ $coachName }}</strong> a reporté votre entretien de diagnostic
                    à une nouvelle date. Voici les informations :
                </p>

                <p class="section-label">🗓 Ancienne date</p>
                <div class="info-box-old">
                    <div class="info-row">
                        <span class="info-label">📅 Date</span>
                        <span class="info-value-old">{{ $ancienneDate }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">🕐 Heure</span>
                        <span class="info-value-old">{{ $ancienneHeure }}</span>
                    </div>
                </div>

                <p class="section-label">✅ Nouvelle date</p>
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">📅 Date</span>
                        <span class="info-value">{{ $nouvelleDate }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">🕐 Heure</span>
                        <span class="info-value">{{ $nouvelleHeure }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📍 Mode</span>
                        <span class="info-value">{{ $mode === 'presentiel' ? 'Présentiel' : 'En ligne' }}</span>
                    </div>
                    @if($mode === 'presentiel' && $location)
                        <div class="info-row">
                            <span class="info-label">🏢 Lieu</span>
                            <span class="info-value">{{ $location }}</span>
                        </div>
                    @endif
                    @if($mode === 'en_ligne' && $meetingLink)
                        <div class="info-row">
                            <span class="info-label">🔗 Lien</span>
                            <span class="info-value">
                                <a href="{{ $meetingLink }}" style="color:#006b08; word-break:break-all;">
                                    Rejoindre la réunion
                                </a>
                            </span>
                        </div>
                    @endif
                </div>

                @if($mode === 'en_ligne' && $meetingLink)
                    <div style="text-align:center;">
                        <a href="{{ $meetingLink }}" class="btn">Rejoindre la réunion</a>
                    </div>
                @endif

                <div class="warning">
                    ⚠️ Merci de noter cette nouvelle date dans votre agenda. En cas d'empêchement,
                    contactez votre coach au plus vite.
                </div>

                <p style="color:#999; font-size:13px; margin-top:20px;">
                    Cet email a été envoyé automatiquement par la plateforme Programme CLEE.
                </p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Programme CLEE — Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>