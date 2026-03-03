{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
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
    <title>CLEE - Mot de passe oublié</title>
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
                                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li class="text-danger">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <h4 class="text-center m-t-15">Mot de passe oublié</h4>
                                <p class="text-muted">
                                    Mot de passe oublié ? Pas de souci. Indiquez-nous simplement votre adresse e-mail et
                                    nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra
                                    d’en choisir un nouveau.
                                </p>
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button> <strong>Bravo!</strong> {{ session('status') }}</div>
                                @endif
                                <form class="m-t-30 m-b-30" method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Email">
                                    </div>

                                    <div class="text-center m-b-15 m-t-15">
                                        <button type="submit" class="btn">
                                            Lien de réinitialisation</button>
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

</body>

</html>
