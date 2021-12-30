@extends('layouts/contentLayoutMaster')

@section('title', 'Home')

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

@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection


@section('content')
<!-- users list start -->
<section class="app-user-list">
  <!-- list and filter start -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="list-table table">
        <thead class="table-light">
          <tr>
              <th style="width: 150px;">Header Image</th>
              <th>Site Name</th>
              <th>Header Title</th>
              <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

@include('content.home.modal-home-site')

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
  <script src="{{ asset(('vendors/js/forms/repeater/jquery.repeater.min.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
{{--  <script src="{{ asset(('js/scripts/pages/app-user-list.js')) }}"></script>--}}
  <script src="{{ asset(('js/scripts/forms/form-repeater.js')) }}"></script>
  <script>
      $(document).ready(function() {
          $('#select_site').select2();

          $('#logo').click(function(){
              $('#header_image').click();
          });
      });
      function changeImg(input){
          if(input.files && input.files[0]){
              var reader = new FileReader();
              reader.onload = function(e){
                  $('#logo').attr('src',e.target.result);
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
          // var newUserSidebar = $('.new-user-modal');
          var  HomeSiteForm = $('#HomeSiteForm');
          // Users List datatable
          var dtTable = $('.list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('home.getIndex')}}",
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: 'logo',className: "text-center" },

                  { data: 'site_name' },
                  { data: 'header_title' },
                  { data: 'action' }
              ],
              columnDefs: [
                  {
                      targets: 0,
                      responsivePriority: 4,
                      render: function (data, type, full, meta) {
                          var $output =
                              '<div class="avatar"><img src="' + data + '" alt="Avatar" height="100" width="100"></div>';
                          return $output;
                      }
                  },
                  {
                      targets: 1,
                      render: function (data, type, full, meta) {
                          var $output ='<span class="fw-bolder">'+data+'</span>';
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
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editHomeSite">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteHomeSite">' +
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
                      text: 'Add New',
                      className: 'add-new-site-home btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#HomeSiteModal'
                      },
                      init: function (api, node, config) {
                          $(node).removeClass('btn-secondary');
                      }
                  }
              ],

          });
          $('.add-new-site-home').on('click',function (){
              $('#HomeSiteForm').trigger("reset");
              $('.homeSiteModalLabel').html("Add New");
              $('#submitButton').prop('class','btn btn-primary ');
              $('#submitButton').text('Create');
              $('#submitButton').val('create');

          });


          if (HomeSiteForm.length) {
              HomeSiteForm.validate({
                  errorClass: 'error',
                  rules: {
                      'header_title': {
                          required: true
                      },
                      'header_content': {
                          required: true
                      },
                      'body_title': {
                          required: true
                      },
                      'body_content': {
                          required: true
                      },
                      'footer_title': {
                          required: true
                      },
                      'footer_content': {
                          required: true
                      }
                  }
              });
              HomeSiteForm.on('submit', function (e) {
                  e.preventDefault();
                  var formData = new FormData($("#HomeSiteForm")[0]);
                  if($('#submitButton').val() == 'create'){
                      $.ajax({
                          data: formData,
                          url: '{{route('home.create')}}',
                          type: "POST",
                          dataType: 'json',
                          processData: false,
                          contentType: false,
                          success: function (data) {
                              if(data.errors){
                                  for( var count=0 ; count <data.errors.length; count++){
                                      toastr['error']('', data.errors[count], {
                                          showMethod: 'fadeIn',
                                          hideMethod: 'fadeOut',
                                          timeOut: 3000,
                                      });
                                  }
                              }
                              if (data.success) {
                                  toastr['success']('', data.success, {
                                      showMethod: 'fadeIn',
                                      hideMethod: 'fadeOut',
                                      timeOut: 2000,
                                  });
                                  dtTable.draw();
                                  $('#HomeSiteForm').trigger("reset");
                                  $('#HomeSiteModal').modal('hide');
                              }
                          },
                      });
                  }
                  if($('#submitButton').val() == 'update'){
                      $.ajax({
                          data: formData,
                          url: '{{route('home.update')}}',
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
                                  $('#apiKeysForm').trigger("reset");
                                  $('#HomeSiteModal').modal('hide');
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
          }



          $(document).on('click','.deleteHomeSite', function (data){
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
                          url: "api-keys/" + id +"/delete",
                          success: function (data) {
                              console.log(data)
                              if(data.success){
                                  dtTable.draw();
                                  toastr['success']('', data.success, {
                                      showMethod: 'fadeIn',
                                      hideMethod: 'fadeOut',
                                      timeOut: 2000,
                                  });
                              }
                          },
                          error: function (data) {
                          }
                      });

                  }
              });
          });
          $(document).on('click','.editHomeSite', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "home/" + id + "/edit",
                  success: function (data) {
                      $('#HomeSiteModal').modal('show');
                      $('.homeSiteModalLabel').html("Edit");
                      $('#submitButton').prop('class','btn btn-success');
                      $('#submitButton').text('Update');
                      $('#submitButton').val('update');
                      $('#id').val(data.id);
                      $('#header_title').val(data.header_title);
                      $('#header_content').val(data.header_content);

                      $('#body_title').val(data.body_title);
                      $('#body_content').val(data.body_content);
                      $('#footer_title').val(data.footer_title);
                      $('#footer_content').val(data.footer_content);
                      $('#logo').attr('src',data.header_image);
                      // $('#select_site').val(data.site_id);
                      // $('#select_site').select2();
                  },
                  error: function (data) {
                  }
              });
          });
      });

  </script>
@endsection
