
<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Operateurs'])

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

            @include('layouts.content-heading',['head'=>'Utilisateurs','content'=>'<a class="text-decoration-none" href="">Operateurs</a>','localize'=>'dashboard.WELCOME'])

            @if(session()->has('returnMessage'))
                {!! session('returnMessage') !!}
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
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
                        <form method="post" action="#" id="ajoutOperateur">
                            @csrf
                            <div  class="collapse" id="collapseOne" aria-labelledby="headingOne" >
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label">Nom *</label>
                                        <input class="form-control" type="text" id="nom" name="nom" required="">
                                    </div>
                                    <div class="form-group  col-md-6">
                                        <label class="col-form-label">Prénoms *</label>
                                        <input class="form-control" type="text" id="prenoms" name="prenoms" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label">Login *</label>
                                        <input class="form-control" type="text" id="login" name="login" required="">
                                    </div>
                                    <div class="form-group  col-md-6">
                                        <label class="col-form-label">Mot de Passe *</label>
                                        <input class="form-control" type="password" id="password" name="password" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label">Téléphone *</label>
                                        <input class="form-control" type="text" id="telephone" name="telephone" required="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="role" class="form-label mt-2">Roles</label>
                                        <select required id="role_id" name="role"
                                                class="form-select select2-role">
                                            <option value="">Sélectionner un rôle</option>
                                            @if($roles->isNotEmpty())

                                                @foreach($roles as $role)

                                                    <option value="{{$role->id}}">{{$role->name}}</option>

                                                @endforeach

                                            @endif
                                        </select>
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
                                    <th>Ordre</th>
                                    <th>Nom</th>
                                    <th>Prenoms</th>
                                    <th>Login</th>
                                    <th>Telephone</th>
                                    <th>Rôle</th>
                                    <th>Actif</th>
                                    {{--@canany(['Comptes.Modifier_compte', 'Comptes.Supprimer_compte'])--}}
                                        <th>Action</th>
                                    {{--@endcanany--}}
                                </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)

                                    @if($operateurs->isNotEmpty())

                                        @foreach($operateurs as $cle)
                                        <tr data-id="{{ $cle->idOperateur }}" data-role-id="{{ $cle->idRole }}">
                                            <td>{{ $i++ }}</td>
                                            <td class="nom">{{ $cle->nom }}</td>
                                            <td class="prenoms">{{ $cle->prenoms }}</td>
                                            <td class="telephone">{{ $cle->contact }}</td>
                                            <td class="login">{{ $cle->login }}</td>
                                            <td class="libelleRole">{{ $cle->name }}</td>
                                            <td class="actif">

                                                @if($cle->actif == 1)
                                                    <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cliquer pour désactiver le compte" class="btn btn-outline-success btn-sm  btn-comptes-open" data-id="{{ $cle->idOperateur }}" data-name="{{ $cle->nom . ' ' .  $cle->prenoms}}"><i class="fas fa-lock-open"></i></button>
                                                @else
                                                    <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cliquer pour activer le compte" class="btn btn-outline-danger btn-sm  btn-comptes-lock" data-id="{{ $cle->idOperateur }}" data-name="{{ $cle->nom . ' ' .  $cle->prenoms}}"><i class="fas fa-lock"></i></button>
                                                @endif
                                            </td>
                                            {{--@canany(['Comptes.Modifier_compte', 'Comptes.Supprimer_compte'])--}}
                                                <td  class="text-center">
                                                    {{--@can('Comptes.Modifier_compte')--}}
                                                        <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cliquer pour modifier cette ligne" class="btn btn-outline-info btn-sm  btn-edit" data-id="{{ $cle->idOperateur }}"><i class="fas fa-pencil-alt"></i></button>
                                                    {{--@endcan
                                                    @can('Comptes.Supprimer_compte')--}}
                                                        <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Clique pour supprimer cette ligne" class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $cle->idOperateur }}" data-name="{{ $cle->nom . ' ' .  $cle->prenoms}}"><i class="fas fa-trash-alt"></i></button>
                                                    {{--@endcan--}}
                                                </td>
                                            {{--@endcanany--}}

                                        </tr>
                                    @endforeach

                                   @endif
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
@include('layouts.datatablesjs')

<!-- Modal-->
<!-- Modal -->
<div class="modal fade" id="modalEditLigne" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'entrée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="operateur_id" id="operateurId">

                    <div class="m-1">
                        <label for="nom" class="form-label mt-2">Nom</label>
                        <input type="text" class="form-control" id="nomModif" name="nom">
                    </div>
                    <div class="m-1">
                        <label for="prenoms" class="form-label mt-2">Prenoms</label>
                        <input type="text" class="form-control" id="prenomsModif" name="prenoms">
                    </div>
                    <div class="m-1">
                        <label for="login" class="form-label mt-2">Login</label>
                        <input type="text" class="form-control" id="loginModif" name="login">
                    </div>
                    <div class="m-1">
                        <label for="password" class="form-label mt-2">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="m-1">
                        <label for="telephone" class="form-label mt-2">Téléphone</label>
                        <input type="text" class="form-control" id="telephoneModif" name="telephone">
                    </div>

                    <div class="m-1">
                        <label for="role" class="form-label mt-2">Roles</label>
                        <select required id="role" name="role" class="form-select select2-role"></select>
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

<!-- Modal -->
<script type="text/javascript">

    $(document).ready(function() {

        $('.select2-role').select2({
            placeholder: "Sélectionnez un Role",
            allowClear: true
        });

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

        $('#ajoutOperateur').on('submit', function (e) {

            e.preventDefault();

            $(".main-content").block();

            let url = "{{ route('comptes.ajouter') }}";

            let formData = $(this).serialize();

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                success: function (response) {

                    $(".main-content").unblock();

                    if(response.success){
                        Swal.fire({
                            title: "succès",
                            text: response.message,
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#4e7adf",
                            cancelButtonColor: "#38c66c",
                            confirmButtonText: "Rafraichir le tableau",
                            cancelButtonText: 'Continuer les enregistrements'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                window.location.href = "{{--{{route('comptes.index')}}--}}";

                            }
                        });
                    }
                    else{
                        Swal.fire({
                            title: "Echec",
                            text: response.message,
                            icon: "error"
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

        $('.btn-edit').on('click', function () {

            let row = $(this).closest('tr');
            let idOperateur = $(this).data('id');
            let roleId = row.data('role-id');
            let nom = row.find('.nom').text().trim();
            let prenoms = row.find('.prenoms').text().trim();
            let login = row.find('.login').text().trim();
            let telephone = row.find('.telephone').text().trim();


            $('#operateurId').val(idOperateur);
            $('#nomModif').val(nom);
            $('#prenomsModif').val(prenoms);
            $('#loginModif').val(login);
            $('#telephoneModif').val(telephone);

            listeRoles(roleId);

            $('#modalEditLigne').modal('show');
        });


        function listeRoles(idSelectionne = '') {
            $.ajax({
                url: "{{ route('roles.rolestoutrecuperer') }}",
                type: "GET",
                dataType: "json",
                success: function (data) {

                    let select = $('#role');

                    select.empty();
                    select.append('<option value="">Sélectionnez un role</option>');

                    $.each(data, function (key, item) {
                        let selected = (item.id == idSelectionne) ? 'selected' : '';
                        select.append('<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>');
                    });

                    // Recharger Select2 (très important si les options changent dynamiquement)
                    select.select2({
                        dropdownParent: $('#modalEditLigne') // très important !
                    });
                },
                error: function () {
                    console.error("Erreur lors du chargement des communes.");
                }
            });
        }


        // Soumission du formulaire de modification
        $('#editForm').on('submit', function (e) {

            e.preventDefault();

            const formData = $(this).serialize();

            let url = "{{ route('comptes.modifier') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    $('#modalEditLigne').modal('hide');

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
                        url: `/comptes-supprimer/${id}`,
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

        $('.btn-comptes-open').on('click', function () {

            const id = $(this).data('id');

            const name = $(this).data('name');

            console.log(name, id);

            Swal.fire({
                title: `Désactivez le compte de : "${name}" ?`,
                text: "Le compte sera fermé et l'utisateur ne pourra pas y accéder !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, Désactiver !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: `/desactiver-compte/${id}`,
                        type: 'GET',
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

        $('.btn-comptes-lock').on('click', function () {

            const id = $(this).data('id');

            const name = $(this).data('name');

            console.log(name, id);

            Swal.fire({
                title: `Activez le compte de : "${name}" ?`,
                text: "Cette action permettra à l'utilisateur d'accéder au contenu du site !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#38c66c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, Activer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: `/activer-compte/${id}`,
                        type: 'GET',
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
