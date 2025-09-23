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
                <div class="col-md-3">
                    <!-- START card-->
                    <div class="card border-0">
                        <div class="row row-flush">
                            <div class="col-4 bg-info text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-coffee fa-2x"></em></div>
                            <div class="col-8">
                                <div class="card-body text-center">
                                    <h4 class="mt-0">{{$petitdejeuner->totalFacturations ?? 0}} {{$petitdejeuner->totalFacturations ?? 0 > 1 ? "Couverts" : "Couvert"}}</h4>
                                    <p class="mb-0 text-muted">{{$date . " - " . "PET-DEJ"}}</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
                <div class="col-md-3">
                    <!-- START card-->
                    <div class="card border-0">
                        <div class="row row-flush">
                            <div class="col-4 bg-danger text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-concierge-bell fa-2x"></em></div>
                            <div class="col-8">
                                <div class="card-body text-center">
                                    <h4 class="mt-0">{{$dejeuner->totalFacturations ?? 0}} {{$dejeuner->totalFacturations ?? 0> 1 ? "Couverts" : "Couvert"}}</h4>
                                    <p class="mb-0 text-muted">{{$date . " - " . mb_strtoupper($dejeuner->libelle ?? "")}}</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
                <div class="col-md-3">
                    <!-- START card-->
                    <div class="card border-0">
                        <div class="row row-flush">
                            <div class="col-4 bg-inverse text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-utensils fa-2x"></em></div>
                            <div class="col-8">
                                <div class="card-body text-center">
                                    <h4 class="mt-0">{{$diner->totalFacturations ?? 0}} {{$diner->totalFacturations ?? 0 > 1 ? "Couverts" : "Couvert"}}</h4>
                                    <p class="mb-0 text-muted">{{$date . " - " . mb_strtoupper($diner->libelle ?? "")}}</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
                <div class="col-md-3">
                    <!-- START card-->
                    <div class="card border-0">
                        <div class="row row-flush">
                            <div class="col-4 bg-green text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-chart-bar fa-2x"></em></div>
                            <div class="col-8">
                                <div class="card-body text-center">
                                    <h4 class="mt-0">{{$petitdejeuner->totalFacturations ?? 0 + $dejeuner->totalFacturations ?? 0 + $diner->totalFacturations ?? 0}}</h4>
                                    <p class="mb-0 text-muted">{{$date . " - " . "TOTAL"}}</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- START card-->
                    <div class="card card-default" id="cardDemo1">
                        <div class="card-header">
                            Sélectionner un mois
                            <a class="float-right" href="#" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse Card">
                                <em class="fa fa-minus"></em>
                            </a>
                        </div>
                        <div class="card-wrapper" style="max-height: 125px; transition: max-height 0.5s; overflow: hidden;">
                            <div class="card-body">
                                <div class="form-group col-md-12">
                                    <form action="" method="post">
                                        @csrf
                                        <label>Mois *</label>
                                        <select class="t form-control select2-mois" name="mois" id="mois" required>
                                            @foreach($moisOptions as $option)
                                                <option value="{{ $option['value'] }}"
                                                    {{ $option['value'] ==  \Carbon\Carbon::now()->format('n') ? 'selected' : '' }}>
                                                    {{ $option['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- END card-->
                </div>
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
                                    <th>N°</th>
                                    <th>Jour</th>
                                    <th>Petit-Déjeuner</th>
                                    <th>Déjeuner</th>
                                    <th>Diner</th>
                                    <th>Total</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)

                                    @if($facturationparmois->isNotEmpty())

                                        @foreach($facturationparmois as $cle)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $cle->jour }}</td>
                                                <td>{{ $cle->petit_dejeuner }}</td>
                                                <td>{{ $cle->dejeuner }}</td>
                                                <td>{{ $cle->diner }}</td>
                                                <td>{{ $cle->total }}</td>
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

        $('#mois').on('change', function () {
            let mois = $(this).val();
            let url = "{{ route('tableaudebord.recuperer') }}";

            $(".main-content").block();

            let table = $('#datatable2').DataTable();

            // Vider le tableau
            table.clear().draw();

            // Ajouter une ligne "Chargement..." qui occupe toute la largeur
            $('#datatable2 tbody').html('<tr><td colspan="6" class="text-center">Chargement...</td></tr>');

            $.ajax({
                url: url,
                type: "POST",
                data: { mois: mois, _token: '{{ csrf_token() }}' },
                dataType: "json",
                success: function (response) {
                    $(".main-content").unblock();

                    // Vider à nouveau avant d'afficher les nouvelles données
                    table.clear().draw();

                    Toast.fire({
                        title: "Récupération des données en cours",
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    if(response.success && response.data.length > 0){
                        let i = 1;
                        response.data.forEach(function(row){
                            table.row.add([
                                i++,
                                row.jour,
                                row.petit_dejeuner,
                                row.dejeuner,
                                row.diner,
                                row.total
                            ]).draw(false);
                        });
                    } else {
                        // Ligne unique qui prend toute la largeur
                        $('#datatable2 tbody').html('<tr><td colspan="6" class="text-center">Aucune donnée disponible pour ce mois.</td></tr>');
                    }
                },
                error: function (xhr) {
                    $(".main-content").unblock();
                    let errorHtml = "Une erreur est survenue.";
                    if(xhr.status === 422 && xhr.responseJSON.errors){
                        errorHtml = Object.values(xhr.responseJSON.errors).map(e => e.join("\n")).join("\n");
                    }
                    Swal.fire({
                        title: "Erreur!",
                        text: errorHtml,
                        icon: "error"
                    });
                }
            });
        });





    });

</script>

</body>
</html>
