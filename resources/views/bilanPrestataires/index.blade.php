
<!doctype html>
<html lang="fr">

<head>

    <title>{{mb_strtoupper($facturationparmois->first()->libellePrestataire ?? "")}}</title>
    <style>

        .logo{

            display: flex;
            justify-content: center;
            margin-top: 50px;

        }

        .titre{

            display: flex;
            justify-content: center;


        }

        .titre h4{

            text-align:center;
            border: 1.5px solid black;
            background-color: #eee;
            color: black;
            border-radius: 5px;
            padding: 5px;

        }

        .logo img{

            height: auto;
            width: 80%;

        }

        .content{

            margin-top: 20px;

        }

        .kvi {
            width: 100%;
            border-collapse: collapse;
            padding: 5px;
        }

        .kvi th, .kvi td {
            border: 0.5px solid #cccccc;
            padding: 4px 6px;
            text-align: left;
        }

        .kvi th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .centrer td{

            text-align: center;

        }

        .total{

            background: #f2f2f2;
            font-weight: bold;

        }

        .chef{

            display: flex;
            justify-content: flex-end;

        }
    </style>
</head>

<body onload="javascript:window.print(),fermer()">

<div id="layout-wrapper">



    <!-- Start right Content here -->

    <div  class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="logo">
                        <img src="{{asset("assets/img/logoinphb2.png")}}" alt="">
                    </div>

                </div>

                <div class="content">

                    <div class="col-12">
                        <div class="card">
                            <div class="titre">
                                <h4 class="card-title ">BILAN DU MOIS DE {{mb_strtoupper($moisselectionne) . " " . $anneeselectionne}} ({{mb_strtoupper($facturationparmois->first()->libellePrestataire ?? "")}})</h4>
                            </div>
                            <div class="card-body">

                                <table  class="kvi" style="margin-top: 3px">
                                    <thead>

                                    <tr>
                                        <th class="text-center">N°</th>
                                        <th class="text-center">Jour</th>
                                        <th class="text-center">Petit déjeûner</th>
                                        <th class="text-center">Déjeûner</th>
                                        <th class="text-center">Dîner</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        @php($i = 1)

                                        @if($facturationparmois->isNotEmpty() && !is_null($totalservicesparMois))

                                            @foreach($facturationparmois as $cle)
                                                <tr class="centrer">
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{\Carbon\Carbon::parse($cle->jour)->format('d-m-Y')}}</td>
                                                    <td>{{ $cle->petit_dejeuner }}</td>
                                                    <td>{{ $cle->dejeuner }}</td>
                                                    <td>{{ $cle->diner }}</td>
                                                    <td style="font-weight: bold">{{ $cle->total }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="centrer total">
                                                <td>{{ $i }}</td>
                                                <td>Total</td>
                                                <td>{{ $totalservicesparMois->petit_dejeuner }}</td>
                                                <td>{{ $totalservicesparMois->dejeuner }}</td>
                                                <td>{{ $totalservicesparMois->diner }}</td>
                                                <td>{{ $totalservicesparMois->total }}</td>
                                            </tr>

                                        @endif

                                    </tbody>
                                </table>
                                <div class="chef">
                                    <h4 class="card-title float-end">Chef du service restauration</h4>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->

                </div>
                <!-- end container-fluid -->
            </div>
            <!-- End Page-content -->


        </div>
        <!-- end main content-->
    </div>
    <!-- end layout-wrapper -->



    <script type="text/javascript">
        function fermer(){
            setInterval(function(){ open(location, '_self').close() },3000);
        }

    </script>
</body>

</html>
