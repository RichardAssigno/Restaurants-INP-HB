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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                        {{--@canany(['Modifier liste programme', 'Supprimer liste programme'])--}}
                                            <td class="text-center">
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
<div class="modal fade" id="modalScan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scanner le QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center">
                <div class="card border-0">
                    <video id="qr-video" width="100%" height="300" autoplay></video>
                    <canvas id="qr-canvas" style="display:none;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =============== assets SCRIPTS ===============-->
@include('layouts.js')
@include('layouts.datatablesjs')
<script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>


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

                                window.location.href = "{{route('compterestau')}}";

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
