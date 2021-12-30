@extends('layouts/contentLayoutMaster')

@section('title', 'Site List')

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
      <table class="site-list-table table">
        <thead class="table-light">
          <tr>
            <th style="width: 120px;">Logo</th>
            <th>Name</th>
            <th>Category</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <!-- list and filter end -->

    @include('content.site.modal-site')
    @include('content.apikey.modal-api-keys')
    @include('content.category.modal-category')
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
@endsection

@section('page-script')
  {{-- Page js files --}}
{{--  <script src="{{ asset(('js/scripts/pages/app-user-list.js')) }}"></script>--}}
  <script>
      $(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          ('use strict');
          // var newUserSidebar = $('.new-user-modal');
          var  SiteForm = $('#siteForm');
          // Users List datatable
          var dtSiteTable = $('.site-list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('site.getIndex')}}",
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: 'logo',className: "text-center" },
                  { data: 'site_name' },
                  { data: 'category' },
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
                      targets: 2,
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
                              '<a href="/admin/site/'+ full.site_name +'" data-id="'+full.id+'" class="btn btn-sm btn-icon">' +
                              feather.icons['list'].toSvg({ class: 'font-medium-2 text-success' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editSite">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteSite">' +
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
                      text: 'Add New Site',
                      className: 'add-new-site btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#SitesModal'
                      },
                      init: function (api, node, config) {
                          $(node).removeClass('btn-secondary');
                      }
                  }
              ],

          });
          // Form Validation
          if (SiteForm.length) {
              SiteForm.validate({
                  errorClass: 'error',
                  rules: {
                      'site_name': {
                          required: true
                      },
                      'select_category': {
                          required: true
                      }
                  }
              });
              SiteForm.on('submit', function (e) {
                  var isValid = SiteForm.valid();
                  var nameValue = document.getElementById("submitButton").value;
                  var formData = new FormData($("#siteForm")[0]);
                  e.preventDefault();
                  if (isValid) {
                      if (nameValue == "create") {
                          $.ajax({
                              data: formData,
                              url: '{{route('site.create')}}',
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
                                              timeOut: 2000,
                                          });
                                      }
                                  }
                                  if (data.success) {
                                      toastr['success']('', data.success, {
                                          showMethod: 'fadeIn',
                                          hideMethod: 'fadeOut',
                                          timeOut: 2000,
                                      });
                                      dtSiteTable.draw();
                                      $('#siteForm').trigger("reset");
                                      $('#SitesModal').modal('hide');
                                  }
                              },
                          });
                      }
                      if (nameValue == "update") {
                          $.ajax({
                              data: formData,
                              url: '{{route('site.update')}}',
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
                                      dtSiteTable.draw();
                                      $('#siteForm').trigger("reset");
                                      $('#SitesModal').modal('hide');
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
                  }
              });
          }
          $(document).on('click','.deleteSite', function (data){
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
                          url: "site/" + id +"/delete",
                          success: function (data) {
                              dtSiteTable.draw();
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
          $(document).on('click','.editSite', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "site/" + id +"/edit",
                  success: function (data) {
                      console.log(data)
                      $('#SitesModal').modal('show');
                      $('.siteModalLabel').html("Edit Site");
                      $('#submitButton').prop('class','btn btn-success');
                      $('#submitButton').text('Update');
                      $('#submitButton').val('update');

                      $('#id').val(data.id);
                      $('#site_name').val(data.site_name);

                      var id_cate =[];
                      $.each(data.category, function(i, item) {
                          id_cate.push(item.id)
                      });
                      $('#select_category').val(id_cate);
                      $('#select_category').select2();
                      $('#logo').attr('src',data.logo);
                  },
                  error: function (data) {
                  }
              });
          });
      });
      $(document).ready(function() {
          $('#avatar').click(function(){
              $('#image').click();
          });
          $('#logo').click(function(){
              $('#image_logo').click();
          });
          $('.add-new-site').on('click',function (){
              $('#siteForm').trigger("reset");
              $('.siteModalLabel').html("Add Site");
              $('#submitButton').prop('class','btn btn-primary');
              $('#submitButton').text('Create');
              $('#submitButton').val('create');
              $('#select_category').select2();
              $('#select_api_key').select2();
              // $('#logo').attr('src', '');
           });
      });
      function changeImg(input){
          if(input.files && input.files[0]){
              var reader = new FileReader();
              reader.onload = function(e){
                  $('#avatar').attr('src',e.target.result);
                  $('#logo').attr('src',e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }


      $("#categoryForm").on('submit', function (e) {
          e.preventDefault();
          var formData = new FormData($("#categoryForm")[0]);
          if($('#submitButton').val() == 'create'){
              $.ajax({
                  data: formData,
                  url: '{{route('category.create')}}',
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
                          $('#categoryForm').trigger("reset");
                          $('#CategoryModal').modal('hide');

                          if(typeof data.all_category == 'undefined'){
                              data.all_category = {};
                          }
                          if(typeof rebuildCategoryOption == 'function'){
                              rebuildCategoryOption(data.all_category)
                          }
                      }
                  },
              });
          }
      });

      function rebuildCategoryOption(categories){
          var elementSelect = $("#select_category");

          if(elementSelect.length <= 0){
              return false;
          }
          elementSelect.empty();

          for(var category of categories){
              elementSelect.append(
                  $("<option></option>", {
                      value : category.id
                  }).text(category.category_name)
              );
          }
      }


  </script>
@endsection
