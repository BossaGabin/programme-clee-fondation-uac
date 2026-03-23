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
        .btn {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 30px;
            background: #006b08;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
        }
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
            <h1>CLEE UAC STARTUP VALLEY</h1>
        </div>
        <div class="body">
            <p>Bonjour <strong>{{ $candidatName }}</strong>,</p>
            <p>
                Votre coach <strong>{{ $coachName }}</strong> a programmé un entretien de diagnostic
                avec vous. Voici les informations :
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
                    <span class="info-label">📍 Mode</span>
                    <span class="info-value">{{ $mode === 'presentiel' ? 'Présentiel' : 'En ligne' }}</span>
                </div>
                @if($mode === 'presentiel' && $location)
                    <div class="info-row">
                        <span class="info-label">🏢 Lieu</span>
                        <span class="info-value">{{ $location }}</span>
                    </div>
                @endif
                @if($mode === 'en_ligne')
                    @if($plateforme === 'whatsapp')
                        <div class="info-row">
                            <span class="info-label">📱 WhatsApp</span>
                            <span class="info-value">{{ $numeroWhatsapp }}</span>
                        </div>
                    @elseif($plateforme === 'appel_direct')
                        <div class="info-row">
                            <span class="info-label">📞 Appel direct – Le coach vous contactera par appel sur votre numéro <strong>{{ $numeroAppel }}</strong></span>
                        </div>
                    @elseif($plateforme === 'google_meet' && $meetingLink)
                        <div class="info-row">
                            <span class="info-label">🎥 Google Meet</span>
                            <span class="info-value">
                                <a href="{{ $meetingLink }}" style="color:#006b08;">Rejoindre la réunion</a>
                            </span>
                        </div>
                    @endif
                @endif
            </div>

            @if($mode === 'en_ligne' && $meetingLink)
                <div style="text-align:center;">
                    <a href="{{ $meetingLink }}" class="btn">Rejoindre la réunion</a>
                </div>
            <div class="warning">
                ⚠️ Si vous pouvez pas rejoindre la réunion par meet appellez nous au 0146464693.
            </div>
            @endif

            <div class="warning">
                ⚠️ Merci d'être disponible à l'heure indiquée. En cas d'empêchement,
                contactez votre coach et l'administration au plus vite au 0162470707.
            </div>

            <p style="color:#999; font-size:13px; margin-top:20px;">
                Cet email a été envoyé automatiquement depuis la plateforme du CLEE UAC STARTUP VALLEY.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CLEE UAC STARTUP VALLEY — Tous droits réservés
        </div>
    </div>
</body>
</html>