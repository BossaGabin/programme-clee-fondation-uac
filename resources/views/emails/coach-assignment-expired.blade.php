<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #006b08; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .header p { color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 14px; }
        .body { padding: 30px; }
        .body p { color: #555; line-height: 1.7; }
        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-box .label { font-size: 12px; color: #999; margin-bottom: 2px; }
        .info-box .value { font-weight: bold; font-size: 15px; color: #006b08; margin: 0 0 10px; }
        .warning {
            background: #f8d7da;
            border-left: 4px solid #e74a3b;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #721c24;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            margin: 15px 0;
            padding: 12px 30px;
            background: #006b08;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
        }
        .footer { background: #f8f9fc; padding: 15px; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">

        <div class="header">
            <h1>CLEE UAC STARTUP VALLEY</h1>
            <p>Plateforme de suivi à l'employabilité</p>
        </div>

        <div class="body">
            <p>Bonjour,</p>
            <p>
                L'affectation du candidat <strong>{{ $candidatName }}</strong>
                au coach <strong>{{ $coachName }}</strong> a <strong style="color:#e74a3b;">expiré</strong>
                sans réponse du coach dans le délai imparti de 8 heures.
            </p>

            <div class="info-box">
                <p class="label">Candidat concerné</p>
                <p class="value">{{ $candidatName }}</p>

                <p class="label">Coach n'ayant pas répondu</p>
                <p class="value">{{ $coachName }}</p>
            </div>

            <div class="warning">
                ⏰ Le délai de 8 heures est dépassé. Veuillez affecter
                un autre coach à ce candidat dans les meilleurs délais.
            </div>

            <div style="text-align:center;">
                <a href="{{ url('/admin/demandes') }}" class="btn">
                    Gérer les affectations
                </a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CLEE UAC STARTUP VALLEY — Tous droits réservés
        </div>

    </div>
</body>
</html>