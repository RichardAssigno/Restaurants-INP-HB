
<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Autorisations'])

    @include('layouts.css')
    @include('layouts.datatablescss')

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

            @include('layouts.content-heading',['head'=>'Utilisateurs','content'=>'<a class="text-decoration-none" href="">Autorisations</a>','localize'=>'dashboard.WELCOME'])

            @if(session()->has('returnMessage'))
                {!! session('returnMessage') !!}
            @endif
            {{--@can('Ajouter role')--}}
            <div class="row">
                <div class="col-lg-12">
                    <!-- START card-->
                    <div class="card ">
                        <div class="card-header">
                        </div>
                        <div class="card-body">

                            <div class="text-center">
                                <a
                                    data-toggle="collapse"
                                    data-target="#collapseOne"
                                    aria-expanded="true"
                                    aria-controls="collapseOne"
                                    class="mb-1 btn-sm btn btn-outline-primary text-center"
                                    href=""><i class="fas fa-plus"></i>
                                    Ajouter</a>


                            </div>
                            <!-- Debut ajout id="AjoutEcole" -->
                            <form method="post" action="#" id="ajoutAutorisation">
                                @csrf
                                <div  class="collapse" id="collapseOne" aria-labelledby="headingOne" >
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label class="col-form-label">Autorisation *</label>
                                            <input class="form-control" type="text" id="libelle" name="libelle" required="">
                                        </div>
                                    </div>
                                    <div class="col-auto text-center">
                                        <button class="btn btn-primary mb-2" type="submit">
                                            Valider
                                        </button>
                                    </div>

                                </div>
                            </form><!-- Fin ajout  -->

                        </div>
                    </div><!-- END card-->
                </div>

            </div>
            {{-- @endcan--}}
            @if($permissions->isNotEmpty())
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
                                    <th>N°</th>
                                    <th>Libellé Autorisation</th>
                                    {{--@canany(['Modifier role', 'Supprimer role'])--}}
                                    <th class="sort-alpha" data-priority="2">Actions</th>
                                    {{--@endcanany--}}
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 1)
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $i++  }}</td>
                                        <td>{{ $permission->name  }}</td>

                                        {{--@canany(['Modifier liste programme', 'Supprimer liste programme'])--}}
                                        <td>
                                            <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cliquer pour modifier cette ligne" class="btn btn-outline-info btn-sm btn-edit" data-id="{{ $permission->id }}"><i class="fas fa-pencil-alt"></i></button>
                                            <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Clique pour supprimer cette ligne" class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $permission->id }}" data-name="{{ $permission->name }}"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                        {{--@endcanany--}}
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

                </div>
            @endif

        </div>
    </section>

    <!-- Page footer-->
    @include('layouts.footer')
</div>

<!-- =============== assets SCRIPTS ===============-->

@include('layouts.js')
@include('layouts.datatablesjs')

<!-- Modal-->
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'entrée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="m-1">
                        <label for="libelle" class="form-label mt-2">Libelle de l'Autorisation</label>
                        <input type="text" id="libelle" class="form-control" name="libelle">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{--<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Confirmation de suppression</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="color: indianred;">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="dissmisDelete" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>--}}
<!-- Modal -->
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

        $('#ajoutAutorisation').on('submit', function (e) {

            e.preventDefault();

            $(".main-content").block();

            let url = "{{ route('permissions.ajouter') }}";

            let formData = $(this).serialize();

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Succès",
                            text: response.success, // <-- ton message s'affichera ici
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#4e7adf",
                            cancelButtonColor: "#38c66c",
                            confirmButtonText: "Rafraîchir le tableau",
                            cancelButtonText: 'Continuer les enregistrements'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('permissions.index') }}";
                            }
                        });
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errorHtml = "Une erreur est survenue.";

                        $(".main-content").unblock();

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorHtml = Object.values(xhr.responseJSON.errors).map(e => e.join("\n")).join("\n");
                        }

                        Swal.fire({
                            title: "Erreur!",
                            text: errorHtml,
                            icon: "error"
                        });

                    } else {
                        $(".main-content").unblock();
                        Swal.fire({
                            title: "Erreur!",
                            text: xhr.responseJSON.message,
                            icon: "error"
                        });

                    }
                }

            });
        });

        // Quand on clique sur le bouton modifier
        $('.btn-edit').on('click', function () {
            const id = $(this).data('id');

            // Récupération des données via AJAX
            $.ajax({
                url: '/Permissions-Recuperer/' + id, // adapte cette URL
                type: 'GET',
                success: function (data) {
                    // Remplir les champs du formulaire
                    console.log($('#libelle').val(data.name));
                    $('#edit-id').val(data.id);
                    $('#libelle').val(data.name);

                    // Afficher le modal
                    $('#editModal').modal('show');
                },
                error: function () {
                    Swal.fire({
                        title: "Echec",
                        text: response.message,
                        icon: "error"
                    });
                }
            });
        });

        // Soumission du formulaire de modification
        $('#editForm').on('submit', function (e) {

            e.preventDefault();

            const formData = $(this).serialize();

            let url = "{{ route('permissions.modifier') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    $('#editModal').modal('hide');

                    Toast.fire({
                        title: response.message,
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Recharge toute la page après un petit délai (le temps de voir le toast)
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';

                        $.each(errors, function (key, value) {
                            messages += `• ${value[0]}\n`;
                        });

                        Swal.fire({
                            title: "Erreur de validation",
                            text: messages,
                            icon: "warning"
                        });
                    } else {
                        Swal.fire({
                            title: "Échec",
                            text: xhr.responseJSON?.message || "Une erreur est survenue.",
                            icon: "error"
                        });
                    }
                }

            });

        });
        $('.btn-delete').on('click', function () {

            const id = $(this).data('id');

            const name = $(this).data('name');

            console.log(name);

            Swal.fire({
                title: `Supprimer "${name}" ?`,
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: `/Supprimer-Permission/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {

                            Toast.fire({
                                title: response.message,
                                position: "top-end",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Recharger après suppression
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Erreur',
                                text: xhr.responseJSON?.message || "Une erreur est survenue lors de la suppression.",
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

    });
</script>

</body>
</html>
