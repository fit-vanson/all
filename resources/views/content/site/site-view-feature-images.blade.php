@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Feature Images')

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
    <link rel="stylesheet" href="{{ asset(('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-file-uploader.css')) }}">
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
                        <a class="nav-link" href="{{asset('admin/site/view/'.$site->site_name.'/block-ips')}}">
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
                        <a class="nav-link active" href="{{asset('admin/site/view/'.$site->site_name.'/feature-images')}}">
                            <i data-feather="image" class="font-medium-3 me-50"></i><span class="fw-bold">Feature Images</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  href="{{asset('admin/site/view/'.$site->site_name.'/load-feature')}}">
                            <i data-feather="loader" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Load Feature</span>
                        </a>
                    </li>
                </ul>
                <!--/ User Pills -->

                <!-- Categories table -->
                <div class="card">
                    <h4 class="card-header">Site List Categories</h4>
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-site-feature-images table">
                            <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Actions</th>
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

    {{--@include('content/_partials/_modals/modal-edit-user')--}}
    @include('content.site.modal-add-feature-images')
{{--    @include('content.site.modal_add_category')--}}
{{--    @include('content.category.modal-category')--}}
    {{--@include('content/_partials/_modals/modal-upgrade-plan')--}}
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

    <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>
    <script src="{{ asset(('vendors/js/file-uploaders/dropzone.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script>
        $(document).ready(function() {
            $('#avatar').click(function(){
                $('#image').click();
            });
        });
        function changeImg(input){
            if(input.files && input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#avatar').attr('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        Dropzone.autoDiscover = false;

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ('use strict');
            var url = window.location.pathname;
            var  addFeatureImagesForm = $('#addFeatureImagesForm');
            var dtTable = $('.datatable-site-feature-images').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    {{--url: "{{route('site.detail.getIndex')}}"+"kpopwallpapers.net",--}}
                        {{--url: "{{asset(url1.'/getIndex')}}",--}}
                    url: url +"/get",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    { data: 'image' },
                    { data: 'name' },
                    { data: 'action' }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        responsivePriority: 4,
                        render: function (data, type, full, meta) {
                            var $output ='<img src="{{asset('storage/feature-images')}}/'+data+'" alt="Avatar" height="200px">';
                            return $output;
                        }
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editSiteFeatureImage">' +
                                feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                                '</a>'+
                                '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteSiteFeatureImage">' +
                                feather.icons['trash'].toSvg({ class: 'font-medium-2 text-danger' }) +
                                '</a>'
                            );
                        }
                    }
                ],
                order: [1, 'asc'],
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
                        text: 'Add New ',
                        className: 'btn btn-primary',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#addFeatureImagesModal',
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],

            });
            addFeatureImagesForm.dropzone(
                {
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    addRemoveLinks: true,
                    timeout: 0,
                    dictRemoveFile: 'Xoá',
                    init: function() {
                        var _this = this; // For the closure
                        this.on('success', function(file, response) {
                            if (response.success) {
                                _this.removeFile(file);
                                dtTable.draw();
                                $(".site_feature_images").load(" .site_feature_images");
                                $('#addFeatureImagesModal').modal('hide');
                                toastr['success'](file.name,response.success, {
                                    showMethod: 'slideDown',
                                    hideMethod: 'slideUp',
                                    timeOut: 1000,
                                });
                            } if (response.errors) {
                                for( var count=0 ; count < response.errors.length; count++){
                                    toastr['error'](file.name,response.errors[count], {
                                        showMethod: 'slideDown',
                                        hideMethod: 'slideUp',
                                        timeOut: 5000,
                                    });
                                }

                            }
                        });
                    },
                });

            $(document).on('click','.deleteSiteFeatureImage', function (data){
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
                            url:  url+'/'+id +"/delete",
                            success: function (data) {
                                dtTable.draw();
                                $(".site_feature_images").load(" .site_feature_images");
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
            $(document).on('click','.editSiteFeatureImage', function (data){
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: url + '/'+id +"/edit",
                    success: function (data) {
                        $('#editFeatureImagesModal').modal('show');
                        $('#id_edit').val(data.id);
                        $('#feature_image_name').val(data.name);
                        $('#site_id').val(data.site_id);
                        $('#site_id').select2();
                        $('#avatar').attr('src','{{asset('storage/feature-images')}}/'+data.image);
                    },
                    error: function (data) {
                    }
                });
            });
            var editFeatureImagesForm = $('#editFeatureImagesForm')

            editFeatureImagesForm.on('submit', function (e) {
                var formData = new FormData($("#editFeatureImagesForm")[0]);
                e.preventDefault();
                        $.ajax({
                            data: formData,
                            url: url + '/update',
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
                                    $(".site_feature_images").load(" .site_feature_images");
                                    $('#editFeatureImagesForm').trigger("reset");
                                    $('#editFeatureImagesModal').modal('hide');
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
        });

    </script>

@endsection
