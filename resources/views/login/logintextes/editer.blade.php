<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Ajout école'])

    @include('layouts.css')
    @include('layouts.css-summernote')

    <!-- CSS de Summernote -->
    <!-- add summernote -->


    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
<div class="wrapper">
    <!-- top navbar-->
    @include('layouts.topnavbar')

    <!-- sidebar-->
    @include('layouts.menu')

    @include('layouts.setting')

    <section class="section-container">

        <!-- Page content-->
        <div class="content-wrapper">
            @include('layouts.content-heading', [
               'head' => 'Ressources',
               'content' => '<a class="text-decoration-none" href="' . route('logintexte.index') . '">Texte de la page Login</a> / Editer le texte',
               'localize' => 'dashboard.WELCOME'
           ])
            @if(session()->has('returnMessage'))

                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    {!!  session('returnMessage') !!}
                </div>

            @endif
            @if(session()->has('errorMessage'))

                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    {!!  session('errorMessage') !!}
                </div>

            @endif

            <div class="row">

                <div class="col-md-12">
                    <!-- DATATABLE DEMO 2-->
                    <div class="content-heading d-flex flex-column flex-md-row align-items-center">
                        <!-- Partie visible uniquement sur les grands écrans -->
                        <div class="d-none d-md-block">
                            Textes sur la page de connexion
                            <small>Les textes que nous voulons afficher à la page de connexion</small>
                        </div>

                    </div>

                    <div class="portlets-wrapper">
                        <!-- START row-->
                        <div class="row">

                            <div class="col-12">

                                <form id="ModificationLoginTexte">
                                    @csrf
                                    @method('put')

                                    <div class="card card-default mb-3">
                                        <h1 class="card-header">Modifier un texte de la page login</h1>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <textarea name="texte" name="content" id="contents"
                                                          class="form-control">{!! $logintexte->texte !!} </textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-center">
                                        <button type="submit" class="btn btn-outline-info btn-sm m-2">Enregistrer
                                        </button>
                                        <a href="{{ route('logintexte.index') }}"
                                           class="btn btn-outline-secondary btn-sm m-2">Retour à la liste</a>
                                    </div>


                                </form>

                            </div>
                        </div><!-- END row-->
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Page footer-->
    @include('layouts.footer')
</div>

<!-- =============== assets SCRIPTS ===============-->

@include('layouts.js')


<script type="text/javascript">

    $(document).ready(function() {

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

        $('#ModificationLoginTexte').on('submit', function(e) {
            e.preventDefault();

            var url = "{{route('logintexte.update',['id'=>$logintexte->id])}}";
            $.ajax({
                url: url,
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Toast.fire({
                        title: response.success,
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    })
                        .then((value) => {
                            window.location= "{{route('logintexte.index')}}";
                        });
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    var errorHtml = '';
                    $.each(errors, function(key, value) {
                        errorHtml += '' + value + '';
                    });
                    errorHtml += '';

                    Swal.fire({
                        icon: "error",
                        title: "Erreur!",
                        text: errorHtml

                    });
                }
            });
        });
    });
</script>
</body>
</html>
