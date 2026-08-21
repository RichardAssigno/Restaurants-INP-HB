<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.metas', ['title' => 'Gestion des administrateurs'])
    @include('layouts.css')
    @include('layouts.datatablescss')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .operator-form-card,
        .operator-results-card {
            border: 0;
            border-radius: .65rem;
            box-shadow: 0 2px 12px rgba(30, 45, 65, .09);
            overflow: hidden;
        }

        .operator-card-header {
            align-items: center;
            background: linear-gradient(135deg, #fff 0%, #f4f7fb 100%);
            border-bottom: 1px solid #e8edf3;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .operator-card-title {
            color: #293846;
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: .2rem;
        }

        .operator-card-subtitle {
            color: #6c757d;
            font-size: .85rem;
            margin: 0;
        }

        .operator-form-card label {
            color: #3f4d5a;
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .operator-form-card .select2-container,
        #edit-operator-modal .select2-container {
            width: 100% !important;
        }

        select.is-invalid + .select2 .select2-selection {
            border-color: #f05050;
        }

        .operator-form-actions {
            align-items: center;
            border-top: 1px solid #edf0f4;
            display: flex;
            justify-content: flex-end;
            margin-top: .25rem;
            padding-top: 1rem;
        }

        .operator-results-card .card-body {
            padding: 0;
        }

        .operator-results-table {
            margin-bottom: 0;
            min-width: 900px;
        }

        .operator-results-table thead th {
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

        .operator-results-table td {
            border-color: #edf0f4;
            padding: .75rem;
            vertical-align: middle;
        }

        #operators-datatable_wrapper {
            min-width: 900px;
            padding: 1rem 1.25rem;
        }

        #operators-datatable_wrapper .dataTables_filter input,
        #operators-datatable_wrapper .dataTables_length select {
            border: 1px solid #d9e0e7;
            border-radius: .35rem;
            padding: .35rem .55rem;
        }

        #operators-datatable_wrapper .dataTables_info,
        #operators-datatable_wrapper .dataTables_paginate {
            padding-top: 1rem;
        }

        #operators-datatable_processing {
            background: #fff;
            border: 0;
            border-radius: .5rem;
            box-shadow: 0 6px 20px rgba(30, 45, 65, .14);
            color: #293846;
            padding: .8rem 1rem;
        }

        .operator-identity {
            align-items: center;
            display: flex;
            min-width: 220px;
        }

        .operator-avatar {
            align-items: center;
            background: #e8f1fc;
            border-radius: 50%;
            color: #4a89dc;
            display: inline-flex;
            flex: 0 0 38px;
            font-size: .8rem;
            font-weight: 700;
            height: 38px;
            justify-content: center;
            margin-right: .7rem;
            text-transform: uppercase;
        }

        .operator-name,
        .operator-main-value {
            color: #293846;
            font-weight: 600;
        }

        .operator-meta {
            color: #87919c;
            display: block;
            font-size: .77rem;
            margin-top: .15rem;
        }

        .operator-status {
            border-radius: 1rem;
            display: inline-flex;
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .6rem;
            white-space: nowrap;
        }

        .operator-status.is-active {
            background: #e8f7ed;
            color: #228b43;
        }

        .operator-status.is-inactive {
            background: #f1f3f5;
            color: #6c757d;
        }

        @media (max-width: 575.98px) {
            .operator-card-header,
            .operator-form-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .operator-card-header .btn,
            .operator-form-actions .btn {
                margin-left: 0 !important;
                margin-top: .5rem;
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    @include('layouts.topnavbar')
    @include('layouts.menu')
    @include('layouts.setting')

    <section class="section-container">
        <div class="content-wrapper">
            @include('layouts.content-heading', [
                'head' => 'Utilisateurs',
                'content' => '<a class="text-decoration-none" href="">Administrateurs</a>',
                'localize' => 'dashboard.WELCOME'
            ])

            <div class="container-fluid">
                <div class="card operator-form-card mb-4">
                    <div class="operator-card-header">
                        <div>
                            <div class="operator-card-title">Ajouter un administrateur</div>
                            <p class="operator-card-subtitle">Le nouveau compte opérateur sera automatiquement activé et rattaché à un prestataire.</p>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="operator-form" novalidate>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nom">Nom <span class="text-danger">*</span></label>
                                    <input class="form-control" id="nom" name="nom" type="text" maxlength="255" autocomplete="family-name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="prenoms">Prénoms <span class="text-danger">*</span></label>
                                    <input class="form-control" id="prenoms" name="prenoms" type="text" maxlength="255" autocomplete="given-name" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="login">Login <span class="text-danger">*</span></label>
                                    <input class="form-control" id="login" name="login" type="text" maxlength="255" autocomplete="username" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="roles_id">Rôle <span class="text-danger">*</span></label>
                                    <select class="form-control js-operator-select" id="roles_id" name="roles_id" required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contact">Contact <span class="text-danger">*</span></label>
                                    <input class="form-control" id="contact" name="contact" type="text" maxlength="255" autocomplete="tel" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="prestataires_id">Prestataire <span class="text-danger">*</span></label>
                                    <select class="form-control js-operator-select" id="prestataires_id" name="prestataires_id" required>
                                        <option value="">Sélectionner un prestataire</option>
                                        @foreach($prestataires as $prestataire)
                                            <option value="{{ $prestataire->id }}">
                                                {{ $prestataire->codePrestataire ? '['.$prestataire->codePrestataire.'] ' : '' }}{{ $prestataire->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="password">Mot de passe <span class="text-danger">*</span></label>
                                    <input class="form-control" id="password" name="password" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                                    <small class="form-text text-muted">8 caractères minimum.</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="password_confirmation">Confirmer <span class="text-danger">*</span></label>
                                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                                </div>
                            </div>

                            <div class="operator-form-actions">
                                <button class="btn btn-outline-secondary mr-2" id="reset-form" type="button">Réinitialiser</button>
                                <button class="btn btn-primary" id="submit-operator" type="submit">
                                    <span class="spinner-border spinner-border-sm mr-1 d-none" id="operator-loader" role="status" aria-hidden="true"></span>
                                    <em class="fa fa-save mr-1" id="operator-save-icon"></em>
                                    <span id="operator-submit-text">Enregistrer l'opérateur</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card operator-results-card">
                    <div class="operator-card-header">
                        <div>
                            <div class="operator-card-title" id="operator-result-count">Liste des administrateurs</div>
                            <p class="operator-card-subtitle">Recherchez, triez et gérez les comptes liés aux prestataires.</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover operator-results-table w-100" id="operators-datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th>Administrateur</th>
                                    <th>Login</th>
                                    <th>Contact</th>
                                    <th>Prestataire</th>
                                    <th>Statut</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</div>

<div class="modal fade" id="edit-operator-modal" tabindex="-1" role="dialog" aria-labelledby="edit-operator-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="edit-operator-modal-title">Modifier l'administrateur</h5>
                    <small class="text-muted">Le statut du compte se gère directement depuis le tableau.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-operator-form" novalidate>
                <div class="modal-body">
                    <input id="edit-operator-id" type="hidden" value="">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="edit-nom">Nom <span class="text-danger">*</span></label>
                            <input class="form-control" id="edit-nom" name="nom" type="text" maxlength="255" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-prenoms">Prénoms <span class="text-danger">*</span></label>
                            <input class="form-control" id="edit-prenoms" name="prenoms" type="text" maxlength="255" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="edit-login">Login <span class="text-danger">*</span></label>
                            <input class="form-control" id="edit-login" name="login" type="text" maxlength="255" autocomplete="username" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="edit-roles-id">Rôle <span class="text-danger">*</span></label>
                            <select class="form-control js-operator-select" id="edit-roles-id" name="roles_id" required>
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="edit-contact">Contact <span class="text-danger">*</span></label>
                            <input class="form-control" id="edit-contact" name="contact" type="text" maxlength="255" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="edit-prestataires-id">Prestataire <span class="text-danger">*</span></label>
                            <select class="form-control js-operator-select" id="edit-prestataires-id" name="prestataires_id" required>
                                <option value="">Sélectionner un prestataire</option>
                                @foreach($prestataires as $prestataire)
                                    <option value="{{ $prestataire->id }}">
                                        {{ $prestataire->codePrestataire ? '['.$prestataire->codePrestataire.'] ' : '' }}{{ $prestataire->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="edit-password">Mot de passe</label>
                            <input class="form-control" id="edit-password" name="password" type="password" minlength="8" maxlength="255" autocomplete="new-password">
                            <small class="form-text text-muted">Laissez vide pour le conserver.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="edit-password-confirmation">Confirmation</label>
                            <input class="form-control" id="edit-password-confirmation" name="password_confirmation" type="password" minlength="8" maxlength="255" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="update-operator">
                        <em class="fa fa-save mr-1"></em>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reset-password-modal" tabindex="-1" role="dialog" aria-labelledby="reset-password-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="reset-password-modal-title">Réinitialiser le mot de passe</h5>
                    <small class="text-muted" id="reset-password-operator-name"></small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reset-password-form" novalidate>
                <div class="modal-body">
                    <input id="reset-password-operator-id" type="hidden" value="">

                    <div class="form-group">
                        <label for="reset-password">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input class="form-control" id="reset-password" name="password" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                        <small class="form-text text-muted">8 caractères minimum.</small>
                    </div>

                    <div class="form-group">
                        <label for="reset-password-confirmation">Confirmation <span class="text-danger">*</span></label>
                        <input class="form-control" id="reset-password-confirmation" name="password_confirmation" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="reset-notifier-sms" name="notifier_sms" type="checkbox" value="1" checked>
                        <label class="custom-control-label" for="reset-notifier-sms">Notifier l'administrateur par SMS</label>
                    </div>
                    <small class="form-text text-muted">Le SMS contiendra son login et le nouveau mot de passe.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="submit-reset-password">
                        <span class="spinner-border spinner-border-sm mr-1 d-none" id="reset-password-loader" role="status" aria-hidden="true"></span>
                        <em class="fa fa-key mr-1" id="reset-password-icon"></em>
                        <span id="reset-password-submit-text">Réinitialiser</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.js')
@include('layouts.datatablesjs')

<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';

        const routes = {
            donnees: @json(route('comptes.donnees')),
            ajouter: @json(route('comptes.ajouter')),
            recuperer: @json(route('comptes.recuperer', ['id' => '__ID__'])),
            modifier: @json(route('comptes.modifier', ['id' => '__ID__'])),
            motDePasse: @json(route('comptes.mot-de-passe', ['id' => '__ID__'])),
            statut: @json(route('comptes.statut', ['id' => '__ID__'])),
            supprimer: @json(route('comptes.supprimer', ['id' => '__ID__']))
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#prestataires_id, #roles_id').select2({
            width: '100%',
            placeholder: function () {
                return $(this).find('option:first').text();
            },
            allowClear: true
        });

        $('#edit-prestataires-id, #edit-roles-id').select2({
            width: '100%',
            placeholder: function () {
                return $(this).find('option:first').text();
            },
            allowClear: true,
            dropdownParent: $('#edit-operator-modal')
        });

        function echapperHtml(valeur) {
            return $('<div>').text(valeur == null ? '' : valeur).html();
        }

        function urlPour(modele, id) {
            return modele.replace('__ID__', encodeURIComponent(id));
        }

        function effacerErreurs($formulaire) {
            $formulaire.find('.is-invalid').removeClass('is-invalid');
            $formulaire.find('.invalid-feedback.js-server-error').remove();
        }

        function afficherErreurs(requete, $formulaire) {
            effacerErreurs($formulaire);
            const erreurs = requete.responseJSON && requete.responseJSON.errors
                ? requete.responseJSON.errors
                : {};
            const messages = [];

            Object.keys(erreurs).forEach(function (champ) {
                const $champ = $formulaire.find('[name="' + champ + '"]');
                const message = Array.isArray(erreurs[champ]) ? erreurs[champ][0] : erreurs[champ];
                messages.push(message);
                $champ.addClass('is-invalid');
                const $cible = $champ.hasClass('select2-hidden-accessible')
                    ? $champ.next('.select2')
                    : $champ;
                $('<div class="invalid-feedback js-server-error d-block"></div>').text(message).insertAfter($cible);
            });

            Swal.fire({
                icon: 'error',
                title: 'Enregistrement impossible',
                text: messages.join(' ') || (requete.responseJSON && requete.responseJSON.message) || 'Une erreur est survenue.'
            });
        }

        function reinitialiserCreation() {
            const formulaire = document.getElementById('operator-form');
            if (! formulaire) {
                return;
            }

            formulaire.reset();
            effacerErreurs($('#operator-form'));
            $('#prestataires_id, #roles_id').val('').trigger('change');
        }

        function ouvrirModification(operateur) {
            const $formulaire = $('#edit-operator-form');
            effacerErreurs($formulaire);
            $('#edit-operator-id').val(operateur.id);
            $('#edit-nom').val(operateur.nom);
            $('#edit-prenoms').val(operateur.prenoms);
            $('#edit-login').val(operateur.login);
            $('#edit-contact').val(operateur.contact);
            $('#edit-roles-id').val(operateur.roles_id || '').trigger('change');
            $('#edit-prestataires-id').val(operateur.prestataires_id || '').trigger('change');
            $('#edit-password, #edit-password-confirmation').val('');
            $('#edit-operator-modal').modal('show');
        }

        const tableau = $('#operators-datatable').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            searchDelay: 350,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']],
            autoWidth: false,
            ajax: {
                url: routes.donnees,
                type: 'GET',
                error: function (requete) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Chargement impossible',
                        text: (requete.responseJSON && requete.responseJSON.message) || "La liste des administrateurs n'a pas pu être chargée."
                    });
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center text-muted',
                    render: function (data, type, row, meta) {
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                {
                    data: 'nom',
                    render: function (data, type, row) {
                        const nomComplet = ((row.nom || '') + ' ' + (row.prenoms || '')).trim();
                        if (type !== 'display') {
                            return nomComplet;
                        }
                        const initiales = ((row.nom || '').trim().charAt(0) + (row.prenoms || '').trim().charAt(0)) || '?';
                        return '<div class="operator-identity">'
                            + '<span class="operator-avatar">' + echapperHtml(initiales) + '</span>'
                            + '<div><div class="operator-name">' + echapperHtml(nomComplet) + '</div>'
                            + '<span class="operator-meta">' + echapperHtml(row.role || 'Compte opérateur') + '</span></div></div>';
                    }
                },
                {
                    data: 'login',
                    render: function (data, type) {
                        return type === 'display'
                            ? '<span class="operator-main-value">' + echapperHtml(data) + '</span>'
                            : data;
                    }
                },
                { data: 'contact', render: function (data) { return echapperHtml(data); } },
                {
                    data: 'prestataire',
                    render: function (data, type, row) {
                        if (type !== 'display') {
                            return [row.codePrestataire, data].filter(Boolean).join(' ');
                        }
                        const code = row.codePrestataire ? '[' + echapperHtml(row.codePrestataire) + '] ' : '';
                        return data
                            ? '<span class="operator-main-value">' + code + echapperHtml(data) + '</span>'
                            : '<span class="text-muted">Non rattaché</span>';
                    }
                },
                {
                    data: 'actif',
                    render: function (data, type) {
                        if (type !== 'display') {
                            return Number(data) === 1 ? 'Actif' : 'Inactif';
                        }
                        return Number(data) === 1
                            ? '<span class="operator-status is-active"><em class="fa fa-check mr-1"></em>Actif</span>'
                            : '<span class="operator-status is-inactive">Inactif</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-right',
                    render: function (data, type, row) {
                        if (type !== 'display') {
                            return '';
                        }

                        const actions = [];
                        actions.push('<button class="btn btn-sm btn-outline-primary js-edit-operator mr-1" type="button" data-id="' + row.id + '" title="Modifier"><em class="fa fa-pen"></em></button>');
                        actions.push('<button class="btn btn-sm btn-outline-info js-reset-password mr-1" type="button" data-id="' + row.id + '" data-name="' + echapperHtml(((row.nom || '') + ' ' + (row.prenoms || '')).trim()) + '" title="Réinitialiser le mot de passe"><em class="fa fa-key"></em></button>');
                        if (Number(row.actif) === 1) {
                            actions.push('<button class="btn btn-sm btn-outline-warning js-toggle-operator mr-1" type="button" data-id="' + row.id + '" data-active="1" title="Désactiver"><em class="fa fa-ban"></em></button>');
                        } else {
                            actions.push('<button class="btn btn-sm btn-outline-success js-toggle-operator mr-1" type="button" data-id="' + row.id + '" data-active="0" title="Activer"><em class="fa fa-check"></em></button>');
                        }
                        actions.push('<button class="btn btn-sm btn-outline-danger js-delete-operator" type="button" data-id="' + row.id + '" title="Supprimer"><em class="fa fa-trash"></em></button>');

                        return actions.join('');
                    }
                }
            ],
            language: {
                thousands: ' ',
                processing: 'Chargement des administrateurs...',
                search: 'Rechercher dans les résultats :',
                lengthMenu: 'Afficher _MENU_ administrateurs',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ administrateurs',
                infoEmpty: 'Aucun administrateur à afficher',
                infoFiltered: '(filtrés parmi _MAX_ administrateurs)',
                emptyTable: 'Aucun administrateur enregistré.',
                zeroRecords: 'Aucun administrateur ne correspond à cette recherche.',
                paginate: { previous: 'Précédent', next: 'Suivant' }
            },
            drawCallback: function () {
                const informations = this.api().page.info();
                const total = new Intl.NumberFormat('fr-FR').format(informations.recordsDisplay);
                $('#operator-result-count').text(total + ' administrateur' + (informations.recordsDisplay > 1 ? 's' : ''));
            }
        });

        $('#operator-form').on('submit', function (evenement) {
            evenement.preventDefault();
            const $formulaire = $(this);
            const $bouton = $('#submit-operator');
            effacerErreurs($formulaire);
            $bouton.prop('disabled', true);
            $('#operator-loader').removeClass('d-none');
            $('#operator-save-icon').addClass('d-none');
            $('#operator-submit-text').text('Enregistrement en cours...');

            $.ajax({
                url: routes.ajouter,
                type: 'POST',
                data: $formulaire.serialize()
            }).done(function (reponse) {
                Swal.fire({
                    icon: 'success',
                    title: 'Opérateur ajouté',
                    text: reponse.message,
                    showDenyButton: true,
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#0b946f',
                    denyButtonColor: '#0593b2',
                    confirmButtonText: 'Ajouter un autre opérateur',
                    denyButtonText: 'Recharger le tableau'
                }).then(function (resultat) {
                    reinitialiserCreation();
                    if (resultat.isDenied) {
                        tableau.ajax.reload(null, false);
                    } else {
                        $('#nom').trigger('focus');
                    }
                });
            }).fail(function (requete) {
                afficherErreurs(requete, $formulaire);
            }).always(function () {
                $bouton.prop('disabled', false);
                $('#operator-loader').addClass('d-none');
                $('#operator-save-icon').removeClass('d-none');
                $('#operator-submit-text').text("Enregistrer l'opérateur");
            });
        });

        $('#edit-operator-form').on('submit', function (evenement) {
            evenement.preventDefault();
            const $formulaire = $(this);
            const id = $('#edit-operator-id').val();
            const $bouton = $('#update-operator');
            effacerErreurs($formulaire);
            $bouton.prop('disabled', true);

            $.ajax({
                url: urlPour(routes.modifier, id),
                type: 'PUT',
                data: $formulaire.serialize()
            }).done(function (reponse) {
                $('#edit-operator-modal').modal('hide');
                tableau.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Modification effectuée', text: reponse.message });
            }).fail(function (requete) {
                afficherErreurs(requete, $formulaire);
            }).always(function () {
                $bouton.prop('disabled', false);
            });
        });

        $(document).on('click', '.js-edit-operator', function () {
            const id = $(this).data('id');
            $.get(urlPour(routes.recuperer, id))
                .done(function (reponse) {
                    ouvrirModification(reponse.data);
                })
                .fail(function (requete) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Chargement impossible',
                        text: (requete.responseJSON && requete.responseJSON.message) || "L'administrateur n'a pas pu être chargé."
                    });
                });
        });

        $(document).on('click', '.js-reset-password', function () {
            const formulaire = document.getElementById('reset-password-form');
            formulaire.reset();
            effacerErreurs($('#reset-password-form'));
            $('#reset-password-operator-id').val($(this).data('id'));
            $('#reset-password-operator-name').text($(this).data('name'));
            $('#reset-notifier-sms').prop('checked', true);
            $('#reset-password-modal').modal('show');
        });

        $('#reset-password-modal').on('shown.bs.modal', function () {
            $('#reset-password').trigger('focus');
        });

        $('#reset-password-form').on('submit', function (evenement) {
            evenement.preventDefault();

            const $formulaire = $(this);
            const id = $('#reset-password-operator-id').val();
            const $bouton = $('#submit-reset-password');
            effacerErreurs($formulaire);
            $bouton.prop('disabled', true);
            $('#reset-password-loader').removeClass('d-none');
            $('#reset-password-icon').addClass('d-none');
            $('#reset-password-submit-text').text('Réinitialisation en cours...');

            $.ajax({
                url: urlPour(routes.motDePasse, id),
                type: 'PATCH',
                data: $formulaire.serialize()
            }).done(function (reponse) {
                $('#reset-password-modal').modal('hide');
                Swal.fire({
                    icon: reponse.sms_envoye === false ? 'warning' : 'success',
                    title: reponse.sms_envoye === false ? 'Mot de passe modifié, SMS non envoyé' : 'Mot de passe réinitialisé',
                    text: reponse.message
                });
            }).fail(function (requete) {
                afficherErreurs(requete, $formulaire);
            }).always(function () {
                $bouton.prop('disabled', false);
                $('#reset-password-loader').addClass('d-none');
                $('#reset-password-icon').removeClass('d-none');
                $('#reset-password-submit-text').text('Réinitialiser');
            });
        });

        $(document).on('click', '.js-toggle-operator', function () {
            const $bouton = $(this);
            const id = $bouton.data('id');
            const actif = Number($bouton.data('active')) === 1;
            const ligne = tableau.row($bouton.closest('tr')).data() || {};
            const nom = ((ligne.nom || '') + ' ' + (ligne.prenoms || '')).trim();

            Swal.fire({
                icon: 'question',
                title: actif ? 'Désactiver ce compte ?' : 'Activer ce compte ?',
                text: 'Voulez-vous ' + (actif ? 'désactiver' : 'activer') + ' le compte de ' + nom + ' ?',
                showCancelButton: true,
                confirmButtonColor: actif ? '#f0ad4e' : '#27c24c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: actif ? 'Oui, désactiver' : 'Oui, activer',
                cancelButtonText: 'Annuler'
            }).then(function (resultat) {
                if (! resultat.isConfirmed) {
                    return;
                }

                $.ajax({ url: urlPour(routes.statut, id), type: 'PATCH' })
                    .done(function (reponse) {
                        tableau.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: reponse.actif ? 'Compte activé' : 'Compte désactivé',
                            text: reponse.message
                        });
                    })
                    .fail(function (requete) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Changement impossible',
                            text: (requete.responseJSON && requete.responseJSON.message) || "Le statut du compte n'a pas pu être modifié."
                        });
                    });
            });
        });

        $(document).on('click', '.js-delete-operator', function () {
            const $bouton = $(this);
            const id = $bouton.data('id');
            const ligne = tableau.row($bouton.closest('tr')).data() || {};
            const nom = ((ligne.nom || '') + ' ' + (ligne.prenoms || '')).trim();

            Swal.fire({
                icon: 'warning',
                title: "Supprimer l'administrateur ?",
                text: nom + ' ne pourra plus se connecter à l’application restaurant.',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then(function (resultat) {
                if (! resultat.isConfirmed) {
                    return;
                }

                $.ajax({ url: urlPour(routes.supprimer, id), type: 'DELETE' })
                    .done(function (reponse) {
                        tableau.ajax.reload(null, false);
                        Swal.fire({ icon: 'success', title: 'Suppression effectuée', text: reponse.message });
                    })
                    .fail(function (requete) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Suppression impossible',
                            text: (requete.responseJSON && requete.responseJSON.message) || "L'administrateur n'a pas pu être supprimé."
                        });
                    });
            });
        });

        $('#reset-form').on('click', reinitialiserCreation);

        $('#edit-operator-modal').on('hidden.bs.modal', function () {
            const formulaire = document.getElementById('edit-operator-form');
            if (formulaire) {
                formulaire.reset();
                effacerErreurs($('#edit-operator-form'));
                $('#edit-prestataires-id, #edit-roles-id').val('').trigger('change');
            }
        });

        $('#reset-password-modal').on('hidden.bs.modal', function () {
            const formulaire = document.getElementById('reset-password-form');
            formulaire.reset();
            effacerErreurs($('#reset-password-form'));
            $('#reset-password-operator-id').val('');
            $('#reset-password-operator-name').text('');
        });
    });
</script>
</body>
</html>
