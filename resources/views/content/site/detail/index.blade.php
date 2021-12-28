@extends('layouts/contentLayoutMaster')

@section('title', 'Tabs')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(('vendors/css/forms/select/select2.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/sweetalert2.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <section id="nav-filled">
        <div class="row match-height">
            <!-- Justified Tabs starts -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{$site->site_name}}</h4>
                        <div class="avatar"><img src="{{$site->logo}}" height="80" width="80"/></div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                            <li class="nav-item">
                                <a
                                    class="nav-link active"
                                    id="category-tab-justified"
                                    data-bs-toggle="tab"
                                    href="#category-just"
                                    role="tab"
                                    aria-controls="category-just"
                                    aria-selected="true"
                                >Category</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    id="wallpaper-tab-justified"
                                    data-bs-toggle="tab"
                                    href="#wallpaper-just"
                                    role="tab"
                                    aria-controls="wallpaper-just"
                                    aria-selected="true"
                                >Wallpaper</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    id="block-ips-tab-justified"
                                    data-bs-toggle="tab"
                                    href="#block-ips-just"
                                    role="tab"
                                    aria-controls="block-ips-just"
                                    aria-selected="true"
                                >Block Ips</a
                                >
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content pt-1">
                            <div class="tab-pane active" id="category-just" role="tabpanel" aria-labelledby="category-tab-justified">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
                                        <table class="list-table table">
                                            <thead class="table-light">
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>View Count</th>
                                                <th>Real</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="wallpaper-just" role="tabpanel" aria-labelledby="wallpaper-tab-justified">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
                                        <table class="list-table-wallpaper table">
                                            <thead class="table-light">
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>View Count</th>
                                                <th>Like Count</th>
                                                <th>Category</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="block-ips-just" role="tabpanel" aria-labelledby="block-ips-tab-justified">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
                                        <table class="list-table-block-ips table">
                                            <thead class="table-light">
                                            <tr>
                                                <th>Ip Address </th>
                                                <th>Created at</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Justified Tabs ends -->
        </div>
        @include('content.site.detail.modal_edit_category')
        @include('content.site.detail.modal_add_site_block_ips')
    </section>


@endsection

@section('page-script')
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
<script>
    $('#select_category').select2();
    $('#block_ips_site').select2();
    $(document).ready(function() {
        $('#avatar').click(function(){
            $('#image').click();
        });
        $('#avatar_edit').click(function(){
            $('#image_edit').click();
        });
    });
    function changeImg(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#avatar').attr('src',e.target.result);
                $('#avatar_edit').attr('src',e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        ('use strict');
        var url = window.location.pathname;
        var  EditCategoryForm = $('#EditCategoryForm');
        var  addBlockIpsSiteForm = $('#addBlockIpsSiteForm');
        // Users List datatable
        var dtTable = $('.list-table').DataTable({
            processing: true,
            serverSide: true,
            displayLength: 50,
            ajax: {
                {{--url: "{{route('site.detail.getIndex')}}"+"kpopwallpapers.net",--}}
                {{--url: "{{asset(url1.'/getIndex')}}",--}}
                url: url +"/category",
                type: "post"
            },
            columns: [
                // columns according to JSON
                { data: 'image' },
                { data: 'category_name' },
                { data: 'view_count' },
                { data: 'checked_ip' },
                { data: 'action' }
            ],
            columnDefs: [
                {
                    targets: 0,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {
                        var $image = full['image']
                        var $output ='<img src="'+ $image + '" alt="Avatar" height="100px">';
                        return $output;
                    }
                },

                {
                    // User Role
                    targets: 3,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $assignedTo = full.checked_ip,
                            $output = '';
                        var realBadgeObj = {
                            0:'<span class="badge rounded-pill badge-light-danger">FAKE</span>',
                            1:'<span class="badge rounded-pill badge-light-success">REAL</span>',
                        };
                        $output = realBadgeObj[full.checked_ip];
                        return $output
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editSiteCategory">' +
                            feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                            '</a>'
                        );
                    }
                }
            ],
            order: [0, 'asc'],
            // dom:
            //     '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
            //     '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
            //     '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
            //     '>t' +
            //     '<"d-flex justify-content-between mx-2 row mb-1"' +
            //     '<"col-sm-12 col-md-6"i>' +
            //     '<"col-sm-12 col-md-6"p>' +
            //     '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'Search',
                searchPlaceholder: 'Search ...'
            },
            // Buttons with Dropdown
            // buttons: [
            //     {
            //         text: 'Add New',
            //         className: 'addNewCategorySite btn btn-primary',
            //         attr: {
            //             'data-bs-toggle': 'modal',
            //             'data-bs-target': '#AddSiteCategoryModal',
            //         },
            //         init: function (api, node, config) {
            //             $(node).removeClass('btn-secondary');
            //         }
            //     }
            // ],

        });
        var dtTableWallpaper = $('.list-table-wallpaper').DataTable({
            processing: true,
            serverSide: true,
            displayLength: 50,
            ajax: {
                {{--url: "{{route('site.detail.getIndex')}}"+"kpopwallpapers.net",--}}
                    {{--url: "{{asset(url1.'/getIndex')}}",--}}
                url: url +"/wallpaper",
                type: "post"
            },
            columns: [
                // columns according to JSON
                { data: 'image' },
                { data: 'name' },
                { data: 'view_count' },
                { data: 'like_count' },
                { data: 'category' },
                { data: 'action' }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        var $image = full['image']
                        var $output ='<img src="{{asset('storage/wallpapers/thumbnail')}}/'+$image+'" alt="wallpaper" height="100px">';
                        return $output;
                    }
                },

                // {
                //     // User Role
                //     targets: 3,
                //     orderable: false,
                //     render: function (data, type, full, meta) {
                //         var $assignedTo = full.checked_ip,
                //             $output = '';
                //         var realBadgeObj = {
                //             0:'<span class="badge rounded-pill badge-light-danger">FAKE</span>',
                //             1:'<span class="badge rounded-pill badge-light-success">REAL</span>',
                //         };
                //         $output = realBadgeObj[full.checked_ip];
                //         return $output
                //     }
                // },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editSiteCategory">' +
                            feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                            '</a>'
                        );
                    }
                }
            ],
            order: [0, 'asc'],
            // dom:
            //     '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
            //     '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
            //     '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
            //     '>t' +
            //     '<"d-flex justify-content-between mx-2 row mb-1"' +
            //     '<"col-sm-12 col-md-6"i>' +
            //     '<"col-sm-12 col-md-6"p>' +
            //     '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'Search',
                searchPlaceholder: 'Search ...'
            },
            // Buttons with Dropdown
            // buttons: [
            //     {
            //         text: 'Add New',
            //         className: 'addNewCategorySite btn btn-primary',
            //         attr: {
            //             'data-bs-toggle': 'modal',
            //             'data-bs-target': '#AddSiteCategoryModal',
            //         },
            //         init: function (api, node, config) {
            //             $(node).removeClass('btn-secondary');
            //         }
            //     }
            // ],

        });
        var dtTableBlockIps = $('.list-table-block-ips').DataTable({
            processing: true,
            serverSide: true,
            displayLength: 10,
            ajax: {
                url: url +"/block-ips",
                type: "post"
            },
            columns: [
                // columns according to JSON
                { data: 'ip_address' },
                { data: 'created_at' },
                { data: 'action' }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        var $output ='<span class="fw-bolder">'+data+'</span>';
                        return $output;
                    }
                },
                {
                    targets: 1,
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
                            '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteSiteBlockIp">' +
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
                    text: 'Add New',
                    className: 'add_new_block_ips btn btn-primary',
                    attr: {
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#addSiteBlockIpsModal',
                        'data-id': '1'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],

        });
        EditCategoryForm.on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData($("#EditCategoryForm")[0]);
            if($('#submitButton_ed').val() == 'update'){
                $.ajax({
                    data: formData,
                    url: url +"/update-category",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            toastr['success']('', data.success, {
                                showMethod: 'fadeIn',
                                hideMethod: 'fadeOut',
                                timeOut: 2000,
                            });
                            dtTable.draw();
                            $('#EditCategoryForm').trigger("reset");
                            $('#EditSiteCategoryModal').modal('hide');
                        }
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                toastr['error']('', data.errors[count], {
                                    showMethod: 'fadeIn',
                                    hideMethod: 'fadeOut',
                                    timeOut: 2000,
                                });
                            }
                        }
                    },
                });
            }
        });

        addBlockIpsSiteForm.on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData($("#addBlockIpsSiteForm")[0]);
                $.ajax({
                    data: formData,
                    url: url +"/update-block-ips",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            toastr['success']('', data.success, {
                                showMethod: 'fadeIn',
                                hideMethod: 'fadeOut',
                                timeOut: 2000,
                            });
                            dtTableBlockIps.draw();
                            $('#addBlockIpsSiteForm').trigger("reset");
                            $('#addSiteBlockIpsModal').modal('hide');
                        }
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                toastr['error']('', data.errors[count], {
                                    showMethod: 'fadeIn',
                                    hideMethod: 'fadeOut',
                                    timeOut: 2000,
                                });
                            }
                        }
                    },
                });

        });

        $(document).on('click','.editSiteCategory', function (data){
            var id = $(this).data("id");
            $.ajax({
                type: "get",
                url: url +"/category/" + id + "/edit",
                success: function (data) {
                    $('#EditSiteCategoryModal').modal('show');
                    // $('.exampleModalLabel').html("Edit Category");
                    // $('#submitButton').prop('class','btn btn-success');
                    // $('#submitButton').text('Update');
                    // $('#submitButton').val('update');
                    $('#id').val(data[0].id);
                    $('#category_site_name_edit').val(data[1].category_name);
                    if(data[0].image){
                        $('#avatar_edit').attr('src',data[0].image);
                    }else {
                        $('#avatar_edit').attr('src',data[1].image);
                    }
                },
                error: function (data) {
                }
            });
        });
        $(document).on('click','.add_new_block_ips', function (data){
            $.ajax({
                type: "get",
                url: url +"/block-ips/edit",
                success: function (data) {
                    console.log(data)

                    $('#id_site').val(data.id);

                    var id_block_ip =[];
                    $.each(data.block_ips, function(i, item) {
                        id_block_ip.push(item.id)
                    });
                    $('#block_ips_site').val(id_block_ip);
                    $('#block_ips_site').select2();
                },
                error: function (data) {
                }
            });
        });
        $(document).on('click','.deleteSiteBlockIp', function (data){
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
                        url: url +"/block-ips/" + id + "/delete",
                        success: function (data) {
                            console.log(data)
                            if(data.success){
                                dtTableBlockIps.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Your file has been deleted.',
                                    timer: 1000,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                            }if(data.error){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deleted!',
                                    text: 'Không thể xoá defaultCategory ',
                                    timer: 1000,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                            }
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
