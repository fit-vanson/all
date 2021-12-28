@extends('layouts/contentLayoutMaster')

@section('title', 'Block Ips')

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
              <th>Ip Address</th>
              <th>Created at</th>
              <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

@include('content.blockip.modal-block-ip')

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
{{--  <script src="{{ asset(('js/scripts/pages/app-user-list.js')) }}"></script>--}}
  <script src="{{ asset(('js/scripts/forms/form-repeater.js')) }}"></script>
  <script>

      $(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          ('use strict');
          // var newUserSidebar = $('.new-user-modal');
          var  BlockIpForm = $('#blockIpForm');
          // Users List datatable
          var dtTable = $('.list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('block_ips.getIndex')}}",
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
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editBlockIp">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteBlockIp">' +
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
                      className: 'add-new btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#BlockIpModal'
                      },
                      init: function (api, node, config) {
                          $(node).removeClass('btn-secondary');
                      }
                  }
              ],

          });
          $('.add-new').on('click',function (){
              $('#categoryForm').trigger("reset");
              $('.blockIpModalLabel').html("Add IP Address");
              $('#submitButton').prop('class','btn btn-primary ');
              $('#submitButton').text('Create');
              $('#submitButton').val('create');

          });

          if (BlockIpForm.length) {
              BlockIpForm.validate({
                  errorClass: 'error',
                  rules: {
                      'ip_address': {
                          required: true
                      }
                  }
              });
              BlockIpForm.on('submit', function (e) {
                  e.preventDefault();
                  var formData = new FormData($("#blockIpForm")[0]);
                  if($('#submitButton').val() == 'create'){
                      $.ajax({
                          data: formData,
                          url: '{{route('block_ips.create')}}',
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
                                  $('#blockIpForm').trigger("reset");
                                  // $('#CategoryModal').modal('hide');
                              }
                          },
                      });
                  }
                  if($('#submitButton').val() == 'update'){
                      $.ajax({
                          data: formData,
                          url: '{{route('block_ips.update')}}',
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
                                  $('#blockIpForm').trigger("reset");
                                  $('#BlockIpModal').modal('hide');
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


          $(document).on('click','.deleteBlockIp', function (data){
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
                          url: "block-ips/" + id +"/delete",
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
          $(document).on('click','.editBlockIp', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "block-ips/" + id + "/edit",
                  success: function (data) {
                      $('#BlockIpModal').modal('show');
                      $('.blockIpModalLabel').html("Edit IP Address ");
                      $('#submitButton').prop('class','btn btn-success');
                      $('#submitButton').text('Update');
                      $('#submitButton').val('update');
                      $('#id').val(data.id);
                      $('#ip_address').val(data.ip_address);
                  },
                  error: function (data) {
                  }
              });
          });
      });

  </script>
@endsection
