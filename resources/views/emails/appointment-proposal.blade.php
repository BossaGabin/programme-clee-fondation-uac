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
        .slot {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: 4px solid #006b08;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 12px 0;
        }
        .slot .slot-title { font-weight: bold; color: #006b08; margin-bottom: 6px; font-size: 14px; }
        .slot p { margin: 3px 0; color: #555; font-size: 13px; }
        .mode-box {
            background: #e8f5e9;
            border-left: 4px solid #006b08;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #1b5e20;
            margin: 20px 0;
        }
        .btn {
            display: block;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            margin: 8px 0;
        }
        .btn-1 { background: #006b08; color: #fff; }
        .btn-2 { background: #17a2b8; color: #fff; }
        .btn-3 { background: #f4a900; color: #fff; }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #f4a900;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
            margin: 20px 0;
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
            <p>Bonjour <strong>{{ $candidatName }}</strong>,</p>
            <p>
                Votre coach <strong>{{ $coachName }}</strong> vous propose
                3 horaires pour votre entretien de diagnostic.
                Veuillez cliquer sur le bouton correspondant à votre disponibilité.
            </p>

            {{-- Horaire 1 --}}
            <div class="slot">
                <p class="slot-title">📅 Horaire 1</p>
                <p>📆 {{ \Carbon\Carbon::parse($proposal->date_1)->format('l d F Y') }}</p>
                <p>🕐 {{ \Carbon\Carbon::parse($proposal->heure_1)->format('H:i') }}</p>
            </div>

            {{-- Horaire 2 --}}
            <div class="slot">
                <p class="slot-title">📅 Horaire 2</p>
                <p>📆 {{ \Carbon\Carbon::parse($proposal->date_2)->format('l d F Y') }}</p>
                <p>🕐 {{ \Carbon\Carbon::parse($proposal->heure_2)->format('H:i') }}</p>
            </div>

            {{-- Horaire 3 --}}
            <div class="slot">
                <p class="slot-title">📅 Horaire 3</p>
                <p>📆 {{ \Carbon\Carbon::parse($proposal->date_3)->format('l d F Y') }}</p>
                <p>🕐 {{ \Carbon\Carbon::parse($proposal->heure_3)->format('H:i') }}</p>
            </div>

            {{-- Mode d'entretien --}}
            <div class="mode-box">
                @if($proposal->mode === 'presentiel')
                    📍 <strong>Entretien en présentiel</strong><br>
                    Lieu : {{ $proposal->location ?? 'À confirmer' }}
                @else
                    💻 <strong>Entretien en ligne</strong> —
                    @if($proposal->plateforme_enligne === 'whatsapp')
                        📱 WhatsApp : <strong>{{ $proposal->numero_whatsapp }}</strong>
                    @elseif($proposal->plateforme_enligne === 'appel_direct')
                        📞 Appel direct : <strong>{{ $proposal->numero_appel }}</strong>
                    @elseif($proposal->plateforme_enligne === 'google_meet')
                        🎥 Google Meet — Le lien vous sera envoyé après confirmation
                    @endif
                @endif
            </div>

            <div class="warning">
                ⚠️ Pour confirmer votre horaire, veuillez vous connecter sur la plateforme
                <a href="{{ url('/login') }}" style="color:#856404; font-weight:bold;">CLEE UAC STARTUP VALLEY</a>
                et choisir l'horaire qui vous convient depuis votre tableau de bord.
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