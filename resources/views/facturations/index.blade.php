<!DOCTYPE html>
<html lang="fr">

<head>

    @include('layouts.metas',['title'=>'Tableau de bord'])

    @include('layouts.css')
    @include('layouts.datatablescss')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .billing-page {
            background: #f5f7fa;
        }

        .billing-hero {
            align-items: center;
            background: linear-gradient(135deg, #172b4d 0%, #2458a6 58%, #0b94b6 100%);
            border-radius: .9rem;
            box-shadow: 0 10px 28px rgba(23, 43, 77, .18);
            color: #fff;
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            overflow: hidden;
            padding: 1.35rem 1.5rem;
            position: relative;
        }

        .billing-hero::after {
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
            content: '';
            height: 180px;
            position: absolute;
            right: -45px;
            top: -80px;
            width: 180px;
        }

        .billing-hero-copy,
        .billing-live-badge {
            position: relative;
            z-index: 1;
        }

        .billing-hero-eyebrow {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            margin-bottom: .3rem;
            opacity: .75;
            text-transform: uppercase;
        }

        .billing-hero h2 {
            color: #fff;
            font-size: 1.35rem;
            margin: 0 0 .3rem;
        }

        .billing-hero p {
            margin: 0;
            opacity: .78;
        }

        .billing-live-badge {
            align-items: center;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 2rem;
            display: inline-flex;
            font-size: .78rem;
            font-weight: 600;
            padding: .5rem .8rem;
            white-space: nowrap;
        }

        .billing-live-dot {
            background: #5af0a5;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(90, 240, 165, .17);
            height: 8px;
            margin-right: .55rem;
            width: 8px;
        }

        .billing-card {
            border: 0;
            border-radius: .75rem;
            box-shadow: 0 2px 12px rgba(30, 45, 65, .08);
            height: calc(100% - 1.25rem);
            margin-bottom: 1.25rem;
            overflow: hidden;
        }

        .billing-summary-card {
            background: #fff;
            padding: 1.35rem;
        }

        .billing-summary-layout {
            align-items: center;
            display: flex;
        }

        .billing-summary-icon,
        .billing-section-icon {
            align-items: center;
            background: #e9f2ff;
            border-radius: .7rem;
            color: #2872d0;
            display: inline-flex;
            flex: 0 0 52px;
            height: 52px;
            justify-content: center;
            margin-right: 1rem;
            min-width: 52px;
            width: 52px;
        }

        .billing-summary-number {
            color: #25364d;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: .35rem;
        }

        .billing-summary-label {
            color: #8994a3;
            font-size: .75rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .billing-summary-meta {
            border-left: 1px solid #edf0f4;
            margin-left: auto;
            min-width: 200px;
            padding-left: 1.25rem;
        }

        .billing-summary-meta strong {
            color: #33475f;
            display: block;
            font-size: .9rem;
            margin-bottom: .3rem;
        }

        .billing-summary-meta span {
            color: #8994a3;
            font-size: .82rem;
        }

        .billing-scan-card {
            align-items: center;
            background: linear-gradient(145deg, #fff 0%, #f4fbfd 100%);
            display: flex;
            justify-content: space-between;
            padding: 1.35rem;
        }

        .billing-scan-card h4 {
            color: #293846;
            font-size: 1rem;
            margin: 0 0 .35rem;
        }

        .billing-scan-card p {
            color: #87919c;
            font-size: .82rem;
            margin: 0;
        }

        .billing-scan-button {
            align-items: center;
            border-radius: .55rem;
            display: inline-flex;
            font-weight: 600;
            margin-left: 1rem;
            padding: .7rem 1rem;
            white-space: nowrap;
        }

        .billing-panel-header {
            align-items: center;
            background: linear-gradient(135deg, #fff 0%, #f7f9fc 100%);
            border-bottom: 1px solid #e8edf3;
            display: flex;
            padding: 1rem 1.25rem;
        }

        .billing-panel-header .billing-section-icon {
            flex-basis: 40px;
            height: 40px;
            min-width: 40px;
            width: 40px;
        }

        .billing-panel-title {
            color: #293846;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 .15rem;
        }

        .billing-panel-subtitle {
            color: #87919c;
            font-size: .8rem;
            margin: 0;
        }

        .billing-pin-card .card-body {
            padding: 1.25rem;
        }

        .billing-pin-label {
            color: #3f4d5a;
            font-weight: 600;
        }

        .billing-pin-input {
            border: 1px solid #dce3ea;
            border-radius: .55rem 0 0 .55rem;
            font-size: 1.1rem;
            height: 48px;
            letter-spacing: .12em;
        }

        .billing-pin-submit {
            border-radius: 0 .55rem .55rem 0;
            min-width: 120px;
        }

        .billing-activity-card {
            height: auto;
        }

        .billing-activity-list {
            display: grid;
            gap: .8rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            max-height: 460px;
            overflow-y: auto;
            padding: 1rem;
        }

        .billing-passage-card {
            align-items: center;
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: .7rem;
            color: inherit;
            display: flex;
            min-width: 0;
            padding: .85rem;
            text-decoration: none !important;
            transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .billing-passage-card:hover {
            border-color: #bdd5f1;
            box-shadow: 0 5px 14px rgba(37, 74, 120, .09);
            color: inherit;
            transform: translateY(-1px);
        }

        .billing-passage-avatar {
            flex: 0 0 46px;
            height: 46px;
            margin-right: .8rem;
            position: relative;
            width: 46px;
        }

        .billing-passage-avatar img {
            border: 2px solid #eef3f8;
            border-radius: 50%;
            height: 46px;
            object-fit: cover;
            width: 46px;
        }

        .billing-passage-count {
            align-items: center;
            background: #2872d0;
            border: 2px solid #fff;
            border-radius: 1rem;
            bottom: -4px;
            color: #fff;
            display: inline-flex;
            font-size: .65rem;
            font-weight: 700;
            height: 21px;
            justify-content: center;
            min-width: 21px;
            padding: 0 .25rem;
            position: absolute;
            right: -4px;
        }

        .billing-passage-content {
            min-width: 0;
            width: 100%;
        }

        .billing-passage-topline {
            align-items: center;
            display: flex;
            gap: .5rem;
            justify-content: space-between;
            margin-bottom: .25rem;
        }

        .billing-passage-name {
            color: #2d3e53;
            font-size: .88rem;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .billing-passage-time {
            background: #f0f4f8;
            border-radius: 1rem;
            color: #6d7b8b;
            flex: 0 0 auto;
            font-size: .7rem;
            padding: .22rem .45rem;
        }

        .billing-passage-reference {
            color: #2872d0;
            display: block;
            font-size: .75rem;
            font-weight: 600;
            margin-bottom: .25rem;
        }

        .billing-passage-meta {
            color: #8a96a4;
            font-size: .73rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .billing-empty-state {
            color: #8b97a6;
            grid-column: 1 / -1;
            padding: 3rem 1rem;
            text-align: center;
        }

        .billing-empty-state em {
            color: #c3ccd6;
            display: block;
            font-size: 2rem;
            margin-bottom: .75rem;
        }

        .billing-camera-frame {
            background: #111827;
            border-radius: .75rem;
            overflow: hidden;
            position: relative;
        }

        .billing-camera-frame video {
            display: block;
            max-height: 420px;
            object-fit: cover;
            width: 100%;
        }

        .billing-camera-guide {
            border: 3px solid rgba(255, 255, 255, .8);
            border-radius: .75rem;
            height: 190px;
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 190px;
        }

        @media (max-width: 767.98px) {
            .billing-hero,
            .billing-scan-card,
            .billing-summary-layout {
                align-items: flex-start;
                flex-direction: column;
            }

            .billing-live-badge,
            .billing-scan-button {
                margin-left: 0;
                margin-top: 1rem;
            }

            .billing-summary-meta {
                border-left: 0;
                border-top: 1px solid #edf0f4;
                margin-left: 0;
                margin-top: 1rem;
                min-width: 0;
                padding-left: 0;
                padding-top: 1rem;
                width: 100%;
            }

            .billing-summary-icon {
                flex-basis: 52px;
                margin-bottom: .85rem;
            }

            .billing-activity-list {
                gap: .65rem;
                grid-template-columns: 1fr;
                max-height: 520px;
                padding: .75rem;
            }

            .billing-passage-card {
                padding: .75rem;
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
        <div class="content-wrapper billing-page">

            @include('layouts.content-heading',['head'=>'Restauration','content'=>'<a class="text-decoration-none" href="">Facturation</a>','localize'=>'dashboard.WELCOME'])
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="billing-hero">
                <div class="billing-hero-copy">
                    <div class="billing-hero-eyebrow">Espace de facturation</div>
                    <h2>Enregistrez les passages simplement</h2>
                    <p>Scannez un QR code ou saisissez le code confidentiel du compte.</p>
                </div>
                <div class="billing-live-badge">
                    <span class="billing-live-dot"></span>
                    Service opérationnel
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card billing-card billing-summary-card">
                        <div class="billing-summary-layout">
                            <span class="billing-summary-icon"><em class="fa fa-utensils fa-lg"></em></span>
                            <div>
                                <div class="billing-summary-number" id="served-count">{{ $transactionsoperateur->totalTransaction ?? 0 }}</div>
                                <div class="billing-summary-label">Repas servis aujourd’hui</div>
                            </div>
                            <div class="billing-summary-meta">
                                <strong id="current-service">{{ mb_strtoupper($services->libelle ?? 'Aucun service') }}</strong>
                                <span>Tarif : <span id="current-price">{{ $services->valeur ?? 0 }}</span> FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card billing-card billing-scan-card">
                        <div class="d-flex align-items-center">
                            <span class="billing-section-icon"><em class="fa fa-qrcode fa-lg"></em></span>
                            <div>
                                <h4>Lecture par caméra</h4>
                                <p>Présentez le QR code devant l’objectif.</p>
                            </div>
                        </div>
                        <button id="btn-scan" class="btn btn-info billing-scan-button" type="button">
                            <em class="fa fa-camera mr-2"></em>Scanner
                        </button>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card billing-card billing-pin-card" id="cardDemo1">
                        <div class="billing-panel-header">
                            <span class="billing-section-icon"><em class="fa fa-key"></em></span>
                            <div>
                                <div class="billing-panel-title">Facturation par code</div>
                                <p class="billing-panel-subtitle">Saisissez le code PIN ou le token de validation.</p>
                            </div>
                        </div>
                        <div class="card-wrapper">
                            <div class="card-body">
                                <form action="" method="post" id="ajoutParCodePin">
                                        @csrf
                                    <label class="billing-pin-label" for="codePin">Code PIN ou token de validation <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control billing-pin-input" name="code" id="codePin" inputmode="numeric" autocomplete="off" placeholder="Saisir le code" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary billing-pin-submit" id="pin-submit" type="submit">
                                                <span class="spinner-border spinner-border-sm mr-1 d-none" id="pin-loader" aria-hidden="true"></span>
                                                <em class="fa fa-check mr-1" id="pin-submit-icon"></em>
                                                Valider
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Seuls les chiffres sont acceptés.</small>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">

                <div class="col-md-12">

                    <div class="card billing-card billing-activity-card">
                        <div class="billing-panel-header">
                            <span class="billing-section-icon"><em class="fa fa-history"></em></span>
                            <div>
                                <div class="billing-panel-title">Derniers passages enregistrés</div>
                                <p class="billing-panel-subtitle">Les 10 dernières facturations effectuées par cet opérateur.</p>
                            </div>
                        </div>
                        <div id="etudiants-list" class="billing-activity-list">
                            @if($etudiantfactureparoperateur->isNotEmpty())
                                @foreach($etudiantfactureparoperateur as $cle)
                                    @php
                                        $lienDetail = !is_null($cle->idEtudiant) ? route('afficher.etudiants', ['id' => $cle->idEtudiant])
                                            : (!is_null($cle->idCompteRestau) ? route('afficher.comptes-libres', ['id' => $cle->idCompteRestau]) : '#');
                                    @endphp
                                    <a class="billing-passage-card" href="{{ $lienDetail }}">
                                        <div class="billing-passage-avatar">
                                            <img src="{{ !empty($cle->photo) ? 'data:' . ($cle->typePhoto ?? 'image/jpeg') . ';base64,' . $cle->photo : asset('assets/img/avatar.png') }}" alt="Photo">
                                            <span class="billing-passage-count">{{ $cle->totalTransactions }}</span>
                                        </div>
                                        <div class="billing-passage-content">
                                            <div class="billing-passage-topline">
                                                <span class="billing-passage-name">
                                                    {{ !is_null($cle->matricule) ? trim(($cle->nom ?? '').' '.($cle->prenoms ?? '')) : ($cle->libelleDirection ?? 'Compte libre') }}
                                                </span>
                                                <time class="billing-passage-time">{{ \Carbon\Carbon::parse($cle->dateTransaction)->format('H:i:s') }}</time>
                                            </div>
                                            <span class="billing-passage-reference">{{ $cle->matricule ?? 'Compte libre' }}</span>
                                            <div class="billing-passage-meta">
                                                {{ $cle->telephone ?? 'Contact non renseigné' }} · Opérateur : {{ $cle->nomOperateur ?? '' }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="billing-empty-state">
                                    <em class="fa fa-receipt"></em>
                                    Aucun passage enregistré pour le moment.
                                </div>
                            @endif
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
        <div class="modal-content border-0" style="border-radius: .8rem; overflow: hidden;">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Scanner le QR code</h5>
                    <small class="text-muted">Placez le code à l’intérieur du cadre.</small>
                </div>
                <button class="close" id="btn-close-scan" type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="billing-camera-frame">
                    <video id="qr-video" height="380" autoplay></video>
                    <canvas id="qr-canvas" style="display:none;"></canvas>
                    <div class="billing-camera-guide" aria-hidden="true"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger" id="btn-stop-scan" type="button">
                    <em class="fa fa-stop-circle mr-1"></em>Arrêter la caméra
                </button>
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

        function mettreAJourResume(transaction) {
            if (!transaction) {
                return;
            }

            $('#served-count').text(transaction.totalTransaction || 0);
            $('#current-service').text((transaction.libelleService || 'Aucun service').toString().toUpperCase());
            $('#current-price').text(transaction.valeur || 0);
        }

        const urlDetailEtudiant = @json(route('afficher.etudiants', ['id' => '__ID__']));
        const urlDetailCompteLibre = @json(route('afficher.comptes-libres', ['id' => '__ID__']));

        function rafraichirTransactions() {
            $.ajax({
                url: "{{ route('facturations.refresh') }}",
                method: "GET",
                success: function(response) {
                    mettreAJourResume(response.transactionsoperateur);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

// Lancer la mise à jour toutes les 5 secondes (5000ms)
        setInterval(rafraichirTransactions, 5000);

        if (window.Echo) {
            window.Echo.channel('transactions')
                .listen('.TransactionUpdated', function (event) {
                    mettreAJourResume(event.transactionsoperateur);
                });
        }


        let video = document.getElementById("qr-video");
        let canvas = document.getElementById("qr-canvas");
        let context = canvas.getContext("2d");
        let scanning = false;
        let cameraAutorisee = false;
        let modalScan;
        let bipAudio = new Audio('{{ asset("assets/music/sonqrcode.mp3") }}');

        //Démarre la caméra
        function lancerCamera() {
            cameraAutorisee = true;

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                    .then(function (stream) {
                        if (!cameraAutorisee) {
                            stream.getTracks().forEach(track => track.stop());
                            return;
                        }

                        video.srcObject = stream;
                        video.setAttribute("playsinline", true); // iOS
                        video.play();
                        scanning = true;
                        tick();
                    })
                    .catch(function (err) {
                        if (cameraAutorisee) {
                            Swal.fire("Erreur caméra", err.message, "error");
                        }
                    });
            } else {
                cameraAutorisee = false;
                Swal.fire("Erreur", "Caméra non supportée par ce navigateur", "error");
            }
        }

        //Stoppe proprement la caméra
        function arreterCamera() {
            cameraAutorisee = false;
            scanning = false;
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        //Premier clic sur bouton
        $("#btn-scan").on("click", function () {
            modalScan = new bootstrap.Modal(document.getElementById('modalScan'));
            modalScan.show();
            lancerCamera();
        });

        // Arrêt explicite puis fermeture du modal.
        $('#btn-stop-scan').on('click', function () {
            arreterCamera();

            if (modalScan && typeof modalScan.hide === 'function') {
                modalScan.hide();
            } else {
                $('#modalScan').modal('hide');
            }
        });

        // La croix arrête immédiatement le flux.
        $('#btn-close-scan').on('click', arreterCamera);

        // Toute fermeture (croix, bouton, clic sur l'arrière-plan ou touche Échap)
        // coupe également la caméra. Les deux écoutes assurent la compatibilité
        // avec les événements Bootstrap 4 et Bootstrap 5.
        const modalScanElement = document.getElementById('modalScan');
        modalScanElement.addEventListener('hide.bs.modal', arreterCamera);
        $('#modalScan').on('hide.bs.modal hidden.bs.modal', arreterCamera);

        // Boucle de capture et scan
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

                    //bip sonore
                    bipAudio.play();

                    // Stop caméra pour éviter les lectures multiples
                    arreterCamera();

                    // Envoi Ajax vers Laravel
                    $.ajax({
                        url: "{{ route('facturations.scanqrcode') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            code: code.data
                        },
                        success: function (response) {
                            majUI(response);

                            // Relancer automatiquement la caméra si modal encore ouvert
                            if ($('#modalScan').hasClass('show')) {
                                lancerCamera();
                            }
                        },
                        error: function(xhr) {
                            let errorHtml = "Une erreur est survenue.";
                            if(xhr.status === 422 && xhr.responseJSON.errors){
                                errorHtml = Object.values(xhr.responseJSON.errors).map(arr => arr.join("\n")).join("\n");
                            } else if(xhr.responseJSON?.message){
                                errorHtml = xhr.responseJSON.message;
                            }
                            Swal.fire({ icon: 'error', title: 'Erreur', text: errorHtml });

                            // Relancer automatiquement la caméra si modal encore ouvert
                            if ($('#modalScan').hasClass('show')) {
                                lancerCamera();
                            }
                        }
                    });
                }
            }

            requestAnimationFrame(tick);
        }



        $('#ajoutParCodePin').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const $bouton = $('#pin-submit');
            $bouton.prop('disabled', true);
            $('#pin-loader').removeClass('d-none');
            $('#pin-submit-icon').addClass('d-none');

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
                },
                complete: function () {
                    $bouton.prop('disabled', false);
                    $('#pin-loader').addClass('d-none');
                    $('#pin-submit-icon').removeClass('d-none');
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
            mettreAJourResume(response.transactionsoperateur);

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

                    const nomComplet = etu.matricule
                        ? `${etu.nom || ''} ${etu.prenoms || ''}`.trim()
                        : (etu.libelleDirection || 'Compte libre');
                    const lien = etu.idEtudiant
                        ? urlDetailEtudiant.replace('__ID__', encodeURIComponent(etu.idEtudiant))
                        : (etu.idCompteRestau
                            ? urlDetailCompteLibre.replace('__ID__', encodeURIComponent(etu.idCompteRestau))
                            : '#');
                    const photo = etu.photo
                        ? `data:${etu.typePhoto || 'image/jpeg'};base64,${etu.photo}`
                        : @json(asset('assets/img/avatar.png'));

                    const $carte = $('<a>', {
                        class: 'billing-passage-card',
                        href: lien
                    });
                    const $avatar = $('<div>', { class: 'billing-passage-avatar' })
                        .append($('<img>', { src: photo, alt: 'Photo' }).on('error', function () {
                            this.src = @json(asset('assets/img/avatar.png'));
                        }))
                        .append($('<span>', { class: 'billing-passage-count', text: etu.totalTransactions || 0 }));
                    const $topline = $('<div>', { class: 'billing-passage-topline' })
                        .append($('<span>', { class: 'billing-passage-name', text: nomComplet }))
                        .append($('<time>', { class: 'billing-passage-time', text: heureFormatee }));
                    const $contenu = $('<div>', { class: 'billing-passage-content' })
                        .append($topline)
                        .append($('<span>', {
                            class: 'billing-passage-reference',
                            text: etu.matricule || 'Compte libre'
                        }))
                        .append($('<div>', {
                            class: 'billing-passage-meta',
                            text: `${etu.telephone || 'Contact non renseigné'} · Opérateur : ${etu.nomOperateur || ''}`
                        }));

                    container.append($carte.append($avatar, $contenu));
                });
            }

        }


    });

</script>

</body>
</html>
