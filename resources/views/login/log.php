
<!DOCTYPE html>
<html lang="en" data-topbar-color="brand">
<head>
    <meta charset="utf-8" />
    <title>SGI-CONNEXION</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="le système de gestion intégrée (SGI) de l'Institut National Polytechnique Félix Houphouët-Boigny (INP-HB). Conçu pour répondre aux besoins variés et complexes de notre établissement , le SGI offre une plateforme centralisée et intuitive pour gérer tous les aspects de la vie universitaire." name="description" />
    <meta content="SERVICE DE LA TRANSFORMATION DIGITALE,DE LA DATA ET DU DÉVELOPPEMENT DES OUTILS D'ENSEIGNEMENT NUMÉRIQUE" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/img/icon.ico')}}">

    <!-- App css -->
    <link href="{{asset('assets/login/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/login/login-app.min.css')}}" rel="stylesheet" type="text/css" id="app-stylesheet" />

    <!-- icons -->
    <link href="{{asset('assets/login/login-icons.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{asset('assets/login/config.js')}}"></script>


</head>

<body style="font-family: 'Helvetica'" class="auth-fluid-pages pb-0">
<div style="background-image: url({{ $image->loginImage ?? asset('assets/login/bg-auth.jpg') }}); background-size: cover; background-position: center;">
    <!-- Auth fluid right content --></div>
<div  class="auth-fluid"
      style="background-image: url({{ $image->loginImage ?? asset('assets/login/bg-auth.jpg') }}); background-size: cover; background-position: center;">
    <!-- Auth fluid right content -->

    <div class="auth-fluid-right" >
        <div class="auth-user-testimonial">
            <h3 class="mb-3 text-white">Système de Gestion Intégré (SGI) de l'INP-HB</h3>
            <p class="lead fw-normal"> {{$texte->texte}}
            </p>

        </div> <!-- end auth-user-testimonial-->
    </div>

    <!--Auth fluid left content -->
    <div class="auth-fluid-form-box">
        <div class="align-items-center d-flex h-100">
            <div class="card-body">
                <!-- title-->

                <div class="text-center">
                    <h4 class="mt-0">Se connecter</h4>
                    <img class="text-center" src="assets/login/logo.png" alt="" >
                </div>
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
                @endif
                <!-- form -->


                <form method="POST" action="{{ route('login.loguser') }}">

                    @csrf
                    <div class="mb-2">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input class="form-control" type="email" name="email" id="email" value="{{ isset($_COOKIE["email"]) ? $_COOKIE["email"] : "" }}" required="" placeholder="Veillez entrer votre Email">
                    </div>
                    <div class="mb-2">

                        <label for="password" class="form-label">Mot de Passe</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" name="password" value="{{ isset($_COOKIE["password"]) ? $_COOKIE["password"] : "" }}" class="form-control" placeholder="Veillez entrer votre Mot de Passe">

                        </div>

                        <a href="#" class="text-muted float-end"><small>Mot de passe oublié ?</small></a>

                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" name="remember" {{ isset($_COOKIE["email"]) && isset($_COOKIE["password"]) ? "checked" : "" }} }} type="checkbox" id="checkbox-signin">
                            <label class="form-check-label" for="checkbox-signin">
                                Se souvenir de moi
                            </label>
                        </div>
                    </div>
                    <div class="d-grid text-center">
                        <button class="btn btn-primary" type="submit">Valider </button>
                    </div>

                </form>
                <!-- end form-->

            </div> <!-- end .card-body -->
        </div> <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->
</div>
<!-- end auth-fluid-->

<!-- Vendor js -->
<script src="assets/login/vendor.min.js"></script>

<!-- App js -->
<script src="assets/login/login-app.min.js"></script>

</body>
</html>
