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
               <div class="col-xl-10">
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
                           <h4 class="text-center m-t-15">Création de compte</h4>
                           <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                              <ul class="list-disc list-inside text-danger">
                                 @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                                 @endforeach
                              </ul>
                           </div>
                           @endif
                           <form class="m-t-30 m-b-30" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                              @csrf
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="name">Nom & Prénom(s) <span
                                          class="text-danger">*</span></label>
                                       <input type="text" id="name" name="name" class="form-control"
                                          placeholder="Nom & Prénom(s)">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="email">Email <span class="text-danger">*</span></label>
                                       <input type="email" id="email" name="email" class="form-control"
                                          placeholder="Email">
                                    </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="phone">Téléphone <span
                                          class="text-danger">*</span></label>
                                       <input type="text" id="phone" name="phone" class="form-control"
                                          placeholder="Télephone (0197000000)">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="avatar">Photo de profil 
                                       {{-- <span class="text-danger">*</span></label> --}}
                                       <span class="text-muted">(Optionnel)</span></label>
                                       <input type="file" id="avatar" name="avatar" class="form-control"
                                          placeholder="Photo de profil">
                                    </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group mb-3">
                                          <label for="date_of_birth">Date de naissance <span class="text-danger">*</span></label>
                                          <input type="date" id="date_of_birth" name="date_of_birth"
                                             class="form-control @error('date_of_birth') is-invalid @enderror"
                                             value="{{ old('date_of_birth') }}"
                                             max="{{ now()->subYears(18)->format('Y-m-d') }}">
                                          @error('date_of_birth')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                    </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="password">Mot de passe <span class="text-danger">*</span></label>
                                       <div class="input-group">
                                          <input type="password" id="password" name="password"
                                             class="form-control" placeholder="Mot de passe">
                                          <div class="input-group-append">
                                             <span class="input-group-text" id="toggle-password"
                                                style="cursor:pointer; user-select:none;">🔓</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="password_confirmation">
                                       Confirmer le mot de passe <span class="text-danger">*</span>
                                       </label>
                                       <div class="input-group">
                                          <input type="password" id="password_confirmation"
                                             name="password_confirmation"
                                             class="form-control" placeholder="Confirmer le mot de passe">
                                          <div class="input-group-append">
                                             <span class="input-group-text" id="toggle-password-confirm"
                                                style="cursor:pointer; user-select:none;">🔓</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="text-center m-b-15 m-t-15">
                                 <button type="submit" class="btn btn-primary">S'incrire</button>
                              </div>
                              <div class="create-btn text-center" style="margin-bottom:-50px">
                                 <p>
                                    Avez-vous un compte?
                                    <a href="{{{route('login')}}}" style="color: #006b08 !important">
                                    Se connecter
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
             input.type       = isHidden ? 'text' : 'password';
             this.textContent = isHidden ? '🔒' : '🔓';
         });
         
         document.getElementById('toggle-password-confirm').addEventListener('click', function () {
             const input    = document.getElementById('password_confirmation');
             const isHidden = input.type === 'password';
             input.type       = isHidden ? 'text' : 'password';
             this.textContent = isHidden ? '🔒' : '🔓';
         });
      </script>
   </body>
</html>