<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.metas', ['title' => 'Gestion des cartes libres'])
    @include('layouts.css')
    @include('layouts.datatablescss')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .free-card-panel {
            border: 0;
            border-radius: .65rem;
            box-shadow: 0 2px 12px rgba(30, 45, 65, .09);
            overflow: hidden;
        }

        .free-card-header {
            align-items: center;
            background: linear-gradient(135deg, #fff 0%, #f4f7fb 100%);
            border-bottom: 1px solid #e8edf3;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .free-card-title {
            color: #293846;
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: .2rem;
        }

        .free-card-subtitle {
            color: #6c757d;
            font-size: .85rem;
            margin: 0;
        }

        .free-card-table {
            margin-bottom: 0;
            min-width: 980px;
        }

        .free-card-table thead th {
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

        .free-card-table td {
            border-color: #edf0f4;
            padding: .75rem;
            vertical-align: middle;
        }

        #free-cards-table_wrapper {
            min-width: 980px;
            padding: 1rem 1.25rem;
        }

        .free-card-name {
            color: #293846;
            font-weight: 600;
        }

        .free-card-meta {
            color: #87919c;
            display: block;
            font-size: .77rem;
            margin-top: .15rem;
        }

        .free-card-status {
            border-radius: 1rem;
            display: inline-flex;
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .65rem;
            white-space: nowrap;
        }

        .free-card-status.is-active {
            background: #e8f7ed;
            color: #228b43;
        }

        .free-card-status.is-inactive {
            background: #f1f3f5;
            color: #6c757d;
        }

        @media (max-width: 575.98px) {
            .free-card-header {
                align-items: stretch;
                flex-direction: column;
            }

            .free-card-header .btn {
                margin-top: .75rem;
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
                'content' => '<a class="text-decoration-none" href="'.route('cartes-libres.index').'">Cartes libres</a>',
                'localize' => 'dashboard.WELCOME'
            ])

            <div class="container-fluid">
                <div class="card free-card-panel">
                    <div class="free-card-header">
                        <div>
                            <div class="free-card-title" id="free-card-result-count">Liste des cartes libres</div>
                            <p class="free-card-subtitle">Créez et gérez les cartes non rattachées à un étudiant.</p>
                        </div>
                        @if($canCreate)
                            <button class="btn btn-primary" id="create-free-card" type="button">
                                <em class="fa fa-plus mr-1"></em>Nouvelle carte libre
                            </button>
                        @endif
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover free-card-table w-100" id="free-cards-table">
                                <thead>
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th>Carte libre</th>
                                    <th>Direction</th>
                                    <th>Capacité</th>
                                    <th>Validité</th>
                                    <th>Comptes liés</th>
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

<div class="modal fade" id="free-card-modal" tabindex="-1" role="dialog" aria-labelledby="free-card-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="free-card-modal-title">Nouvelle carte libre</h5>
                    <small class="text-muted">Les cartes nouvellement créées sont actives immédiatement.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="free-card-form" novalidate>
                <div class="modal-body">
                    <input id="free-card-id" type="hidden" value="">

                    <div class="row">
                        <div class="form-group col-md-7">
                            <label for="free-card-label">Libellé <span class="text-danger">*</span></label>
                            <input class="form-control" id="free-card-label" name="libelle" type="text" maxlength="255" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="free-card-direction">Direction <span class="text-danger">*</span></label>
                            <select class="form-control" id="free-card-direction" name="directions_id" required>
                                <option value="">Sélectionner une direction</option>
                                @foreach($directions as $direction)
                                    <option value="{{ $direction->id }}">
                                        {{ $direction->codeDirection ? '['.$direction->codeDirection.'] ' : '' }}{{ $direction->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="free-card-capacity">Capacité <span class="text-danger">*</span></label>
                            <input class="form-control" id="free-card-capacity" name="capacite" type="number" min="1" max="1000000" value="1" required>
                            <small class="form-text text-muted">Nombre maximal de passages autorisés.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="free-card-start-date">Date de début</label>
                            <input class="form-control" id="free-card-start-date" name="dateDebut" type="date">
                            <small class="form-text text-muted">Facultative.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="free-card-days">Durée en jours</label>
                            <input class="form-control" id="free-card-days" name="nombreJours" type="number" min="1" max="3650">
                            <small class="form-text text-muted">Facultative, 3 650 jours maximum.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="save-free-card">
                        <span class="spinner-border spinner-border-sm mr-1 d-none" id="free-card-loader" role="status" aria-hidden="true"></span>
                        <em class="fa fa-save mr-1" id="free-card-save-icon"></em>
                        <span id="free-card-submit-label">Créer la carte</span>
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
            data: @json(route('cartes-libres.data')),
            store: @json(route('cartes-libres.store')),
            show: @json(route('cartes-libres.show', ['carteLibre' => '__ID__'])),
            update: @json(route('cartes-libres.update', ['carteLibre' => '__ID__'])),
            status: @json(route('cartes-libres.status', ['carteLibre' => '__ID__'])),
            destroy: @json(route('cartes-libres.destroy', ['carteLibre' => '__ID__']))
        };
        const permissions = {
            update: @json($canUpdate),
            activate: @json($canActivate),
            delete: @json($canDelete)
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function routeFor(template, id) {
            return template.replace('__ID__', encodeURIComponent(id));
        }

        function clearErrors($form) {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback.js-server-error').remove();
        }

        function showErrors(request, $form) {
            clearErrors($form);
            const errors = request.responseJSON && request.responseJSON.errors
                ? request.responseJSON.errors
                : {};
            const messages = [];

            Object.keys(errors).forEach(function (field) {
                const $field = $form.find('[name="' + field + '"]');
                const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                messages.push(message);
                $field.addClass('is-invalid');
                $('<div class="invalid-feedback js-server-error d-block"></div>').text(message).insertAfter($field);
            });

            Swal.fire({
                icon: 'error',
                title: 'Enregistrement impossible',
                text: messages.join(' ') || (request.responseJSON && request.responseJSON.message) || 'Une erreur est survenue.'
            });
        }

        function resetForm() {
            const form = document.getElementById('free-card-form');
            form.reset();
            clearErrors($('#free-card-form'));
            $('#free-card-id').val('');
            $('#free-card-capacity').val(1);
        }

        function openCreateModal() {
            resetForm();
            $('#free-card-modal-title').text('Nouvelle carte libre');
            $('#free-card-submit-label').text('Créer la carte');
            $('#free-card-modal').modal('show');
        }

        const table = $('#free-cards-table').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            searchDelay: 350,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']],
            autoWidth: false,
            ajax: {
                url: routes.data,
                type: 'GET',
                error: function (request) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Chargement impossible',
                        text: (request.responseJSON && request.responseJSON.message) || "Les cartes libres n'ont pas pu être chargées."
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
                    data: 'libelle',
                    render: function (data, type, row) {
                        if (type !== 'display') {
                            return data;
                        }

                        return '<span class="free-card-name">' + escapeHtml(data) + '</span>'
                            + '<span class="free-card-meta">Carte n° ' + escapeHtml(row.id) + '</span>';
                    }
                },
                {
                    data: 'direction',
                    render: function (data, type, row) {
                        const value = [row.code_direction ? '[' + row.code_direction + ']' : '', data || ''].filter(Boolean).join(' ');
                        return type === 'display' ? escapeHtml(value || 'Non renseignée') : value;
                    }
                },
                {
                    data: 'capacite',
                    className: 'text-center',
                    render: function (data, type) {
                        if (type !== 'display') {
                            return data;
                        }

                        const value = Number(data || 0).toLocaleString('fr-FR');
                        return '<span class="badge badge-info">' + value + ' passage' + (Number(data) > 1 ? 's' : '') + '</span>';
                    }
                },
                {
                    data: 'date_debut',
                    orderable: false,
                    render: function (data, type, row) {
                        const parts = [];
                        if (row.date_debut_lisible) {
                            parts.push('À partir du ' + row.date_debut_lisible);
                        }
                        if (row.nombre_jours) {
                            parts.push(row.nombre_jours + ' jour' + (Number(row.nombre_jours) > 1 ? 's' : ''));
                        }

                        const value = parts.length ? parts.join(' · ') : 'Sans limite définie';
                        return type === 'display' ? escapeHtml(value) : value;
                    }
                },
                {
                    data: 'comptes_count',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data) {
                        return '<span class="badge badge-light">' + Number(data || 0).toLocaleString('fr-FR') + '</span>';
                    }
                },
                {
                    data: 'actif',
                    render: function (data, type) {
                        if (type !== 'display') {
                            return Number(data) === 1 ? 'Active' : 'Inactive';
                        }

                        return Number(data) === 1
                            ? '<span class="free-card-status is-active"><em class="fa fa-check mr-1"></em>Active</span>'
                            : '<span class="free-card-status is-inactive">Inactive</span>';
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
                        if (permissions.update) {
                            actions.push('<button class="btn btn-sm btn-outline-primary js-edit-free-card mr-1" type="button" data-id="' + row.id + '" title="Modifier"><em class="fa fa-pen"></em></button>');
                        }
                        if (permissions.activate) {
                            const active = Number(row.actif) === 1;
                            actions.push('<button class="btn btn-sm ' + (active ? 'btn-outline-warning' : 'btn-outline-success') + ' js-toggle-free-card mr-1" type="button" data-id="' + row.id + '" data-active="' + (active ? '1' : '0') + '" title="' + (active ? 'Désactiver' : 'Activer') + '"><em class="fa ' + (active ? 'fa-ban' : 'fa-check') + '"></em></button>');
                        }
                        if (permissions.delete) {
                            actions.push('<button class="btn btn-sm btn-outline-danger js-delete-free-card" type="button" data-id="' + row.id + '" title="Supprimer"><em class="fa fa-trash"></em></button>');
                        }

                        return actions.length ? actions.join('') : '<span class="text-muted">—</span>';
                    }
                }
            ],
            language: {
                thousands: ' ',
                processing: 'Chargement des cartes libres...',
                search: 'Rechercher :',
                lengthMenu: 'Afficher _MENU_ cartes',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ cartes',
                infoEmpty: 'Aucune carte libre à afficher',
                infoFiltered: '(filtrées parmi _MAX_ cartes)',
                emptyTable: 'Aucune carte libre enregistrée.',
                zeroRecords: 'Aucune carte libre ne correspond à cette recherche.',
                paginate: { previous: 'Précédent', next: 'Suivant' }
            },
            drawCallback: function () {
                const information = this.api().page.info();
                const total = new Intl.NumberFormat('fr-FR').format(information.recordsDisplay);
                $('#free-card-result-count').text(total + ' carte' + (information.recordsDisplay > 1 ? 's' : '') + ' libre' + (information.recordsDisplay > 1 ? 's' : ''));
            }
        });

        $('#create-free-card').on('click', openCreateModal);

        $('#free-card-modal').on('shown.bs.modal', function () {
            $('#free-card-label').trigger('focus');
        });

        $('#free-card-form').on('submit', function (event) {
            event.preventDefault();
            const $form = $(this);
            const id = $('#free-card-id').val();
            const editing = id !== '';
            const $button = $('#save-free-card');
            clearErrors($form);
            $button.prop('disabled', true);
            $('#free-card-loader').removeClass('d-none');
            $('#free-card-save-icon').addClass('d-none');

            $.ajax({
                url: editing ? routeFor(routes.update, id) : routes.store,
                type: editing ? 'PUT' : 'POST',
                data: $form.serialize()
            }).done(function (response) {
                $('#free-card-modal').modal('hide');
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: editing ? 'Carte modifiée' : 'Carte créée', text: response.message });
            }).fail(function (request) {
                showErrors(request, $form);
            }).always(function () {
                $button.prop('disabled', false);
                $('#free-card-loader').addClass('d-none');
                $('#free-card-save-icon').removeClass('d-none');
            });
        });

        $(document).on('click', '.js-edit-free-card', function () {
            const id = $(this).data('id');
            $.get(routeFor(routes.show, id))
                .done(function (response) {
                    const card = response.data;
                    resetForm();
                    $('#free-card-id').val(card.id);
                    $('#free-card-label').val(card.libelle);
                    $('#free-card-direction').val(card.directions_id);
                    $('#free-card-capacity').val(card.capacite);
                    $('#free-card-start-date').val(card.date_debut || '');
                    $('#free-card-days').val(card.nombre_jours || '');
                    $('#free-card-modal-title').text('Modifier la carte libre');
                    $('#free-card-submit-label').text('Enregistrer les modifications');
                    $('#free-card-modal').modal('show');
                })
                .fail(function (request) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Chargement impossible',
                        text: (request.responseJSON && request.responseJSON.message) || "La carte libre n'a pas pu être chargée."
                    });
                });
        });

        $(document).on('click', '.js-toggle-free-card', function () {
            const $button = $(this);
            const id = $button.data('id');
            const active = Number($button.data('active')) === 1;

            Swal.fire({
                icon: 'question',
                title: active ? 'Désactiver cette carte ?' : 'Activer cette carte ?',
                text: active
                    ? "Les comptes liés ne pourront plus utiliser cette carte jusqu'à sa réactivation."
                    : 'La carte pourra de nouveau être utilisée.',
                showCancelButton: true,
                confirmButtonColor: active ? '#f0ad4e' : '#27c24c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: active ? 'Oui, désactiver' : 'Oui, activer',
                cancelButtonText: 'Annuler'
            }).then(function (result) {
                if (! result.isConfirmed) {
                    return;
                }

                $.ajax({ url: routeFor(routes.status, id), type: 'PATCH' })
                    .done(function (response) {
                        table.ajax.reload(null, false);
                        Swal.fire({ icon: 'success', title: response.actif ? 'Carte activée' : 'Carte désactivée', text: response.message });
                    })
                    .fail(function (request) {
                        Swal.fire({ icon: 'error', title: 'Action impossible', text: (request.responseJSON && request.responseJSON.message) || 'Le statut ne peut pas être modifié.' });
                    });
            });
        });

        $(document).on('click', '.js-delete-free-card', function () {
            const id = $(this).data('id');
            const row = table.row($(this).closest('tr')).data() || {};

            Swal.fire({
                icon: 'warning',
                title: 'Supprimer la carte libre ?',
                text: (row.libelle || 'Cette carte') + " sera supprimée. L'action est refusée si un compte restaurant lui est encore lié.",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then(function (result) {
                if (! result.isConfirmed) {
                    return;
                }

                $.ajax({ url: routeFor(routes.destroy, id), type: 'DELETE' })
                    .done(function (response) {
                        table.ajax.reload(null, false);
                        Swal.fire({ icon: 'success', title: 'Carte supprimée', text: response.message });
                    })
                    .fail(function (request) {
                        Swal.fire({ icon: 'error', title: 'Suppression impossible', text: (request.responseJSON && request.responseJSON.message) || "La carte libre n'a pas pu être supprimée." });
                    });
            });
        });

        $('#free-card-modal').on('hidden.bs.modal', resetForm);
    });
</script>
</body>
</html>
