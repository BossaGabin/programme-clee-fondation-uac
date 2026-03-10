<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #006b08; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 30px; text-align: center; }
        .body p { color: #555; line-height: 1.6; }
        .badge {
            display: inline-block;
            margin: 20px 0;
            padding: 14px 35px;
            background: #006b08;
            color: #fff;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
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
            <p>Bonjour <strong>{{ $name }}</strong>,</p>
            <p>Nous avons bien reçu votre demande de diagnostic.</p>

            <p>
                L'équipe de la <strong style="color:#006b08;">Fondation UAC</strong> va étudier votre dossier et vous affecter
                un coach adapté à votre profil.<br>
                <strong>Une note de réponse vous sera envoyée d'ici peu.</strong>
            </p>
            <p style="color:#999; font-size:13px;">
                Si vous n'êtes pas à l'origine de cette demande,
                veuillez contacter le sécretariat au 0162470707.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CLEE UAC STARTUP VALLEY — Tous droits réservés
        </div>
    </div>
</body>
</html>