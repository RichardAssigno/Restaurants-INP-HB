<!-- Datatables-->
<link rel="stylesheet" href="{{asset('assets/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/datatables.net-keytable-bs/css/keyTable.bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">

<style>
    .app-datatable-card {
        border: 0 !important;
        border-radius: .65rem !important;
        box-shadow: 0 2px 12px rgba(30, 45, 65, .09) !important;
        overflow: hidden;
    }

    .app-datatable-card > .card-header:empty {
        display: none;
    }

    .app-datatable-card > .card-body {
        padding: 0 !important;
    }

    .app-datatable-wrapper {
        min-width: 0 !important;
        padding: 1rem 1.25rem !important;
    }

    table.app-datatable {
        border: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        margin-bottom: 0 !important;
        min-width: 0 !important;
        width: 100% !important;
    }

    table.app-datatable thead th {
        background: #f7f9fb !important;
        border-bottom: 1px solid #e5e9ef !important;
        border-left: 0 !important;
        border-right: 0 !important;
        border-top: 0 !important;
        color: #657383 !important;
        font-size: .73rem;
        font-weight: 600;
        letter-spacing: .035em;
        padding: .8rem .75rem !important;
        text-transform: uppercase;
        vertical-align: middle !important;
    }

    table.app-datatable tbody td {
        background: #fff;
        border-bottom: 1px solid #edf0f4 !important;
        border-left: 0 !important;
        border-right: 0 !important;
        border-top: 0 !important;
        color: #34495e;
        padding: .8rem .75rem !important;
        vertical-align: middle !important;
    }

    table.app-datatable.table-striped tbody tr:nth-of-type(odd),
    table.app-datatable.table-striped tbody tr:nth-of-type(even) {
        background: transparent !important;
    }

    table.app-datatable tbody tr:hover td {
        background: #fbfcfe;
    }

    .app-datatable-toolbar {
        align-items: center;
        border-bottom: 1px solid #edf0f4;
        margin: -1rem -1.25rem 1rem !important;
        padding: 1rem 1.25rem .85rem;
    }

    .app-datatable-toolbar .dt-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
    }

    .app-datatable-toolbar .dt-buttons .btn,
    .app-datatable-toolbar .dt-buttons button {
        background: #f7f9fb !important;
        border: 1px solid #dbe3ec !important;
        border-radius: .25rem !important;
        box-shadow: none !important;
        color: #40546a !important;
        font-size: .8rem;
        margin: 0 !important;
        padding: .48rem .72rem;
    }

    .app-datatable-toolbar .dt-buttons .btn:hover,
    .app-datatable-toolbar .dt-buttons button:hover {
        background: #eaf2fb !important;
        border-color: #b8cce2 !important;
        color: #276eae !important;
    }

    .app-datatable-toolbar .dataTables_filter {
        margin: 0;
        text-align: right;
    }

    .app-datatable-toolbar .dataTables_filter label {
        color: #657383;
        font-size: .82rem;
        margin: 0;
        width: 100%;
    }

    .app-datatable-toolbar .dataTables_filter input {
        border: 1px solid #d9e0e7 !important;
        border-radius: .45rem !important;
        box-shadow: none !important;
        margin-left: .5rem;
        min-height: 38px;
        padding: .4rem .65rem !important;
        width: min(230px, 65%) !important;
    }

    .app-datatable-toolbar .dataTables_filter input:focus {
        border-color: #4a89dc !important;
        box-shadow: 0 0 0 .15rem rgba(74, 137, 220, .12) !important;
    }

    .app-datatable-footer {
        align-items: center;
        margin-top: .35rem !important;
    }

    .app-datatable-footer .dataTables_info,
    .app-datatable-footer .dataTables_paginate {
        color: #7b8794;
        font-size: .82rem;
        padding-top: .85rem !important;
    }

    .app-datatable-footer .page-link {
        border-color: #e0e6ed;
        color: #526a82;
    }

    .app-datatable-footer .page-item.active .page-link {
        background: #4a89dc;
        border-color: #4a89dc;
        color: #fff;
    }

    .app-datatable-footer .page-item.disabled .page-link {
        color: #c8d0d8;
    }

    @media (max-width: 767.98px) {
        .app-datatable-wrapper {
            padding: .85rem !important;
        }

        .app-datatable-toolbar {
            margin: -.85rem -.85rem .85rem !important;
            padding: .85rem;
        }

        .app-datatable-toolbar > div {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .app-datatable-toolbar .dt-buttons {
            justify-content: center;
            margin-bottom: .7rem;
        }

        .app-datatable-toolbar .dataTables_filter,
        .app-datatable-toolbar .dataTables_filter label {
            text-align: left !important;
        }

        .app-datatable-toolbar .dataTables_filter input {
            display: block;
            margin: .35rem 0 0 !important;
            max-width: none;
            width: 100% !important;
        }

        .app-datatable-footer .dataTables_info,
        .app-datatable-footer .dataTables_paginate {
            text-align: center !important;
        }
    }
</style>
