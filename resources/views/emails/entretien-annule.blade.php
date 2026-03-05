<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #006b08; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 30px; }
        .body p { color: #555; line-height: 1.7; }
        .info-box {
            background: #f0f9f0;
            border-left: 4px solid #006b08;
            border-radius: 4px;
            padding: 18px 20px;
            margin: 20px 0;
        }
        .info-row { display: table; width: 100%; margin-bottom: 10px; }
        .info-label { display: table-cell; width: 40%; color: #888; font-size: 13px; }
        .info-value { display: table-cell; font-weight: bold; color: #333; font-size: 13px; }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #f4a900;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
        }
        .footer { background: #f8f9fc; padding: 15px; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Programme CLEE</h1>
        </div>
        <div class="body">
            <p>Bonjour <strong>{{ $candidatName }}</strong>,</p>
            <p>
                Votre coach <strong>{{ $coachName }}</strong> a annulé votre entretien de diagnostic
                prévu à la date suivante :
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">📅 Date</span>
                    <span class="info-value">{{ $date }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🕐 Heure</span>
                    <span class="info-value">{{ $heure }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">👤 Coach</span>
                    <span class="info-value">{{ $coachName }}</span>
                </div>
            </div>

            <div class="warning">
                ⚠️ Votre coach prendra contact avec vous prochainement pour convenir
                d'une nouvelle date d'entretien.
            </div>

            <p style="color:#999; font-size:13px; margin-top:20px;">
                Cet email a été envoyé automatiquement par la plateforme Programme CLEE.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Programme CLEE — Tous droits réservés
        </div>
    </div>
</body>
</html>