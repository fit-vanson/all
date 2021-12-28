@extends('layouts/contentLayoutMaster')

@section('title', 'Wallpapers List')

@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">



  <link rel="stylesheet" href="{{ asset(('vendors/css/animate/animate.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/sweetalert2.min.css')) }}">

  <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/file-uploaders/dropzone.min.css')) }}">

@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-file-uploader.css')) }}">
@endsection


@section('content')
<!-- users list start -->
<section class="app-user-list">
  <div class="row">
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{count($users)}}</h3>
            <span>Total Category</span>
          </div>
          <div class="avatar bg-light-primary p-50">
            <span class="avatar-content">
              <i data-feather="user" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{\App\Models\User::role('Admin')->count()}}</h3>
            <span>Category Real</span>
          </div>
          <div class="avatar bg-light-danger p-50">
            <span class="avatar-content">
              <i data-feather="user-plus" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{\App\Models\User::role('User')->count()}}</h3>
            <span>Category Fake</span>
          </div>
          <div class="avatar bg-light-success p-50">
            <span class="avatar-content">
              <i data-feather="user-check" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- list and filter start -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="list-table table">
        <thead class="table-light">
          <tr>
              <th></th>
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

{{--@include('content.category.modal-add-category')--}}
{{--@include('content.category.modal-edit-category')--}}
@include('content.wallpaper.modal-wallpaper')
  <!-- list and filter end -->
</section>
<!-- users list ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
  <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>

  <script src="{{ asset(('vendors/js/file-uploaders/dropzone.min.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
{{--  <script src="{{ asset(('js/scripts/pages/app-user-list.js')) }}"></script>--}}
  <script>
      $(document).ready(function() {

          $('#avatar_thumbnail').click(function(){
              $('#image_thumbnail').click();
          });
          $('#avatar_detail').click(function(){
              $('#image_detail').click();
          });
          $('#avatar_download').click(function(){
              $('#image_download').click();
          });
      });
      function changeImg_thumbnail(input){
          if(input.files && input.files[0]){
              var reader = new FileReader();
              reader.onload = function(e){
                  $('#avatar_thumbnail').attr('src',e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
      function changeImg_detail(input){
          if(input.files && input.files[0]){
              var reader = new FileReader();
              reader.onload = function(e){
                  $('#avatar_detail').attr('src',e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
      function changeImg_download(input){
          if(input.files && input.files[0]){
              var reader = new FileReader();
              reader.onload = function(e){
                  $('#avatar_download').attr('src',e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
      $('#select_category').select2();
      $('#select_category_edit').select2();
      Dropzone.autoDiscover = false;

      $(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          ('use strict');
          var url = window.location.href;
          var hash = url.substring(url.indexOf('?')+1);
          var dtTable = $('.list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('wallpaper.getIndex')}}?"+hash,
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: 'id' },
                  { data: 'thumbnail_image',className: "text-center" },
                  { data: 'name' },
                  { data: 'view_count'},
                  { data: 'like_count'},
                  { data: 'category'},
                  { data: 'Actions' }
              ],
              columnDefs: [
                  {
                      // For Checkboxes
                      targets: 0,
                      visible: false,
                      orderable: false,
                      responsivePriority: 3,
                      render: function (data, type, full, meta) {
                          console.log(full)
                          return (
                              '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="'+[full.id]+'" name="id[]" id="checkbox' +
                              data +
                              '" /><label class="form-check-label" for="checkbox' +
                              data +
                              '"></label></div>'
                          );
                      },
                      checkboxes: {
                          selectAllRender:
                              '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
                      }
                  },
                  {
                      targets: 1,
                      render: function (data, type, full, meta) {
                          var $output ='<img src="{{asset('storage/wallpapers/thumbnail')}}/'+data+'" alt="wallpaper" height="100px">';
                      return $output;
                      }
                  },
                  {
                      targets: 2,
                      render: function (data, type, full, meta) {
                          var $output ='<span class="fw-bolder">'+data+'</span>';
                          return $output;
                      }
                  },
                  {
                      targets: 5,
                      orderable: false,
                      render: function (data, type, full, meta) {
                          var categories = full['category'],
                              $output = '';
                          $.each(categories, function(i, item) {
                              var stateNum = Math.floor(Math.random() * 6) + 1;
                              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                              var $state = states[stateNum];
                              $output += '<span style="margin-top: 5px;" class="badge rounded-pill badge-light-'+$state+'">'+item+'</span></br>';
                              return i<2;
                          });
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
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editWallpaper">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteWallpaper">' +
                              feather.icons['trash'].toSvg({ class: 'font-medium-2 text-danger' }) +
                              '</a>'
                          );
                      }
                  }
              ],
              order: [2, 'asc'],
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
                      className: 'add-new btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#WallpaperModal'
                      },
                      init: function (api, node, config) {
                          $(node).removeClass('btn-secondary');
                      }
                  }
              ],
          });
          $(document).on('click','.deleteWallpaper', function (data){
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
                          url: "wallpaper/" + id +"/delete",
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
          $(document).on('click','.editWallpaper', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "wallpaper/" + id + "/edit",
                  success: function (data) {
                      $('#EditWallpaperModal').modal('show');
                      $('#id_edit').val(data.id);
                      $('#wallpaper_name').val(data.name);
                      $('#wallpaper_viewCount').val(data.view_count);
                      $('#wallpaper_likeCount').val(data.like_count);

                      if(data.feature == 1){
                          $('#feature').prop('checked', true);
                      }else {
                          $('#feature').prop('checked', false);
                      }
                      $('#avatar_thumbnail').attr('src','{{asset('storage/wallpapers/thumbnail')}}/'+data.thumbnail_image);
                      $('#avatar_detail').attr('src','{{asset('storage/wallpapers/detail')}}/'+data.image);
                      $('#avatar_download').attr('src','{{asset('storage/wallpapers/download')}}/'+data.origin_image);
                      var id_cate =[];
                      $.each(data.category, function(i, item) {
                          id_cate.push(item.id)
                      });
                      $('#select_category_edit').val(id_cate);
                      $('#select_category_edit').select2();
                  },
                  error: function (data) {
                  }
              });
          });
          var wallpaper = $('#wallpaperForm');
          var EditWallpaper = $('#editWallpaperForm');
          EditWallpaper.on('submit', function (e) {
              e.preventDefault();
              var formData = new FormData($("#editWallpaperForm")[0]);
                  $.ajax({
                      data: formData,
                      url: '{{route('wallpaper.update')}}',
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
                              $('#editWallpaperForm').trigger("reset");
                              $('#EditWallpaperModal').modal('hide');
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
          wallpaper.dropzone(
          {
              maxFilesize: 20,
              parallelUploads: 20,
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
      });


  </script>

@endsection
