<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.metas', ['title' => 'Détail du compte restaurant'])
    @include('layouts.css')
    @include('layouts.datatablescss')

    <style>
        .student-profile-card,
        .student-transactions-card {
            border: 0;
            border-radius: .65rem;
            box-shadow: 0 2px 12px rgba(30, 45, 65, .09);
            overflow: hidden;
        }

        .student-profile-card {
            background: linear-gradient(135deg, #ffffff 0%, #f4f7fb 100%);
        }

        .student-profile-body {
            align-items: center;
            display: flex;
            gap: 1.25rem;
            padding: 1.5rem;
        }

        .student-photo {
            align-items: center;
            background: #e8f1fc;
            border: 4px solid #fff;
            border-radius: 50%;
            box-shadow: 0 4px 14px rgba(30, 45, 65, .14);
            flex: 0 0 92px;
            justify-content: center;
            height: 92px;
            object-fit: cover;
            width: 92px;
        }

        .student-free-account-icon {
            color: #4a89dc;
            display: flex;
            font-size: 2rem;
        }

        .student-identity {
            min-width: 0;
        }

        .student-name {
            color: #293846;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.25;
            margin: 0 0 .45rem;
        }

        .student-reference,
        .student-email {
            align-items: center;
            color: #6c757d;
            display: flex;
            font-size: .88rem;
            margin-bottom: .3rem;
            overflow-wrap: anywhere;
        }

        .student-reference em,
        .student-email em {
            color: #4a89dc;
            margin-right: .5rem;
            width: 16px;
        }

        .student-account-status {
            border-radius: 1rem;
            display: inline-flex;
            font-size: .75rem;
            font-weight: 600;
            margin-top: .4rem;
            padding: .3rem .65rem;
        }

        .student-account-status.is-active {
            background: #e8f7ed;
            color: #228b43;
        }

        .student-account-status.is-inactive {
            background: #fff0f0;
            color: #c43b3b;
        }

        .student-account-status.is-tracked {
            background: #fff5df;
            color: #aa6b00;
        }

        .student-metrics {
            border-top: 1px solid #e8edf3;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .student-metric {
            border-right: 1px solid #e8edf3;
            padding: 1rem 1.25rem;
        }

        .student-metric:last-child {
            border-right: 0;
        }

        .student-metric-label {
            color: #87919c;
            display: block;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .035em;
            margin-bottom: .3rem;
            text-transform: uppercase;
        }

        .student-metric-value {
            color: #293846;
            display: block;
            font-size: 1rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .student-card-header {
            align-items: center;
            background: linear-gradient(135deg, #fff 0%, #f4f7fb 100%);
            border-bottom: 1px solid #e8edf3;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .student-card-title {
            color: #293846;
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: .2rem;
        }

        .student-card-subtitle {
            color: #6c757d;
            font-size: .85rem;
            margin: 0;
        }

        .student-transactions-card .card-body {
            padding: 0;
        }

        .student-transactions-table {
            margin-bottom: 0;
            min-width: 920px;
        }

        .student-transactions-table thead th {
            background: #f7f9fb;
            border-bottom: 1px solid #e5e9ef;
            border-top: 0;
            color: #657383;
            font-size: .73rem;
            letter-spacing: .035em;
            padding: .75rem;
            text-transform: uppercase;
            vertical-align: middle;
        }

        .student-transactions-table td {
            border-color: #edf0f4;
            padding: .75rem;
            vertical-align: middle;
        }

        #student-transactions-datatable_wrapper {
            min-width: 920px;
            padding: 1rem 1.25rem;
        }

        #student-transactions-datatable_wrapper .dataTables_filter input,
        #student-transactions-datatable_wrapper .dataTables_length select {
            border: 1px solid #d9e0e7;
            border-radius: .35rem;
            padding: .35rem .55rem;
        }

        #student-transactions-datatable_wrapper .dataTables_info,
        #student-transactions-datatable_wrapper .dataTables_paginate {
            padding-top: 1rem;
        }

        .transaction-main-value {
            color: #293846;
            font-weight: 600;
        }

        .transaction-meta {
            color: #87919c;
            display: block;
            font-size: .77rem;
            margin-top: .15rem;
        }

        .transaction-service {
            background: #e8f1fc;
            border-radius: 1rem;
            color: #3977bd;
            display: inline-flex;
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .65rem;
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .student-profile-body {
                align-items: flex-start;
                padding: 1.15rem;
            }

            .student-photo {
                flex-basis: 74px;
                height: 74px;
                width: 74px;
            }

            .student-name {
                font-size: 1.1rem;
            }

            .student-metrics {
                grid-template-columns: 1fr;
            }

            .student-metric {
                border-bottom: 1px solid #e8edf3;
                border-right: 0;
            }

            .student-metric:last-child {
                border-bottom: 0;
            }

            .student-card-header {
                align-items: stretch;
                flex-direction: column;
            }

            .student-card-header .btn {
                margin-top: .75rem;
                width: 100%;
            }
        }

        @media (max-width: 419.98px) {
            .student-profile-body {
                align-items: center;
                flex-direction: column;
                text-align: center;
            }

            .student-reference,
            .student-email {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
@php
    $estCompteLibre = isset($compteLibre);
    $profil = $estCompteLibre ? $compteLibre : $etudiant;
    $nomComplet = $estCompteLibre
        ? mb_strtoupper($profil->libelleCarteLibre ?? 'Compte libre')
        : trim(mb_strtoupper($profil->nom ?? '').' '.mb_strtoupper($profil->prenoms ?? ''));
    $rechargementAutomatique = mb_strtolower((string) ($profil->modeRechargement ?? '')) === 'auto';
    $compteActif = (int) ($profil->actif ?? 0) === 1;
    $compteTraque = (int) ($profil->traques ?? 0) === 1;
    $libelleEtat = ($compteActif ? 'Compte actif' : 'Compte inactif').($compteTraque ? ' · Traqué' : '');
    $classeEtat = $compteTraque ? 'is-tracked' : ($compteActif ? 'is-active' : 'is-inactive');
@endphp

<div class="wrapper">
    @include('layouts.topnavbar')
    @include('layouts.menu')
    @include('layouts.setting')

    <section class="section-container">
        <div class="content-wrapper">
            @include('layouts.content-heading', [
                'head' => 'Compte restaurant',
                'content' => '<a class="text-decoration-none" href="'.route('facturations.index').'">Facturation</a> / '.($estCompteLibre ? 'Détail compte libre' : 'Détail étudiant'),
                'localize' => 'dashboard.WELCOME'
            ])

            <div class="container-fluid">
                <div class="card student-profile-card mb-4">
                    <div class="student-profile-body">
                        @if($estCompteLibre)
                            <div class="student-photo student-free-account-icon" aria-hidden="true">
                                <em class="fa fa-credit-card"></em>
                            </div>
                        @else
                            <img
                                class="student-photo"
                                src="{{ !empty($profil->typePhoto) && !empty($profil->photo) ? 'data:'.$profil->typePhoto.';base64,'.$profil->photo : asset('assets/img/avatar.png') }}"
                                alt="Photo de {{ $nomComplet ?: 'l\'étudiant' }}"
                            >
                        @endif

                        <div class="student-identity">
                            <h1 class="student-name">{{ $nomComplet ?: ($estCompteLibre ? 'Compte libre' : 'Étudiant') }}</h1>
                            <div class="student-reference">
                                <em class="fa {{ $estCompteLibre ? 'fa-ticket-alt' : 'fa-id-card' }}" aria-hidden="true"></em>
                                <span>{{ $estCompteLibre ? 'Carte libre · Compte n° '.$profil->idCompte : ($profil->matricule ?? 'Matricule non renseigné') }}</span>
                            </div>
                            <div class="student-email">
                                <em class="fa {{ $estCompteLibre ? 'fa-building' : 'fa-envelope' }}" aria-hidden="true"></em>
                                <span>{{ $estCompteLibre ? ($profil->libelleDirection ?? 'Direction non renseignée') : ($profil->email ?? 'E-mail non renseigné') }}</span>
                            </div>
                            <span class="student-account-status {{ $classeEtat }}">
                                <em class="fa {{ $compteActif ? 'fa-check' : 'fa-ban' }} mr-1" aria-hidden="true"></em>
                                {{ $libelleEtat }}
                            </span>
                        </div>
                    </div>

                    <div class="student-metrics">
                        <div class="student-metric">
                            <span class="student-metric-label">Type de compte</span>
                            <span class="student-metric-value">{{ $profil->libelleTypeCompte ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="student-metric">
                            <span class="student-metric-label">Type de facturation</span>
                            <span class="student-metric-value">{{ $profil->libelleTypeFacturation ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="student-metric">
                            <span class="student-metric-label">{{ $estCompteLibre ? 'Capacité par service' : 'Solde disponible' }}</span>
                            <span class="student-metric-value">
                                @if($estCompteLibre)
                                    {{ number_format((int) ($profil->capacite ?? 0), 0, ',', ' ') }} passage{{ (int) ($profil->capacite ?? 0) > 1 ? 's' : '' }}
                                @else
                                    {{ $rechargementAutomatique ? 'Illimité' : number_format((float) ($profil->solde ?? 0), 0, ',', ' ').' FCFA' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card student-transactions-card">
                    <div class="student-card-header">
                        <div>
                            <div class="student-card-title" id="student-transaction-count">Historique des passages</div>
                            <p class="student-card-subtitle">Recherchez, triez et consultez les 30 derniers passages facturés.</p>
                        </div>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('facturations.index') }}">
                            <em class="fa fa-arrow-left mr-1" aria-hidden="true"></em>Retour à la facturation
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover student-transactions-table w-100" id="student-transactions-datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th>Date et heure</th>
                                    <th>Service</th>
                                    <th>Montant</th>
                                    <th>Opérateur</th>
                                    <th>Prestataire</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($infostransactions as $transaction)
                                    <tr>
                                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                        <td data-order="{{ \Carbon\Carbon::parse($transaction->created_at)->timestamp }}">
                                            <span class="transaction-main-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</span>
                                            <span class="transaction-meta">{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s') }}</span>
                                        </td>
                                        <td>
                                            <span class="transaction-service">{{ $transaction->libelleService ?? 'Non renseigné' }}</span>
                                        </td>
                                        <td data-order="{{ (float) ($transaction->valeur ?? 0) }}">
                                            <span class="transaction-main-value">{{ number_format((float) ($transaction->valeur ?? 0), 0, ',', ' ') }} FCFA</span>
                                        </td>
                                        <td>
                                            <span class="transaction-main-value">{{ trim(mb_strtoupper($transaction->nomOperateur ?? '').' '.mb_strtoupper($transaction->prenomsOperateur ?? '')) ?: 'Non renseigné' }}</span>
                                            <span class="transaction-meta">{{ $transaction->contactOperateur ?? 'Contact non renseigné' }}</span>
                                        </td>
                                        <td>
                                            <span class="transaction-main-value">
                                                {{ !empty($transaction->codePrestataire) ? '['.$transaction->codePrestataire.'] ' : '' }}{{ $transaction->libellePrestataire ?? 'Non renseigné' }}
                                            </span>
                                            <span class="transaction-meta">{{ $transaction->localisationPrestataire ?? 'Localisation non renseignée' }}</span>
                                        </td>
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

    @include('layouts.footer')
</div>

@include('layouts.js')
@include('layouts.datatablesjs')

<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';

        $('#student-transactions-datatable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 30], [10, 25, 30]],
            order: [[1, 'desc']],
            autoWidth: false,
            responsive: true,
            columnDefs: [
                { targets: 0, orderable: false, searchable: false, width: '52px' },
                { targets: 1, responsivePriority: 1 },
                { targets: 2, responsivePriority: 2 },
                { targets: 3, responsivePriority: 3 },
                { targets: 4, responsivePriority: 4 },
                { targets: 5, responsivePriority: 5 }
            ],
            language: {
                thousands: ' ',
                search: 'Rechercher dans les passages :',
                lengthMenu: 'Afficher _MENU_ passages',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ passages',
                infoEmpty: 'Aucun passage à afficher',
                infoFiltered: '(filtrés parmi _MAX_ passages)',
                emptyTable: @json($estCompteLibre ? 'Aucun passage enregistré pour ce compte libre.' : 'Aucun passage enregistré pour cet étudiant.'),
                zeroRecords: 'Aucun passage ne correspond à cette recherche.',
                paginate: { previous: 'Précédent', next: 'Suivant' }
            },
            drawCallback: function () {
                const informations = this.api().page.info();
                const total = new Intl.NumberFormat('fr-FR').format(informations.recordsDisplay);
                $('#student-transaction-count').text(total + ' passage' + (informations.recordsDisplay > 1 ? 's' : ''));
            }
        });
    });
</script>
</body>
</html>
