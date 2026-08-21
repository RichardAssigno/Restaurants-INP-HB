<!-- Datatables-->
<script src="{{asset('assets/datatables.net/js/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/jszip/dist/jszip.js')}}"></script>
<script src="{{asset('assets/pdfmake/build/pdfmake.js')}}"></script>
<script src="{{asset('assets/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/datatables.net-buttons/js/dataTables.buttons.js')}}"></script>
<script src="{{asset('assets/datatables.net-buttons-bs/js/buttons.bootstrap.js')}}"></script>
<script src="{{asset('assets/datatables.net-buttons/js/buttons.colVis.js')}}"></script>
<script src="{{asset('assets/datatables.net-buttons/js/buttons.html5.js')}}"></script>
<script src="{{asset('assets/datatables.net-buttons/js/buttons.print.js')}}"></script>
<script src="{{asset('assets/datatables.net-keytable/js/dataTables.keyTable.js')}}"></script>
<script src="{{asset('assets/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
<script src="{{asset('assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>

<script>
    window.restaurantDataTableDom = "<'row app-datatable-toolbar'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row app-datatable-footer'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

    window.restaurantDataTableExportColumns = function (index, data, node) {
        const libelle = $(node).text().trim().toLocaleLowerCase('fr-FR');
        return libelle !== 'action' && libelle !== 'actions';
    };

    window.restaurantDataTableExportOptions = function () {
        return {
            columns: window.restaurantDataTableExportColumns,
            format: {
                body: function (data, row, column, node) {
                    return $(node).text().replace(/\s+/g, ' ').trim();
                }
            }
        };
    };

    window.restaurantDataTableButtons = function () {
        const titre = function () {
            return document.title || 'Export';
        };

        return [
            {
                extend: 'copyHtml5',
                text: 'Copier',
                className: 'btn btn-outline-secondary',
                title: titre,
                exportOptions: window.restaurantDataTableExportOptions()
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                className: 'btn btn-outline-secondary',
                title: titre,
                bom: true,
                exportOptions: window.restaurantDataTableExportOptions()
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                className: 'btn btn-outline-secondary',
                title: titre,
                exportOptions: window.restaurantDataTableExportOptions()
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                className: 'btn btn-outline-secondary',
                title: titre,
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: window.restaurantDataTableExportOptions()
            },
            {
                extend: 'print',
                text: 'Capturer',
                className: 'btn btn-outline-secondary',
                title: titre,
                exportOptions: window.restaurantDataTableExportOptions()
            }
        ];
    };

    $.extend(true, $.fn.dataTable.defaults, {
        dom: window.restaurantDataTableDom,
        buttons: window.restaurantDataTableButtons(),
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        autoWidth: false,
        responsive: true,
        language: {
            thousands: ' ',
            search: 'Filtrer ici :',
            info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
            infoEmpty: 'Aucune entrée à afficher',
            infoFiltered: '(filtrées parmi _MAX_ entrées)',
            emptyTable: 'Aucune donnée disponible.',
            zeroRecords: 'Aucun résultat ne correspond à cette recherche.',
            paginate: {
                previous: '<em class="fa fa-caret-left"></em>',
                next: '<em class="fa fa-caret-right"></em>'
            }
        }
    });

    $(document).on('init.dt', function (event, settings) {
        const $table = $(settings.nTable);
        const $wrapper = $(settings.nTableWrapper);

        $table.addClass('app-datatable table-hover').removeClass('table-bordered');
        $wrapper.addClass('app-datatable-wrapper');
        $table.closest('.card').addClass('app-datatable-card');
    });
</script>
