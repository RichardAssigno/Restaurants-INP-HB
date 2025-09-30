<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Tableau de bord'])

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
                    <div class="card border-0">
                        <div class="row row-flush">
                            <div class="col-4 bg-info text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-coffee fa-2x"></em></div>
                            <div class="col-8">
                                <div class="card-body text-center">
                                    <h4 class="mt-0">Nombres de repas servis : {{mb_strtoupper($transactionsoperateur->totalTransaction ?? 0)}}</h4>
                                    <p class="mb-0 text-muted">Service en cours : {{mb_strtoupper($transactionsoperateur->libelleService ?? "")}}</p>
                                    <p class="mb-0 text-muted">Prix : {{$transactionsoperateur->valeur ?? ""}} FCFA</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
                <div class="col-md-6">
                    <div class="card border-0">
                        <div class="row row-flush">
                            <div class="col-4 bg-danger text-center d-flex align-items-center justify-content-center rounded-left">
                                <em class="fa fa-qrcode fa-2x"></em>
                            </div>
                            <div class="col-8">
                                <div class="card-body text-center">
                                    <h4 class="">
                                        <button id="btn-scan" class="btn btn-outline-info btn-lg btn-edit">
                                            Lecture
                                        </button>
                                    </h4>
                                    <p class=" text-muted" style="margin-bottom: 0">Cliquez sur le bouton pour lire le code QR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <!-- START card-->
                    <div class="card card-default" id="cardDemo1">
                        <div class="card-header">
                            Entrez votre Code Pin ou le Token de Validation
                            <a class="float-right" href="#" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse Card">
                                <em class="fa fa-minus"></em>
                            </a>
                        </div>
                        <div class="card-wrapper" style="max-height: 125px; transition: max-height 0.5s; overflow: hidden;">
                            <div class="card-body">
                                <div class="form-group col-md-12">
                                    <form action="" method="post" id="ajoutParCodePin">
                                        @csrf
                                        <div class="row mb-3">
                                            <label>Code Pin ou le Token de Validation *</label>
                                            <input type="password" class="form-control" name="code" id="codePin" inputmode="numeric" required>
                                            <br>
                                        </div>
                                        <div class="col-auto text-center">
                                            <button class="btn btn-primary mb-2" type="submit">
                                                Valider
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
            </div>

            <div class="row justify-content-center">

                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="card card-default">
                        <div class="card-header">
                            <div class="card-title">Les 10 dernières personnes enrégistrées</div>
                        </div><!-- START list group-->
                        <div id="etudiants-list" class="slimScrollDiv">
                            @if($etudiantfactureparoperateur->isNotEmpty())
                                @foreach($etudiantfactureparoperateur as $cle)
                                    <a href="{{route("afficher.etudiants", ["id" => $cle->idEtudiant])}}" style="text-decoration: none">
                                        <div class="list-group-item list-group-item-action">
                                            <div class="media">
                                                <img class="align-self-start mx-2 circle thumb32" src="data:{{ $cle->typePhoto ?? "" }};base64,{{ $cle->photo ?? ""}}" alt="Photo">

                                                <div class="media-body text-truncate">
                                                    <p class="mb-1">
                                                        <strong class="text-primary">
                                                            <span class="circle bg-success circle-lg text-left"></span>
                                                            <span>{{" ( " . $cle->totalTransactions . " ) " . $cle->matricule . " | " . $cle->nom . " " . $cle->prenoms }}</span>
                                                        </strong>
                                                    </p>
                                                    <p class="mb-1 text-sm">
                                                        {{ $cle->telephone . " | Opérateur : " . $cle->nomOperateur }}
                                                    </p>
                                                </div>
                                                <div class="ml-auto"><small class="text-muted ml-2">{{ \Carbon\Carbon::parse($cle->dateTransaction)->format('H:i:s') }}</small></div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <p class="text-muted text-center">Aucun étudiant trouvé</p>
                            @endif
                        </div>

                        <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 103.514px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;">

                        </div>
                    </div><!-- END list group-->
                </div>
            </div>

            {{--<div class="row">

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
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Prénoms</th>
                                    <th>Téléphone</th>
                                    <th>Opérateur</th>
                                    <th>NBR Fcturation</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)

                                    @if($etudiantfactureparoperateur->isNotEmpty())

                                        @foreach($etudiantfactureparoperateur as $cle)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $cle->matricule }}</td>
                                                <td>{{ $cle->nom }}</td>
                                                <td>{{ $cle->prenoms }}</td>
                                                <td>{{ $cle->telephone }}</td>
                                                <td>{{ $cle->nomOperateur }}</td>
                                                <td>{{ $cle->totalTransactions }}</td>
                                            </tr>
                                        @endforeach

                                    @endif
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>--}}
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

    // ✅ Bloquer la saisie autre que numérique
    $(document).on("input", "#codePin", function() {
        this.value = this.value.replace(/\D/g, ""); // enlève tout sauf chiffres
    });

    $(document).on("keypress", "#codePin", function(e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });

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

        function rafraichirTransactions() {
            $.ajax({
                url: "{{ route('facturations.refresh') }}",
                method: "GET",
                success: function(response) {
                    if (response.transactionsoperateur) {
                        $(".mt-0").text(
                            "Nombres de repas servis : " +
                            (response.transactionsoperateur.totalTransaction || 0).toString().toUpperCase()
                        );
                        $(".mb-0.text-muted:first").text(
                            "Service en cours : " + (response.transactionsoperateur.libelleService || "")
                        );
                        $(".mb-0.text-muted:last").text(
                            "Prix : " + (response.transactionsoperateur.valeur || "") + " FCFA"
                        );
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

// Lancer la mise à jour toutes les 5 secondes (5000ms)
        setInterval(rafraichirTransactions, 5000);


        let video = document.getElementById("qr-video");
        let canvas = document.getElementById("qr-canvas");
        let context = canvas.getContext("2d");
        let scanning = false;
        let modalScan; // pour stocker l'instance du modal

        $("#btn-scan").on("click", function () {
            // Crée ou récupère l'instance du modal
            modalScan = new bootstrap.Modal(document.getElementById('modalScan'));
            modalScan.show();

            // Lance la caméra
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                    .then(function (stream) {
                        video.srcObject = stream;
                        video.setAttribute("playsinline", true); // iOS
                        video.play();
                        scanning = true;
                        tick();
                    })
                    .catch(function (err) {
                        Swal.fire("Erreur caméra", err.message, "error");
                    });
            } else {
                Swal.fire("Erreur", "Caméra non supportée par ce navigateur", "error");
            }
        });

        function tick() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                let imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                let code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    scanning = false;

                    // Stop caméra
                    video.srcObject.getTracks().forEach(track => track.stop());

                    // Ferme le modal
                    if(modalScan) {
                        modalScan.hide();
                    }

                    // Envoi vers Laravel
                    $.ajax({
                        url: "{{ route('facturations.scanqrcode') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            code: code.data
                        },
                        success: function (response) {
                            majUI(response);
                        },

                        error: function(xhr) {
                            let errorHtml = "Une erreur est survenue.";
                            if(xhr.status === 422 && xhr.responseJSON.errors){
                                errorHtml = Object.values(xhr.responseJSON.errors).map(arr => arr.join("\n")).join("\n");
                            } else if(xhr.responseJSON?.message){
                                errorHtml = xhr.responseJSON.message;
                            }
                            Swal.fire({ icon: 'error', title: 'Erreur', text: errorHtml });
                        }
                    });
                }
            }

            requestAnimationFrame(tick);
        }


        $('#ajoutParCodePin').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: "{{ route('facturations.scanqrcode') }}",
                method: "POST",
                data: formData,
                success: function (response) {
                    majUI(response);

                    $('#ajoutParCodePin input[name="code"]').val('');

                    $('#ajoutParCodePin input[name="code"]').focus();
                },
                error: function(xhr) {
                    let errorHtml = "Une erreur est survenue.";
                    if(xhr.status === 422 && xhr.responseJSON.errors){
                        errorHtml = Object.values(xhr.responseJSON.errors).map(arr => arr.join("\n")).join("\n");
                    } else if(xhr.responseJSON?.message){
                        errorHtml = xhr.responseJSON.message;
                    }
                    Swal.fire({ icon: 'error', title: 'Erreur', text: errorHtml });
                }
            });
        });


        function majUI(response) {
            // Toast succès
            Toast.fire({
                title: response.message || 'Connexion réussie',
                position: "top-end",
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });

            // 🔹 Mise à jour nombre de repas et service en cours
            if (response.transactionsoperateur) {
                $(".mt-0").text("Nombres de repas servis : " + (response.transactionsoperateur.totalTransaction || 0).toString().toUpperCase());
                $(".mb-0.text-muted:first").text("Service en cours : " + (response.transactionsoperateur.libelleService || ""));
                $(".mb-0.text-muted:last").text("Prix : " + (response.transactionsoperateur.valeur || "") + " FCFA");
            }

            // 🔹 Mise à jour tableau DataTable
            if (response.etudiantfactureparoperateur) {

                let container = $("#etudiants-list");
                container.empty();

                response.etudiantfactureparoperateur.forEach(function (etu) {
                    // convertir la date envoyée par Laravel en objet Date
                    let dateObj = new Date(etu.dateTransaction);

                    // Formatter en H:i:s
                    let heures   = String(dateObj.getHours()).padStart(2, '0');
                    let minutes  = String(dateObj.getMinutes()).padStart(2, '0');
                    let secondes = String(dateObj.getSeconds()).padStart(2, '0');

                    let heureFormatee = `${heures}:${minutes}:${secondes}`;

                    let item = `
            <div class="list-group-item list-group-item-action">
                <div class="media">
                    <img class="align-self-start mx-2 circle thumb32"
                         src="data:${etu.typePhoto};base64,${etu.photo}"
                         alt="Photo">
                    <div class="media-body text-truncate">
                        <p class="mb-1">
                            <strong class="text-primary">
                                <span class="circle bg-success circle-lg text-left"></span>
                                <span> ( ${etu.totalTransactions} ) ${etu.matricule} | ${etu.nom} ${etu.prenoms}</span>
                            </strong>
                        </p>
                        <p class="mb-1 text-sm">
                             ${etu.telephone} | Opérateur : ${etu.nomOperateur}
                        </p>
                    </div>
                    <div class="ml-auto">
                        <small class="text-muted ml-2">${heureFormatee}</small>
                    </div>
                </div>
            </div>
        `;
                    container.append(item);
                });
            }

        }


    });

</script>

</body>
</html>
