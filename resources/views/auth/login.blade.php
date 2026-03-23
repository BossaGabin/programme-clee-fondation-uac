<!DOCTYPE html>
<html lang="en" class="h-100" id="login-page1">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CLEE - Se connecter</title>
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
                <div class="col-xl-6">
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
                                @if ($errors->any())
                                    <h4 class="text-center m-t-15">Connexion</h4>
                                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li class="text-danger">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="m-t-30 m-b-30" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Email">
                                    </div>


                                    <div class="form-group">
                                        <label for="password">Mot de passe <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" id="password" name="password"
                                                class="form-control" placeholder="Mot de passe">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="toggle-password"
                                                    style="cursor:pointer; user-select:none;">
                                                    🔓
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <div class="form-check p-l-0">
                                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                                <label class="form-check-label" for="remember_me">Se Souvenir de
                                                    moi</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 text-right" >
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" style="color: #006b08 !important">Mot de passe
                                                    oublié?</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center m-b-15 m-t-15">
                                        <button type="submit" class="btn btn-primary">Se connecter</button>
                                    </div>
                                    <div class="create-btn text-center">
                                        <p>
                                            Avez-vous un compte?
                                            <a href="{{{route('register')}}}" style="color: #006b08 !important">
                                                S'inscrire
                                                <i class='bx bx-chevrons-right bx-fade-right'></i>
                                            </a>
                                        </p>
                                    </div>
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
    document.getElementById('toggle-password').addEventListener('click', function () {
        const input    = document.getElementById('password');
        const isHidden = input.type === 'password';
        input.type     = isHidden ? 'text' : 'password';
        this.textContent = isHidden ? '🔒' : '🔓';
    });
</script>

</body>

</html>
