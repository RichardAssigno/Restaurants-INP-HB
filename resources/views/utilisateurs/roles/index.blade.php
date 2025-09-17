
<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Roles'])

    @include('layouts.css')
    @include('layouts.datatablescss')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Grille principale pour les modules de permission */
        .permission-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px; /* Espace entre les blocs */
        }

        /* Style de chaque bloc de permission ("rubrique") */
        .rubrique {
            flex: 1 1 calc(25% - 15px); /* ← 4 colonnes */
            box-sizing: border-box;
            border: 1px solid #dee2e6;
            border-radius: 0;
            display: flex;
            flex-direction: column;
        }

        /* En-tête de la rubrique (ex: "Administrateur") */
        .rubrique-header {
            font-weight: bold;
            font-size: 1rem;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .rubrique-header label {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            cursor: pointer;
        }

        /* Liste des permissions sous le titre */
        .permissions-list {
            padding: 10px 15px;
            display: flex;
            flex-direction: column;
            gap: 8px; /* Espace vertical entre chaque permission */
        }

        .permission-item label {
            display: flex;
            align-items: center;
            font-weight: normal;
            font-size: 0.95rem;
            cursor: pointer;
            margin-bottom: 0;
        }

        /* Style global pour les cases à cocher */
        input[type="checkbox"] {
            transform: scale(1.1);
            margin-right: 10px;
        }

        /* -- RESPONSIVITÉ -- */
        /* Pour les écrans de type tablette (3 colonnes) */
        @media (max-width: 1200px) {
            .rubrique {
                flex-basis: calc(33.33% - 15px);
            }
        }

        /* Pour les petites tablettes (2 colonnes) */
        @media (max-width: 768px) {
            .rubrique {
                flex-basis: calc(50% - 15px);
            }
        }

        /* Pour les mobiles (1 colonne) */
        @media (max-width: 576px) {
            .rubrique {
                flex-basis: 100%;
            }
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

            @include('layouts.content-heading',['head'=>'Utilisateurs','content'=>'<a class="text-decoration-none" href="">Roles</a>','localize'=>'dashboard.WELCOME'])

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
                        <form method="post" action="#" id="AjoutRole">
                            @csrf
                            <div  class="collapse" id="collapseOne" aria-labelledby="headingOne" >
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label class="col-form-label">Role *</label>
                                        <input class="form-control" type="text" id="name" name="name" required="">
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
                                    <th>Libellé Role</th>
                                    {{--@canany(['Modifier role', 'Supprimer role'])--}}
                                    <th class="sort-alpha" data-priority="2">Actions</th>
                                    {{--@endcanany--}}
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 1)
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $i++  }}</td>
                                        <td>{{ $role->name  }}</td>

                                        {{--@canany(['Modifier liste programme', 'Supprimer liste programme'])--}}
                                            <td>
                                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cliquer pour modifier cette ligne" class="btn btn-outline-info btn-sm btn-edit" data-id="{{ $role->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Clique pour ajouter des autorisations" class="btn btn-sm btn-outline-success btn-add" data-id="{{ $role->id }}" data-name="{{ $role->name }}"><i class="fas fa-plus-circle"></i></button>
                                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Clique pour supprimer cette ligne" class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $role->id }}" data-name="{{ $role->name }}"><i class="fas fa-trash-alt"></i></button>
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
            <div class="row row-permission" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6"><h4 class="card-title">Liste des permissions</h4></div>
                                <div class="col-md-6"><input type="text" class="form-control" id="recherche" name="recherche" placeholder="Rechercher une autorisation"></div>

                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="ajoutPermissionRole">
                                @csrf
                                <input type="hidden" id="role_id" name="role_id">
                                <div class="permission-grid"></div>
                                <div class="mt-2 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary me-1">Valider</button>
                                    <button type="reset" class="btn btn-secondary">Vider les champs</button>
                                </div>
                            </form>
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
                        <label for="libelle" class="form-label mt-2">Libelle du Rôle</label>
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

        //Debut module ajout

        $('#AjoutRole').on('submit', function (e) {

            e.preventDefault();

            let url = "{{ route('roles.ajouter') }}";

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
                                window.location.href = "{{ route('roles.index') }}";
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
                url: '/Roles-Recuperer/' + id, // adapte cette URL
                type: 'GET',
                success: function (data) {
                    // Remplir les champs du formulaire
                    $('#edit-id').val(data.id);
                    $('#libelle').val(data.name);

                    // Afficher le modal
                    $('#editModal').modal('show');
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

        // Soumission du formulaire de modification
        $('#editForm').on('submit', function (e) {

            e.preventDefault();

            const formData = $(this).serialize();

            let url = "{{ route('roles.modifier') }}";

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
                        url: `/Supprimer-Roles/${id}`,
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

        // Gestion des checkbox rubrique → coche/décoche toutes les permissions
        $(document).on('change', '.rubrique-checkbox', function () {
            const $rubrique = $(this).closest('.rubrique');
            const isChecked = $(this).is(':checked');
            $rubrique.find('.permission-checkbox').prop('checked', isChecked);
        });

        // Gestion des checkbox permission → met à jour l'état de la rubrique
        $(document).on('change', '.permission-checkbox', function () {
            const $rubrique = $(this).closest('.rubrique');
            const $all = $rubrique.find('.permission-checkbox');
            const $checked = $rubrique.find('.permission-checkbox:checked');
            const $rubriqueCheckbox = $rubrique.find('.rubrique-checkbox');

            $rubriqueCheckbox.prop('checked', $all.length === $checked.length);
        });

        $('.btn-add').on('click', function () {

            const roleId = $(this).data('id');

            $('#role_id').val(roleId);

            $.ajax({
                url: `/Roles-Charger-Permissions/${roleId}`,
                type: 'GET',
                success: function (response) {
                    const grouped = response.grouped;
                    const assigned = response.assigned;

                    let html = '';

                    for (const rubrique in grouped) {
                        html += `
                    <div class="rubrique">
                        <div class="rubrique-header">
                            <label>
                                <input type="checkbox" class="rubrique-checkbox">
                                ${rubrique.replace('_', ' ').toUpperCase()}
                            </label>
                        </div>
                        <div class="permissions-list">
                `;

                        grouped[rubrique].forEach(permission => {
                            const name = permission.name;
                            const libelle = name.split('.')[1].replace('_', ' ');
                            const checked = assigned.includes(name) ? 'checked' : '';

                            html += `
                        <div class="permission-item">
                            <label>
                                <input type="checkbox" class="permission-checkbox" name="permissions[]" value="${name}" ${checked}>
                                ${libelle.charAt(0).toUpperCase() + libelle.slice(1)}
                            </label>
                        </div>`;
                        });

                        html += `</div></div>`;
                    }

                    $('.permission-grid').html(html);

                    // Mettre à jour l'état des rubriques selon les permissions déjà cochées
                    $('.rubrique').each(function () {
                        const $rubrique = $(this);
                        const $all = $rubrique.find('.permission-checkbox');
                        const $checked = $rubrique.find('.permission-checkbox:checked');

                        if ($all.length && $all.length === $checked.length) {
                            $rubrique.find('.rubrique-checkbox').prop('checked', true);
                        } else {
                            $rubrique.find('.rubrique-checkbox').prop('checked', false);
                        }
                    });

                    $('.row-permission').hide().fadeIn(); // Ou .slideDown()
                },
                error: function () {
                    Swal.fire({
                        title: "Erreur",
                        text: "Impossible de charger les permissions",
                        icon: "error"
                    });
                }
            });
        });


        $('#ajoutPermissionRole').on('submit', function (e) {

            e.preventDefault();

            const formData = $(this).serialize();

            let url = "{{ route('roles.ajouterpermissions') }}";

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

                        // S'adapte à n'importe quelle structure d'erreurs
                        $.each(errors, function (key, value) {
                            if (Array.isArray(value)) {
                                messages += `• ${value[0]}\n`;
                            } else {
                                messages += `• ${value}\n`;
                            }
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

        $('#recherche').on('keyup', function () {
            let recherche = $(this).val();

            if (recherche.length >= 2) {
                $(".main-content").block();

                $.ajax({
                    url: "{{ route('roles.recherche') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        recherche: recherche
                    },
                    success: function (response) {

                        $(".main-content").unblock();


                    },
                    error: function () {
                        $(".main-content").unblock();
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur!',
                            text: 'Impossible d’effectuer la recherche.'
                        });
                    }
                });
            }
        });




    });
</script>

</body>
</html>
