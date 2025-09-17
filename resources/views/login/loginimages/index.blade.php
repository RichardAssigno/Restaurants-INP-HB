<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Ajout école'])

    @include('layouts.css')
    @include('layouts.css-summernote')

    <!-- CSS de Summernote -->
    <!-- add summernote -->


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

    </style>

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

            @include('layouts.content-heading',['head'=>'Ressources','content'=>'<a class="text-decoration-none" href="">Image de la page Login</a>','localize'=>'dashboard.WELCOME'])

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

                        <form  enctype="multipart/form-data" id="AjoutLoginimage">
                            @csrf

                            <div class="row m-2">
                                <div class="col-12">
                                    <label for="libelle">Image de la page</label>
                                    <input id="image-input" placeholder="Saisir un libelle" class="form-control"
                                           name="image" type="file" accept="image/*">
                                </div>
                            </div>
                            <div class="row m-2" id="preview-container" style="display: none;">
                                <div class="col-12">
                                    <label>Prévisualisation de l'image</label>
                                    <div style="border: 1px solid #ddd; padding: 10px; margin-top: 10px;">
                                        <img id="image-preview" src="" alt="Prévisualisation"
                                             style="max-width: 100%; height: auto;">
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
                                    <th>image</th>
                                    @canany(['Modifier Loginimage', 'Action Loginimage'])
                                        <th class="sort-alpha" data-priority="2">Actions</th>
                                    @endcanany
                                </tr>
                                </thead>
                                <tbody>
                                @php $i = 1 @endphp

                                @foreach($listes as $liste)
                                    <tr class="gradeA">
                                        <td>{{ $i++  }}</td>

                                        <td>
                                            <img src="{{ $liste->loginImage }}" style="width:80px;height: 80px;" class="rounded img-thumbnail clickable" alt="Image">
                                        </td>
                                        @canany(['Modifier Loginimage', 'Action Loginimage'])
                                            <td class="text-center">
                                                @can('Modifier Loginimage')
                                                    <a href="{{route('loginimage.editer',['id'=>$liste->id])}}">
                                                        <div class=" badge badge-default">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </div>
                                                    </a>
                                                @endcan
                                                @can('Action Loginomage')
                                                    <a href="#" data-id="{{$liste->id}}" data-name="{{ $liste->id }}"
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
                            <div id="imageModal" class="modal" style="display: none;">
                                <span class="close">&times;</span>
                                <img class="modal-content" id="modalImage">
                            </div>
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
@include('layouts.js-summernote')

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

        $('#image-input').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-preview').attr('src', e.target.result);
                    $('#preview-container').show();
                }
                reader.readAsDataURL(file);
            }
        });


        $('#AjoutLoginimage').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var url = "{{ route('loginimage.ajouterimage') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $("#AjoutLoginimage").trigger('reset');
                    $("#preview-container").hide();

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
                        if (result.isConfirmed) {
                            window.location = "{{route('loginimage.index')}}";
                        }
                    });
                },
                error: function (response) {
                    var errors = response.responseJSON.errors;
                    var errorHtml = '';
                    $.each(errors, function (key, value) {
                        errorHtml += '' + value + '';
                    });
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
                text: `Voulez-vous changer le statut de cette image ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Oui, je veux changer!',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('loginimage.changerstatutimage') }}",
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("modalImage");
        var closeBtn = document.querySelector(".close");

        document.querySelectorAll('.clickable').forEach(function (img) {
            img.addEventListener('click', function () {
                modal.style.display = "block";
                modalImg.src = this.src;
            });
        });

        closeBtn.onclick = function () {
            modal.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    });

</script>
</body>
</html>
