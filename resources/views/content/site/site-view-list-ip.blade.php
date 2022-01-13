@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - List IP')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
        @include('content.site.site-info')
        <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link " href="{{asset('admin/site/view/'.$site->site_name)}}">
                            <i data-feather="folder" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Categories</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link "  href="{{asset('admin/site/view/'.$site->site_name.'/block-ips')}}">
                            <i data-feather="lock" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Block Ips</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{asset('admin/site/view/'.$site->site_name.'/home')}}">
                            <i data-feather="home" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Web Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{asset('admin/site/view/'.$site->site_name.'/policy')}}">
                            <i data-feather="file-text" class="font-medium-3 me-50"></i><span class="fw-bold">Policy</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{asset('admin/site/view/'.$site->site_name.'/feature-images')}}">
                            <i data-feather="image" class="font-medium-3 me-50"></i><span class="fw-bold">Feature Images</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"  href="{{asset('admin/site/view/'.$site->site_name.'/load-feature')}}">
                            <i data-feather="loader" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Load Feature</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"  href="{{asset('admin/site/view/'.$site->site_name.'/list-ip')}}">
                            <i data-feather="list" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">List IP</span>
                        </a>
                    </li>
                </ul>
                <!--/ User Pills -->

                <!-- Categories table -->
                <div class="card">
                    <h4 class="card-header">Site List Block Ips</h4>
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-site-list-ip table">
                            <thead class="table-light">
                            <tr>
                                <th>Ip Address </th>
                                <th>Updated at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /Categories table -->
            </div>
            <!--/ User Content -->
        </div>
    </section>
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{asset('js/scripts/components/components-navs.js')}}"></script>
    <script src="{{ asset(('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/responsive.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ('use strict');
            var url = window.location.pathname;

            var dtTable = $('.datatable-site-list-ip').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url +"/get",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    { data: 'ip_address' },
                    { data: 'updated_at' },
                    { data: 'action' },
                ],
                columnDefs: [

                    {
                        targets: 0,
                        responsivePriority: 1,
                        render: function (data, type, full, meta) {
                            var $output ='<span class="fw-bolder">'+data+'</span>';
                            return $output;
                        }
                    },
                    {
                        targets: 1,
                        orderable: false,
                        render: function(data, type, row){
                            if(type === "sort" || type === "type"){
                                return data;
                            }
                            return moment(data).format("DD-MM-YYYY HH:mm:ss");
                        }
                    },

                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteIP">' +
                                feather.icons['trash'].toSvg({ class: 'font-medium-2 text-danger' }) +
                                '</a>'
                            );
                        }
                    }
                ],
                order: [0, 'asc'],
                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search ...'
                },
                // Buttons with Dropdown
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        text: feather.icons['share'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                        buttons: [
                            {
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + 'Print',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 0] }
                            },
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 0] }
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 0] }
                            },
                            {
                                extend: 'pdf',
                                text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + 'Pdf',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 0] }
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 0] }
                            }
                        ],
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                            $(node).parent().removeClass('btn-group');
                            setTimeout(function () {
                                $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                            }, 50);
                        }
                    },
                    {
                        text: feather.icons['trash'].toSvg({ class: 'me-50 font-small-4' }) + 'Remove',
                        className: 'deleteMorethan btn btn-danger',
                        attr: {
                            'type' :'submit'
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');

                        }
                    }


                ],

            });

            $(document).on('click','.deleteMorethan', function (data){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            data: $('#checkForm').serialize(),
                            url: url +"/deleteMorethan",
                            type: "post",
                            dataType: 'json',
                            success: function (data) {
                                if(data.success){
                                    dtTable.draw();
                                    toastr['success']('', data.success, {
                                        showMethod: 'fadeIn',
                                        hideMethod: 'fadeOut',
                                        timeOut: 2000,
                                    });
                                }
                                if(data.error){
                                    toastr['error']('', data.error, {
                                        showMethod: 'fadeIn',
                                        hideMethod: 'fadeOut',
                                        timeOut: 2000,
                                    });
                                }


                            },
                        });
                    }
                });
            });
            $(document).on('click','.deleteIP', function (data){
                var id = $(this).data("id");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "get",
                            url: url +'/'+ id + "/delete",
                            success: function (data) {
                                dtTable.draw();
                                toastr['success']('', data.success, {
                                    showMethod: 'fadeIn',
                                    hideMethod: 'fadeOut',
                                    timeOut: 2000,
                                });
                            },
                            error: function (data) {
                            }
                        });
                    }
                });
            });

        });
    </script>

@endsection
