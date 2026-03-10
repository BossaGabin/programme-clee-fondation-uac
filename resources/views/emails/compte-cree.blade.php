{{-- resources/views/emails/compte-cree.blade.php --}}
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
        .role-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .role-coach     { background: #fff3cd; color: #856404; }
        .role-candidat  { background: #d1ecf1; color: #0c5460; }
        .credentials-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box p { margin: 6px 0; color: #333; }
        .credentials-box .label { font-size: 12px; color: #999; margin-bottom: 2px; }
        .credentials-box .value { font-weight: bold; font-size: 15px; color: #006b08; }
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
        .warning {
            background: #fff3cd;
            border-left: 4px solid #f4a900;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
            margin-top: 15px;
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
            <p>Bonjour <strong>{{ $name }}</strong>,</p>

            @if($role === 'coach')
                <p>
                    Vous avez été inscrit sur la plateforme du <strong class="text-primary">CLEE UAC STARTUP VALLEY</strong>
                    en tant que <strong class="text-primary">Coach</strong>.<br>
                    Votre rôle est d'accompagner les candidats dans leur parcours vers l'emploi,
                    en réalisant des entretiens de diagnostic et en assurant leur suivi professionnel.
                </p>
           @elseif($role === 'admin')
            <p>
                    Vous avez été inscrit sur la plateforme du <strong class="text-primary">CLEE UAC STARTUP VALLEY</strong>
                    en tant qu'<strong class="text-primary">Administrateur</strong>.<br>
                    Vous avez accès à l'ensemble des fonctionnalités de la plateforme : gestion des utilisateurs,
                    validation des demandes, affectation des coachs et supervision du parcours des candidats.
                </p>
            @else
                <p>
                    Vous avez été inscrit sur la plateforme du <strong class="text-primary">CLEE UAC STARTUP VALLEY</strong>
                    en tant que <strong class="text-primary">Candidat</strong>.<br>
                    Cette plateforme vous permettra de suivre votre parcours vers l'emploi,
                    d'être accompagné par un coach dédié et de bénéficier d'un suivi personnalisé.
                </p>
            @endif

            <p>Voici vos identifiants de connexion :</p>

            <div class="credentials-box">
                <p class="label">Adresse email</p>
                <p class="value">{{ $email }}</p>

                <p class="label" style="margin-top:12px;">Mot de passe temporaire</p>
                <p class="value">{{ $password }}</p>
            </div>

            <div class="text-center" style="text-align:center;">
                <a href="{{ url('/login') }}" class="btn">
                    Se connecter à la plateforme
                </a>
            </div>

            <div class="warning">
                ⚠️ Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe
                dès votre première connexion.
            </div>

            <p style="color:#999; font-size:13px; margin-top:20px;">
                Si vous n'êtes pas à l'origine de cette inscription ou si vous avez des questions,
                contactez le sécretariat au 0162470707.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CLEE UAC STARTUP VALLE — Tous droits réservés
        </div>

    </div>
</body>
</html>