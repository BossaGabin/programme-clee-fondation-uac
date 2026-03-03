{{-- resources/views/emails/otp.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Base */
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            background: #f4f4f4; 
            margin: 0 !important; 
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        .email-container {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .container { 
            max-width: 500px; 
            margin: 20px auto; 
            background: #fff; 
            border-radius: 8px; 
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header { 
            background: #006b08; 
            padding: 30px 20px; 
            text-align: center; 
        }
        
        .header h1 { 
            color: #fff; 
            margin: 0; 
            font-size: 22px;
            font-weight: bold;
        }
        
        .body { 
            padding: 30px 20px; 
            text-align: center; 
        }
        
        .body p {
            margin: 0 0 15px 0;
            font-size: 15px;
            line-height: 1.6;
            color: #333;
        }
        
        .otp-box { 
            display: inline-block; 
            background: #f0f4ff; 
            border: 2px dashed #006b08;
            border-radius: 8px; 
            padding: 15px 20px; 
            margin: 20px 0;
            max-width: 100%;
        }
        
        .otp-code { 
            font-size: 36px; 
            font-weight: bold; 
            color: #006b08; 
            letter-spacing: 8px;
            word-break: break-all;
        }
        
        .footer { 
            background: #f8f9fc; 
            padding: 20px; 
            text-align: center; 
            color: #999; 
            font-size: 12px;
            line-height: 1.5;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                margin: 10px auto !important;
                border-radius: 0 !important;
            }
            
            .header {
                padding: 25px 15px !important;
            }
            
            .header h1 {
                font-size: 20px !important;
            }
            
            .body {
                padding: 25px 15px !important;
            }
            
            .body p {
                font-size: 14px !important;
            }
            
            .otp-box {
                padding: 12px 15px !important;
                margin: 15px 0 !important;
            }
            
            .otp-code {
                font-size: 28px !important;
                letter-spacing: 4px !important;
            }
            
            .footer {
                padding: 15px !important;
                font-size: 11px !important;
            }
        }

        @media only screen and (max-width: 400px) {
            .otp-code {
                font-size: 24px !important;
                letter-spacing: 3px !important;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <div class="email-container">
                    <div class="container">
                        <div class="header">
                            <h1>Programme CLEE</h1>
                        </div>
                        <div class="body">
                            <p>Bonjour <strong>{{ $name }}</strong>,</p>
                            <p>Voici votre code de vérification :</p>
                            <div class="otp-box">
                                <div class="otp-code">{{ $otp }}</div>
                            </div>
                            <p>Ce code est valable pendant <strong>10 minutes</strong>.</p>
                            <p style="color:#999; font-size:13px; margin-top: 20px;">
                                Si vous n'avez pas créé de compte, ignorez cet email.
                            </p>
                        </div>
                        <div class="footer">
                            &copy; {{ date('Y') }} Programme CLEE — Tous droits réservés
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>