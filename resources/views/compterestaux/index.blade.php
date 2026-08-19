<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Compte du Restaurant'])

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

            @include('layouts.content-heading',['head'=>'Accueil','content'=>'<a class="text-decoration-none" href="">Tableau de bord</a>','localize'=>'dashboard.WELCOME'])
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <!-- START card-->
                    <div class="card ">
                        <div class="card-header">

                        </div>
                        <div class="card-body">

                            <div class="text-center">
                                <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="mb-1 btn-sm btn btn-outline-primary text-center" href=""><i class="fas fa-plus"></i>
                                    Ajouter
                                </a>
                            </div>
                            <div class="collapse show" id="collapseOne" aria-labelledby="headingOne" style="">

                                <form class="needs-validation" id="AjoutCompte" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Matricule *</label>
                                            <input class="form-control" type="text" name="matricule" required="">
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="typefacturation">Type de Facturation*</label>
                                            <select class="t form-control select2-4" name="typefacturation" id="typefacturation" required>
                                                <option value="">Sélectionnez le type de Facturation</option>
                                                @foreach($typesfacturations ?? [] as $value)
                                                    <option value="{{$value->id}}">{{$value->libelle . " - " . $value->modeRechargement}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-auto text-center">
                                        <button class="btn btn-primary mb-2" type="submit">Valider
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div><!-- END card-->
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered w-100" id="datatable2">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Matricule</th>
                                    <th>Nom et Prénoms</th>
                                    <th>Code Pin</th>
                                    <th>Capacité</th>
                                    <th>Solde</th>
                                    <th>Type de Compte</th>
                                    <th>Type de Facturation</th>
                                    {{--@canany(['Voir liste programme', 'Modifier liste programme', 'Supprimer liste programme'])--}}
                                        <th>Action</th>
                                    {{--@endcanany--}}
                                </tr>
                                </thead>
                                <tbody>

                                    @php($i = 1)
                                    @foreach ($comptesrestaux as $key)
                                    <tr>
                                        {{-- Programme --}}
                                        <td>{{ $i ++ ?? '' }}</td>
                                        <td>{{ $key->matricule ?? '' }}</td>
                                        {{-- ECUE et détails --}}
                                        <td>{{ $key->nom . " " . $key->prenoms ?? '' }}</td>
                                        <td>{{ $key->pin ?? '' }}</td>
                                        <td>{{ $key->capacite ?? '' }}</td>
                                        <td>{{ $key->solde ?? '' }}</td>
                                        <td>{{ $key->libelleTypeCompte ?? '' }}</td>
                                        <td>{{ $key->libelleTypeFacturation ?? '' }}</td>
                                        {{--@canany(['Modifier liste programme', 'Supprimer liste programme'])--}}
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-success btn-sm btn-view" data-id="{{ $key->idCompteRestau }}" title="Voir le compte">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm btn-edit" data-id="{{ $key->idCompteRestau }}" title="Modifier le compte">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $key->idCompteRestau }}" data-name="{{ trim(($key->matricule ?? '') . ' - ' . ($key->nom ?? '') . ' ' . ($key->prenoms ?? '')) }}" title="Supprimer le compte">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                {{--@can('Voir liste programme')--}}
                                                    <a href="{{--{{route("programme.afficher",['id'=>$key->idProgramme])}}--}} "
                                                       class="view-programme" data-id="{{--{{ $key->idProgramme }}--}}"  data-toggle="tooltip" data-placement="top"
                                                       title="Voir les éléments du Programme">
                                                        <div class=" badge badge-default">
                                                            <i class="fas fa-eye" style="color: darkgreen"></i>
                                                        </div>
                                                    </a>
                                                {{--@endcan
                                                @can('Modifier liste programme')--}}
                                                    <a href="{{--{{route("nouveauProgramme.editer",['id'=>$key->idProgramme])}}--}} "
                                                       class="view-programme" data-id="{{--{{ $key->idProgramme }}--}}"  data-toggle="tooltip" data-placement="top"
                                                       title="Modifier le Programme">
                                                        <div class=" badge badge-default">
                                                            <i class="fas fa-edit"  style="color: #3bafda"></i>
                                                        </div>
                                                    </a>
                                               {{-- @endcan
                                                @can('Supprimer liste programme')--}}
                                                    <a href="#" data-id="{{--{{$key->idProgramme}}--}}"
                                                       data-name="{{--{{ $key->libelleProgramme }}--}}" class="delete" data-toggle="tooltip" data-placement="top"
                                                       title="Supprimer le Programme">
                                                        <div class=" badge badge-default">
                                                            <i class="fas fa-trash-alt" style="color: crimson"></i>
                                                        </div>
                                                    </a>
                                                {{--@endcan--}}
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
        </div>

    </section>

    <!-- Page footer-->
    @include('layouts.footer')
</div>

<div class="modal fade" id="modalDetailsCompte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Details du compte restaurant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Matricule</dt>
                    <dd class="col-sm-8" id="detailMatricule"></dd>
                    <dt class="col-sm-4">Etudiant</dt>
                    <dd class="col-sm-8" id="detailEtudiant"></dd>
                    <dt class="col-sm-4">Telephone</dt>
                    <dd class="col-sm-8" id="detailTelephone"></dd>
                    <dt class="col-sm-4">PIN</dt>
                    <dd class="col-sm-8" id="detailPin"></dd>
                    <dt class="col-sm-4">Capacite</dt>
                    <dd class="col-sm-8" id="detailCapacite"></dd>
                    <dt class="col-sm-4">Solde</dt>
                    <dd class="col-sm-8" id="detailSolde"></dd>
                    <dt class="col-sm-4">Facturation</dt>
                    <dd class="col-sm-8" id="detailFacturation"></dd>
                    <dt class="col-sm-4">Etat</dt>
                    <dd class="col-sm-8" id="detailEtat"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditCompte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="ModifierCompteRestaurant" class="modal-content">
            @csrf
            <input type="hidden" name="compte_id" id="editCompteId">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le compte restaurant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Etudiant</label>
                    <input type="text" class="form-control" id="editEtudiant" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Type de facturation</label>
                    <select class="form-control" name="typefacturation" id="editTypeFacturation" required>
                        <option value="">Selectionnez le type de facturation</option>
                        @foreach($typesfacturations ?? [] as $value)
                            <option value="{{$value->id}}">{{$value->libelle . " - " . $value->modeRechargement}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Capacite</label>
                        <input type="number" class="form-control" name="capacite" id="editCapacite" min="1" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Solde</label>
                        <input type="number" class="form-control" name="solde" id="editSolde" min="0" step="1" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Actif</label>
                        <select class="form-control" name="actif" id="editActif" required>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Traque</label>
                        <select class="form-control" name="traques" id="editTraques" required>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- =============== assets SCRIPTS ===============-->
@include('layouts.js')
@include('layouts.datatablesjs')


<script>

    $(document).ready(function() {

        $('.select2-mois').select2({
            placeholder: "Sélectionnez un Mois",
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

        $('.view-programme, .delete').hide();

        function urlCompte(id) {
            return "{{ url('/compte-restaurant-recuperer') }}/" + id;
        }

        function messageErreur(xhr) {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                return Object.values(xhr.responseJSON.errors).map(e => e.join("\n")).join("\n");
            }

            return xhr.responseJSON?.message || "Une erreur est survenue.";
        }

        function chargerCompte(id, callback) {
            $(".main-content").block();

            $.ajax({
                url: urlCompte(id),
                type: "GET",
                success: function (compte) {
                    $(".main-content").unblock();
                    callback(compte);
                },
                error: function (xhr) {
                    $(".main-content").unblock();
                    Swal.fire({
                        title: "Erreur",
                        text: messageErreur(xhr),
                        icon: "error"
                    });
                }
            });
        }

        $(document).on('click', '.btn-view', function () {
            chargerCompte($(this).data('id'), function (compte) {
                $('#detailMatricule').text(compte.matricule || '');
                $('#detailEtudiant').text(((compte.nom || '') + ' ' + (compte.prenoms || '')).trim());
                $('#detailTelephone').text(compte.telephone || '');
                $('#detailPin').text(compte.pin || '');
                $('#detailCapacite').text(compte.capacite || 0);
                $('#detailSolde').text((compte.solde || 0) + ' FCFA');
                $('#detailFacturation').text(((compte.libelleTypeFacturation || '') + ' - ' + (compte.modeRechargement || '')).trim());
                $('#detailEtat').text((parseInt(compte.actif) === 1 ? 'Actif' : 'Inactif') + (parseInt(compte.traques) === 1 ? ' / Traque' : ''));
                $('#modalDetailsCompte').modal('show');
            });
        });

        $(document).on('click', '.btn-edit', function () {
            chargerCompte($(this).data('id'), function (compte) {
                $('#editCompteId').val(compte.idCompteRestau);
                $('#editEtudiant').val(((compte.matricule || '') + ' - ' + (compte.nom || '') + ' ' + (compte.prenoms || '')).trim());
                $('#editTypeFacturation').val(compte.idTypeFacturation).trigger('change');
                $('#editCapacite').val(compte.capacite || 1);
                $('#editSolde').val(compte.solde || 0);
                $('#editActif').val(parseInt(compte.actif) === 1 ? '1' : '0');
                $('#editTraques').val(parseInt(compte.traques) === 1 ? '1' : '0');
                $('#modalEditCompte').modal('show');
            });
        });

        $('#ModifierCompteRestaurant').on('submit', function (e) {
            e.preventDefault();

            $(".main-content").block();

            $.ajax({
                url: "{{ route('compterestau.modifier') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    $(".main-content").unblock();
                    $('#modalEditCompte').modal('hide');

                    Toast.fire({
                        title: response.message,
                        icon: "success"
                    });

                    setTimeout(() => location.reload(), 1000);
                },
                error: function (xhr) {
                    $(".main-content").unblock();
                    Swal.fire({
                        title: "Erreur",
                        text: messageErreur(xhr),
                        icon: "error"
                    });
                }
            });
        });

        $(document).on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: `Supprimer "${name}" ?`,
                text: "Le compte restaurant et ses facturations seront desactives.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Oui, supprimer",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $(".main-content").block();

                $.ajax({
                    url: "{{ url('/supprimer-compte-restaurant') }}/" + id,
                    type: "DELETE",
                    success: function (response) {
                        $(".main-content").unblock();

                        Toast.fire({
                            title: response.message,
                            icon: "success"
                        });

                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function (xhr) {
                        $(".main-content").unblock();
                        Swal.fire({
                            title: "Erreur",
                            text: messageErreur(xhr),
                            icon: "error"
                        });
                    }
                });
            });
        });

        $('#AjoutCompte').on('submit', function (e) {

            e.preventDefault();

            $(".main-content").block();

            let url = "{{ route('compterestau.ajouter') }}";

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

                                window.location.href = "{{route('compterestau.index')}}";

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

    });

</script>

</body>
</html>
