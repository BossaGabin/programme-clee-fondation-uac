<!DOCTYPE html>
<html lang="en" class="h-100" id="login-page1">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CLEE - S'inscrire</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <!-- Custom Stylesheet -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/modernizr-3.6.0.min.js') }}"></script>

</head>

<body class="h-100">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
 <div class="login-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-5">
                <div class="form-input-content">
                    <div class="card">
                        <div class="card-body">
                            <div class="logo d-flex justify-content-center align-items-center text-center" style="gap: 30px;">
                                <a href="/">
                                    <img src="{{ asset('assets/images/f-logo.png') }}" class="img-fluid"
                                        style="width:100px; height:100px; object-fit:contain;">
                                </a>
                                <a href="/">
                                    <img src="{{ asset('assets/images/logo-usv.jpg') }}" class="img-fluid"
                                        style="width:100px; height:100px; object-fit:contain;">
                                </a>
                                <a href="/">
                                    <img src="{{ asset('assets/images/f-logo-1.png') }}" class="img-fluid"
                                        style="width:100px; height:100px; object-fit:contain;">
                                </a>
                            </div>

                            <h4 class="text-center m-t-15">Vérification Email</h4>
                            <p class="text-center text-muted">
                                Un code à 6 chiffres a été envoyé à votre adresse email.<br>
                                <strong>Il expire dans 10 minutes.</strong>
                            </p>

                            {{-- Message succès (renvoi) --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            {{-- Erreurs --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('otp.verify') }}" class="m-t-30 m-b-20" id="otp-form">
                                @csrf

                                {{-- Champ caché qui reçoit le code assemblé --}}
                                <input type="hidden" name="otp" id="otp-hidden">

                                <div class="d-flex justify-content-center" style="gap: 10px;">
                                    @for ($i = 1; $i <= 6; $i++)
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            maxlength="1"
                                            class="otp-input form-control text-center"
                                            style="width: 50px; height: 55px; font-size: 24px; font-weight: bold;"
                                            autocomplete="off"
                                            id="otp-{{ $i }}"
                                        >
                                    @endfor
                                </div>

                                <div class="text-center m-t-25 m-b-10">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Vérifier le code
                                    </button>
                                </div>
                            </form>

                            {{-- Renvoi du code --}}
                            <form method="POST" action="{{ route('otp.resend') }}" class="text-center">
                                @csrf
                                <p class="text-muted">Vous n'avez pas reçu le code ?
                                    <button type="submit" class="btn btn-link p-0">Renvoyer</button>
                                </p>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- #/ container -->
    <!-- Common JS -->
    <script src="{{ asset('assets/plugins/common/common.min.js') }}"></script>
    <!-- Custom script -->
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    
    <script>
        const inputs = document.querySelectorAll('.otp-input');
    
        inputs.forEach((input, index) => {
    
            // Autoriser seulement les chiffres
            input.addEventListener('keypress', function (e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
    
            // Passer au champ suivant automatiquement
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
    
            // Gérer la touche Backspace
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                }
            });
    
            // Gérer le coller (paste) du code complet
            input.addEventListener('paste', function (e) {
                e.preventDefault();
                const paste = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                if (paste.length === 6) {
                    inputs.forEach((inp, i) => {
                        inp.value = paste[i] || '';
                    });
                    inputs[5].focus();
                }
            });
        });
    
        // Assembler les 6 chiffres dans le champ caché avant soumission
        document.getElementById('otp-form').addEventListener('submit', function (e) {
            const code = Array.from(inputs).map(inp => inp.value).join('');
            if (code.length !== 6) {
                e.preventDefault();
                alert('Veuillez saisir les 6 chiffres du code.');
                return;
            }
            document.getElementById('otp-hidden').value = code;
        });
    
        // Focus automatique sur le premier champ
        inputs[0].focus();
    </script>

</body>

</html>
