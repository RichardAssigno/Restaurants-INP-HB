
<!DOCTYPE html>
<html lang="en" data-topbar-color="brand">
<head>
    @include("layouts.metas")

    @include("layouts.css")

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body style="font-family: 'Helvetica'" class="auth-fluid-pages pb-0">

<div  class="auth-fluid"
 style="background-image: url({{ isset($image->urlImage) ? Storage::url($image->urlImage) : asset('assets/login/bg-auth.jpg') }}); background-size: cover; background-position: center;">

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

                <form method="POST" id="connexion">

                    @csrf
                    <div class="mb-2">
                        <label for="email" class="form-label">Login</label>
                        <input class="form-control" type="text" name="login" id="login" value="{{ $_COOKIE["login"] ?? "" }}" required="" placeholder="Veuillez entrer votre login">
                    </div>
                    <div class="mb-2">

                        <label for="password" class="form-label">Mot de Passe</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" name="password" value="{{ $_COOKIE["password"] ?? "" }}" class="form-control" placeholder="Veuillez entrer votre mot de passe">

                        </div>

                        <a href="#" class="text-muted float-end"><small>Mot de passe oublié ?</small></a>

                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" name="remember" {{ isset($_COOKIE["login"]) && isset($_COOKIE["password"]) ? "checked" : "" }} type="checkbox" id="checkbox-signin">
                            <label class="form-check-label" for="checkbox-signin">
                                Se souvenir de moi
                            </label>
                        </div>
                    </div>
                    <div class="d-grid text-center">
                        <button class="btn btn-primary" id="login-submit" type="submit">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="login-button-loader" role="status" aria-hidden="true"></span>
                            <em class="fa fa-sign-in-alt me-1" id="login-button-icon"></em>
                            <span id="login-button-text">Valider</span>
                        </button>
                    </div>

                </form>
                <!-- end form-->

            </div> <!-- end .card-body -->
        </div> <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->
</div>
<!-- end auth-fluid-->

@include("layouts.js")

<script>

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function afficherLoaderConnexion() {
        $('#login-submit').prop('disabled', true);
        $('#login-button-loader').removeClass('d-none');
        $('#login-button-icon').addClass('d-none');
        $('#login-button-text').text('Connexion en cours…');
    }

    function masquerLoaderConnexion() {
        $('#login-submit').prop('disabled', false);
        $('#login-button-loader').addClass('d-none');
        $('#login-button-icon').removeClass('d-none');
        $('#login-button-text').text('Valider');
    }

    // Étape 1 : interception du premier formulaire
    $('#connexion').on('submit', function(e) {

        e.preventDefault();

        let login = $('#login').val();
        let password = $('#password').val();

        if (!login || !password) {
            Swal.fire('Champs requis', 'Veuillez remplir l’email et le mot de passe', 'warning');
            return;
        }

        afficherLoaderConnexion();

        $.ajax({
            url: "{{ route('connexion') }}",
            method: "POST",
            data: {
                login: login,
                password: password,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {

                Toast.fire({
                    title: response.success || 'Connexion réussie',
                    position: "top-end",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                });

                setTimeout(function () {
                    window.location.href = response.redirect;
                }, 1500);

            },
            error: function(xhr) {
                masquerLoaderConnexion();
                // Validation failed
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '';

                    if(errors){
                        // Concatène tous les messages d'erreur
                        errorHtml = Object.values(errors).map(arr => arr.join("\n")).join("\n");
                    } else if(xhr.responseJSON.message){
                        errorHtml = xhr.responseJSON.message;
                    } else {
                        errorHtml = "Une erreur inconnue est survenue.";
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: errorHtml
                    });
                } else {
                    // Autres erreurs
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: xhr.responseJSON?.message || 'Une erreur est survenue'
                    });
                }
            }
        });
    });

</script>

</body>
</html>
