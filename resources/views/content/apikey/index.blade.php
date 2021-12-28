@extends('layouts/contentLayoutMaster')

@section('title', 'Api Keys List')

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
              <th>Name</th>
              <th>Key</th>
              <th>Active</th>
              <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

@include('content.apikey.modal-api-keys')

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

          $('#avatar').click(function(){
              $('#image').click();
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
          // var newUserSidebar = $('.new-user-modal');
          var  ApiKeyForm = $('#apiKeysForm');
          // Users List datatable
          var dtTable = $('.list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('api_keys.getIndex')}}",
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: 'apikey_name' },
                  { data: 'key' },
                  { data: 'active' },
                  { data: 'action' }
              ],
              columnDefs: [
                  {
                      targets: 0,
                      render: function (data, type, full, meta) {
                          var $name = full['apikey_name']
                          var $output ='<span class="fw-bolder">'+$name+'</span>';
                          return $output;
                      }
                  },
                  {
                      targets: 2,
                      orderable: false,
                      render: function (data, type, full, meta) {
                          var $assignedTo = data,
                              $output = '';
                          var realBadgeObj = {
                              0:'<span class="badge rounded-pill badge-light-danger">NoActive</span>',
                              1:'<span class="badge rounded-pill badge-light-success">Active</span>',
                          };
                          $output = realBadgeObj[data];
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
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editApiKey">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteApiKey">' +
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
                          'data-bs-target': '#ApiKeysModal'
                      },
                      init: function (api, node, config) {
                          $(node).removeClass('btn-secondary');
                      }
                  }
              ],
              // For responsive popup
              responsive: {
                  details: {
                      display: $.fn.dataTable.Responsive.display.modal({
                          header: function (row) {
                              var data = row.data();
                              return 'Details of ' + data['name'];
                          }
                      }),
                      type: 'column',
                      renderer: function (api, rowIdx, columns) {
                          var data = $.map(columns, function (col, i) {
                              return col.columnIndex !== 6 // ? Do not show row in modal popup if title is blank (for check box)
                                  ? '<tr data-dt-row="' +
                                  col.rowIdx +
                                  '" data-dt-column="' +
                                  col.columnIndex +
                                  '">' +
                                  '<td>' +
                                  col.title +
                                  ':' +
                                  '</td> ' +
                                  '<td>' +
                                  col.data +
                                  '</td>' +
                                  '</tr>'
                                  : '';
                          }).join('');
                          return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                      }
                  }
              },

          });
          $('.add-new').on('click',function (){
              $('#categoryForm').trigger("reset");
              $('.apikeyleModalLabel').html("Add Api Key");
              $('#submitButton').prop('class','btn btn-primary ');
              $('#submitButton').text('Create');
              $('#submitButton').val('create');

          });

          ApiKeyForm.on('submit', function (e) {
              e.preventDefault();
              var formData = new FormData($("#apiKeysForm")[0]);
              if($('#submitButton').val() == 'create'){
                  $.ajax({
                      data: formData,
                      url: '{{route('api_keys.create')}}',
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
                              $('#apiKeysForm').trigger("reset");
                          }
                      },
                  });
              }
              if($('#submitButton').val() == 'update'){
                  $.ajax({
                      data: formData,
                      url: '{{route('api_keys.update')}}',
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
                              $('#ApiKeysModal').modal('hide');
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

          $(document).on('click','.deleteApiKey', function (data){
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
          $(document).on('click','.editApiKey', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "api-keys/" + id + "/edit",
                  success: function (data) {
                      $('#ApiKeysModal').modal('show');
                      $('.apikeyleModalLabel').html("Edit Api Key");
                      $('#submitButton').prop('class','btn btn-success');
                      $('#submitButton').text('Update');
                      $('#submitButton').val('update');
                      $('#id').val(data.id);
                      $('#apikey_name').val(data.apikey_name);
                      $('#key').val(data.key);
                      if(data.active == 1){
                          $('#active').prop('checked', true);
                      }else {
                          $('#active').prop('checked', false);
                      }
                  },
                  error: function (data) {
                  }
              });
          });
      });

  </script>
@endsection
