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
            @include('layouts.content-heading',['head'=>'Ressources','content'=>'<a class="text-decoration-none" href="">Texte de la page Login</a>','localize'=>'dashboard.WELCOME'])

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

                </div>
            </div>
            <div class="portlets-wrapper">
                <!-- START row-->
                <div class="row">

                    <div class="col-12">

                        <form action="" method="POST" enctype="multipart/form-data" id="AjoutLoginTexte">
                            @csrf

                            <div class="card card-default mb-3">
                                <h1 class="card-header">Le texte de la page de connexion</h1>
                                <div class="card-body">
                                    <div class="form-group">
                                        <textarea name="texte" id="content" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto text-center">
                                <button class="btn btn-primary mb-2" type="submit">
                                    Valider
                                </button>
                            </div>
                        </form>

                    </div>
                </div><!-- END row-->
            </div>
            <div class="row">

                <div class="col-md-12">
                    <!-- DATATABLE DEMO 2-->

                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">

                            <table class="table table-striped table-bordered w-100" id="datatable2">
                                <thead>
                                <tr>
                                    <th data-priority="1">N°</th>
                                    <th>Texte</th>
                                    @canany(['Modifier ', 'Action'])
                                        <th class="sort-alpha" data-priority="2">Actions</th>
                                    @endcanany
                                </tr>
                                </thead>
                                <tbody>
                                @php $i = 1 @endphp

                                @foreach($listes as $liste)
                                    <tr class="gradeA">
                                        <td>{{ $i++  }}</td>

                                        <td data-libelle="{{ $liste->texte }}">{!! $liste->texte !!}</td>

                                        @canany(['Modifier Logintexte', 'Action Logintexte'])
                                            <td class="text-center">
                                                @can('Modifier Logintexte')
                                                    <a href="{{route('logintexte.editer',['id'=>$liste->id])}}">
                                                        <div class=" badge badge-default">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </div>
                                                    </a>
                                                @endcan
                                                @can('Action Logintexte')
                                                    <a href="#" data-id="{{$liste->id}}" data-name="{{ $liste->texte }}"
                                                       class="activer-desactiver">
                                                        @if($liste->statut == 1)

                                                            <div class=" badge badge-default">
                                                                <i class="fas fa-lock-open"
                                                                   style="color: limegreen"></i>
                                                            </div>

                                                        @elseif($liste->statut == 0)

                                                            <div class=" badge badge-default">
                                                                <i class="fas fa-lock"></i>
                                                            </div>
                                                        @endif
                                                    </a>

                                                @endcan

                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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

    $(document).ready(function () {

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

        //Debut module ajout

        $('#AjoutLoginTexte').on('submit', function (e) {
            e.preventDefault();


            //var post = $(this).serialize();
            var url = "{{ route('logintexte.ajoutertexte') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {


                    $("#AjoutLoginTexte").trigger('reset');

                    Swal.fire({
                        title: response.success,
                        icon: "success",
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonColor: "#0b946f",
                        denyButtonColor: "#0593b2",
                        confirmButtonText: "Recharger la page",
                        denyButtonText: `Nouvel Enregistrement`
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            window.location = "{{route('logintexte.ajoutertexte')}}";
                        }
                    });
                },
                error: function (response) {
                    var errors = response.responseJSON.errors;
                    var errorHtml = '';
                    $.each(errors, function (key, value) {
                        errorHtml += '' + value + '';
                    });
                    errorHtml += '';
                    //$('#response').html(errorHtml);
                    Swal.fire({
                        icon: "error",
                        title: "Erreur!",
                        text: errorHtml

                    });
                }
            });
        });
        //Fin module ajout

        $('.activer-desactiver').on('click', function (e) {
            e.preventDefault();

            const id = $(this).data('id');



            Swal.fire({
                title: 'Confirmation',
                text: `Voulez-vous changer le statut de ce texte ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Oui, je veux changer!',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('logintexte.changerstatuttexte') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire(
                                    'Mis à jour!',
                                    'Le statut a été mis à jour avec succès.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Erreur!',
                                    'Une erreur est survenue lors de la mise à jour.',
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Erreur!',
                                'Erreur lors de la requête.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

    });
</script>

</body>
</html>
