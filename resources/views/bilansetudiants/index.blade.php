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

                <div class="col-md-6">
                    <!-- START card-->
                    <div class="card">
                        <div class="d-flex">
                            <div class="w-50 bb br px-3">
                                <div class="d-flex align-items-center"><em class="fa fa-users fa-2x text-info"></em>
                                    <div class="ml-auto">
                                        <div class="card-body text-right">
                                            <h4 class="mt-0">{{$etudiant->libelleTypeCompte ?? ''}}</h4>
                                            <p class="mb-0 text-muted">TYPE COMPTE</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 bb px-3">
                                <div class="d-flex align-items-center"><em class="fa fa-money-bill fa-2x text-danger"></em>
                                    <div class="ml-auto">
                                        <div class="card-body text-right">
                                            <h4 class="mt-0">{{$etudiant->libelleTypeFacturation ?? ''}}</h4>
                                            <p class="mb-0 text-muted">TYPE DE FACTURATION</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="w-50 br px-3">
                                <div class="d-flex align-items-center"><em class="fas fa-money-bill-wave fa-2x text-inverse"></em>
                                    <div class="ml-auto">
                                        <div class="card-body text-right">
                                            <h4 class="mt-0">{{$etudiant->solde . " FCFA " ?? ''}}</h4>
                                            <p class="mb-0 text-muted">SOLDE</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 px-3">
                                <div class="d-flex align-items-center"><em class="fa fa-lock-open fa-2x text-success"></em>
                                    <div class="ml-auto">
                                        <div class="card-body text-right">
                                            @if($etudiant->actif == 1 && $etudiant->traques == 0)
                                                <h4 class="mt-0" style="color: darkgreen">
                                                    ACTIF | NON TRAQUE
                                                </h4>
                                            @elseif($etudiant->actif == 0 && $etudiant->traques == 0)
                                                <h4 class="mt-0" style="color: darkred">
                                                    NON ACTIF | NON TRAQUE
                                                </h4>
                                            @elseif($etudiant->actif == 1 && $etudiant->traques == 1)
                                                <h4 class="mt-0">
                                                    ACTIF | TRAQUE
                                                </h4>
                                            @elseif($etudiant->actif == 0 && $etudiant->traques == 1)
                                                <h4 class="mt-0" style="color: darkorange">
                                                    NON ACTIF | TRAQUE
                                                </h4>
                                            @endif

                                            <p class="mb-0 text-muted">ETAT DU COMPTE</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
                <div class="col-md-6">
                    <div class="card card-default" style="background-image: url({{asset("assets/img/profile-bg.jpg")}})">
                        <div class="card-header"></div>
                        <div class="card-body text-center">
                            <img class="mb-2 img-fluid rounded-circle thumb64" @if(!is_null($etudiant->typePhoto) && !is_null($etudiant->photo)) src="data:{{ $etudiant->typePhoto ?? "" }};base64,{{ $cle->photo ?? ""}}" @else src="{{asset("assets/img/avatar.png")}}" @endif  alt="Photo">
                            <h4>{{ mb_strtoupper($etudiant->nom ?? '') . ' ' . mb_strtoupper($etudiant->prenoms ?? '' ) }}</h4>
                            <p>{{$etudiant->matricule . " | " . $etudiant->email }}</p>
                        </div>
                    </div>
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
                                    <th>Jour</th>
                                    <th>Service</th>
                                    <th>Prix</th>
                                    <th>Operateur</th>
                                    <th>Contact Opérateur</th>
                                    <th>Prestataire</th>
                                    <th>Site</th>
                                </tr>
                                </thead>
                                <tbody>

                                    @php($i = 1)
                                    @if($infostransactions->isNotEmpty())
                                        @foreach ($infostransactions as $key)
                                            <tr>
                                                <td>{{ $i ++ ?? '' }}</td>
                                                <td>{{\Carbon\Carbon::parse($key->created_at)->format('d.m.Y H:i:s') ?? '' }}</td>
                                                <td>{{ $key->libelleService ?? '' }}</td>
                                                <td>{{ $key->valeur ?? '' }}</td>
                                                <td>{{ mb_strtoupper($key->nomOperateur) . " " . mb_strtoupper($key->prenomsOperateur) ?? '' }}</td>
                                                <td>{{ $key->contactOperateur ?? '' }}</td>
                                                <td>{{ $key->libellePrestataire ?? '' }}</td>
                                                <td>{{ $key->localisationPrestataire ?? '' }}</td>
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

            let url = "";

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

                                window.location.href = "";

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
