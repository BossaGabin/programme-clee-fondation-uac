{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="en" class="h-100" id="login-page1">


<!-- Mirrored from ameen.vercel.app/main/template-vertical-nav/page-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 Feb 2026 10:19:38 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

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
    <style>
        .btn {
            background: #006b08;
            border-color: #006b08;
            color: #fff
        }

        .btn:hover {
            background: #baa505 !important;
            border-color: #baa505 !important;
            color: #fff !important;
        }
    </style>
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
                                <div class="logo text-center">
                                    <a href="/">
                                        <img src="{{ asset('assets/images/f-logo.png') }}" alt="">
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
                                                {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                                    {{ __('Forgot your password?') }}
                                                </a> --}}
                                                <a href="{{ route('password.request') }}" style="color: #006b08 !important">Mot de passe
                                                    oublié?</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center m-b-15 m-t-15">
                                        <button type="submit" class="btn">Se connecter</button>
                                    </div>
                                    <div class="create-btn text-center">
                                        <p>
                                            Avez-vous un compte?
                                            <a href="{{{route('register')}}}" style="color: #006b08 !important">
                                                S'incrire
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
