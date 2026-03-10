<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
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
        .info-box .label { font-size: 12px; color: #999; margin-bottom: 2px; margin-top: 10px; }
        .info-box .label:first-child { margin-top: 0; }
        .info-box .value { font-weight: bold; font-size: 14px; color: #333; margin: 0; }
        .info-box .value-green { font-weight: bold; font-size: 14px; color: #006b08; margin: 0; }
        .mode-box {
            border-radius: 8px;
            padding: 15px 20px;
            margin: 15px 0;
        }
        .mode-presentiel { background: #d4edda; border-left: 4px solid #1cc88a; }
        .mode-enligne    { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        .mode-box p { margin: 4px 0; color: #333; font-size: 13px; }
        .mode-box .mode-title { font-weight: bold; font-size: 14px; margin-bottom: 8px; }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #f4a900;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
            margin: 20px 0;
        }
        .deadline-box {
            background: #e8f5e9;
            border-left: 4px solid #006b08;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #1b5e20;
            margin: 15px 0;
        }
        .btn-group { text-align: center; margin: 25px 0; }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            margin: 0 8px;
        }
        .btn-accept { background: #1cc88a; color: #fff; }
        .btn-reject { background: #e74a3b; color: #fff; }
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
            <p>Bonjour <strong>{{ $coachName }}</strong>,</p>
            <p>
                Une nouvelle affectation vous a été attribuée par l'administration.
                Le candidat suivant a besoin de votre accompagnement :
            </p>

            {{-- Infos candidat --}}
            <div class="info-box">
                <p class="label">Candidat</p>
                <p class="value-green">{{ $candidatName }}</p>

                @if($demande->candidat->candidatProfile)
                    @php $profile = $demande->candidat->candidatProfile; @endphp

                    <p class="label">Niveau d'étude</p>
                    <p class="value">{{ $profile->niveau_etude ?? '—' }}</p>

                    <p class="label">Domaine de formation</p>
                    <p class="value">{{ $profile->domaine_formation ?? '—' }}</p>

                    <p class="label">Situation actuelle</p>
                    <p class="value">
                        {{ $profile->situation_actuelle === 'autre'
                            ? $profile->situation_autre
                            : ($profile->situation_actuelle ?? '—') }}
                    </p>
                @endif

                <p class="label">Délai de réponse</p>
                <p class="value" style="color:#e74a3b;">{{ $expiresAt->format('d/m/Y à H:i') }}</p>
            </div>

            {{-- Mode d'entretien souhaité --}}
            @if($demande->mode_entretien)
                @if($demande->mode_entretien === 'presentiel')
                    <div class="mode-box mode-presentiel">
                        <p class="mode-title">📍 Entretien en présentiel</p>
                        <p>Le candidat souhaite être reçu en présentiel.</p>
                    </div>
                @else
                    <div class="mode-box mode-enligne">
                        <p class="mode-title">Le candidat souhaite son entretien en ligne</p>
                        @if($demande->plateforme_enligne === 'whatsapp')
                            <p>📱 Plateforme : <strong>WhatsApp</strong></p>
                            <p>Numéro WhatsApp : <strong>{{ $demande->numero_whatsapp }}</strong></p>
                        @elseif($demande->plateforme_enligne === 'appel_direct')
                            <p>📞 Mode : <strong>Appel direct</strong></p>
                            <p>Numéro joignable : <strong>{{ $demande->numero_appel }}</strong></p>
                        @elseif($demande->plateforme_enligne === 'google_meet')
                            <p>🎥 Plateforme : <strong>Google Meet</strong></p>
                            <p>Vous devrez envoyer le lien Meet au candidat lors de la confirmation.</p>
                        @endif
                    </div>
                @endif
            @endif

            {{-- Avertissement 8h --}}
            <div class="warning">
                ⚠️ Vous avez <strong>8 heures</strong> pour accepter ou rejeter cette affectation.
                Sans réponse de votre part, la demande sera automatiquement annulée
                et un autre coach sera contacté.
            </div>

            {{-- Info deadline entretien --}}
            <div class="deadline-box">
                📅 Si vous acceptez, vous devrez programmer l'entretien avec ce candidat
                dans un délai de <strong>7 jours ouvrables (dimanche exclu)</strong>.
                Si vous n'êtes pas disponible dans ce délai, veuillez <strong>rejeter</strong>
                cette affectation afin qu'un autre coach puisse prendre en charge ce candidat.
            </div>

            <p style="color:#999; font-size:13px;">
                Si vous avez des questions, contactez le secrétariat au 0162470707.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CLEE UAC STARTUP VALLEY — Tous droits réservés
        </div>

    </div>
</body>
</html>