{{-- resources/views/emails/reset-password.blade.php --}}
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
        .btn {
            display: inline-block;
            margin: 25px 0;
            padding: 14px 35px;
            background: #006b08;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn:hover { background: #baa505; }
        .expire { color: #999; font-size: 13px; margin-top: 10px; }
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
            <p>Vous avez demandé la réinitialisation de votre mot de passe.<br>
               Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe.</p>

            <a href="{{ $url }}" class="btn">Réinitialiser mon mot de passe</a>

            <p class="expire">Ce lien expire dans <strong>60 minutes</strong>.</p>
            <p style="color:#999; font-size:13px;">
                Si vous n'avez pas demandé de réinitialisation, ignorez cet email.<br>
                Votre mot de passe restera inchangé.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CLEE UAC STARTUP VALLEY — Tous droits réservés
        </div>
    </div>
</body>
</html>