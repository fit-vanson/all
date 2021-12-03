@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">

  <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
  <link rel="stylesheet" href="{{asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
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
            <span>Total Users</span>
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
            <span>Users Admin</span>
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
            <span>Users</span>
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
      <table class="user-list-table table">
        <thead class="table-light">
          <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
    <!-- Modal to add new user starts-->

    <div class="modal modal-slide-in fade" id="modals-slide-in">
      <div class="modal-dialog">
          <form class="add-new-user modal-content pt-0" id="userForm" novalidate="novalidate">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
              <div class="modal-header mb-1">
                  <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
              </div>
              <div class="modal-body flex-grow-1">
                  <input type="hidden" name="id" id="id" value="">
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Username</label>
                      <input type="text" id="user_name" class="form-control dt-uname" placeholder="User1" name="user_name">
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-email">Email</label>
                      <input type="email" id="user_email" class="form-control dt-email" placeholder="user1@vietmmo.net" name="user_email">
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-contact">Password</label>
                      <input type="text" id="user_password" class="form-control dt-contact" placeholder="**********" name="user_password">
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="user-role">User Role</label>
                      <select id="user_role" class="form-select" name="user_role">
                          @foreach($roles as $role)
                          <option value="{{$role->name}}">{{$role->name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <button type="submit" class="btn btn-primary" id="submitButton" value="create">Create</button>
                  <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
              </div>
          </form>
      </div>
    </div>

    <!-- Modal to add new user Ends-->
  </div>
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
          var  UserForm = $('#userForm');
          // Users List datatable
          var dtUserTable = $('.user-list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('user.getIndex')}}",
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: '' },
                  { data: 'name' },
                  { data: 'email' },
                  { data: 'roles' },
                  { data: 'action' }
              ],
              columnDefs: [
                  {
                      // For Responsive
                      className: 'control',
                      orderable: false,
                      responsivePriority: 2,
                      targets: 0,
                      render: function (data, type, full, meta) {
                          return '';
                      }
                  },
                  {
                      // User full name and username
                      targets: 1,
                      responsivePriority: 4,
                      render: function (data, type, full, meta) {
                          var $name = full['name'],
                              $email = full['email'],
                              $image = full['avatar'];
                          if ($image) {
                              // For Avatar image
                              var $output =
                                  '<img src="' + assetPath + 'images/avatars/' + $image + '" alt="Avatar" height="32" width="32">';
                          } else {
                              // For Avatar badge
                              var stateNum = Math.floor(Math.random() * 6) + 1;
                              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                              var $state = states[stateNum],
                                  $name = full['name'],
                                  $initials = $name.match(/\b\w/g) || [];
                              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                              $output = '<span class="avatar-content">' + $initials + '</span>';
                          }
                          var colorClass = $image === '' ? ' bg-light-' + $state + ' ' : '';
                          // Creates full output for row
                          var $row_output =
                              '<div class="d-flex justify-content-left align-items-center">' +
                              '<div class="avatar-wrapper">' +
                              '<div class="avatar ' +
                              colorClass +
                              ' me-1">' +
                              $output +
                              '</div>' +
                              '</div>' +
                              '<div class="d-flex flex-column">' +
                              '<span class="fw-bolder">' +
                              $name +
                              '</span>' +
                              '<small class="emp_post text-muted">' +
                              $email +
                              '</small>' +
                              '</div>' +
                              '</div>';
                          return $row_output;
                      }
                  },
                  {
                      // User Role
                      targets: 3,
                      orderable: false,
                      render: function (data, type, full, meta) {

                          var $assignedTo = full.roles,
                              $output = '';
                          var roleBadgeObj = {
                              Admin:
                                  '<span class="badge rounded-pill badge-light-primary">Admin</span>',
                              User:
                                  '<span class="badge rounded-pill badge-light-success">User</span>',
                          };
                          $output = roleBadgeObj[full.roles];
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
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editUser">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteUser">' +
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
                      text: 'Add New User',
                      className: 'add-new btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#modals-slide-in'
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
          // Form Validation
          if (UserForm.length) {
              UserForm.validate({
                  errorClass: 'error',
                  rules: {
                      'user_name': {
                          required: true
                      },
                      'user_email': {
                          required: true
                      }
                  }
              });
              UserForm.on('submit', function (e) {
                  var isValid = UserForm.valid();
                  var nameValue = document.getElementById("submitButton").value;
                  e.preventDefault();
                  if (isValid) {
                      if (nameValue == "create") {
                          $.ajax({
                              data: $('#userForm').serialize(),
                              url: '{{route('user.create')}}',
                              type: "POST",
                              dataType: 'json',
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
                                      dtUserTable.draw();
                                      $('#userForm').trigger("reset");
                                      $('#modals-slide-in').modal('hide');
                                  }
                              },
                          });
                      }
                      if (nameValue == "update") {
                          $.ajax({
                              data: $('#userForm').serialize(),
                              url: '{{route('user.update')}}',
                              type: "POST",
                              dataType: 'json',
                              success: function (data) {
                                  if (data.success) {
                                      toastr['success']('', data.success, {
                                          showMethod: 'fadeIn',
                                          hideMethod: 'fadeOut',
                                          timeOut: 2000,
                                      });
                                      dtUserTable.draw();
                                      $('#userForm').trigger("reset");
                                      $('#modals-slide-in').modal('hide');
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
          $(document).on('click','.deleteUser', function (data){
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
                          url: "user/delete/" + id,
                          success: function (data) {
                              console.log(data)
                              dtUserTable.draw();
                          },
                          error: function (data) {
                          }
                      });
                      Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'Your file has been deleted.',
                          customClass: {
                              confirmButton: 'btn btn-success'
                          }
                      });
                  }
              });
          });
          $(document).on('click','.editUser', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "user/edit/" + id,
                  success: function (data) {
                      $('.modal-slide-in').modal('show');
                      $('#exampleModalLabel').html("Edit User");
                      $('#submitButton').prop('class','btn btn-success');
                      $('#submitButton').text('Update');
                      $('#submitButton').val('update');

                      $('#id').val(data[0].id);
                      $('#user_name').val(data[0].name);
                      $('#user_email').val(data[0].email);
                      $('#user_role').val(data[1]);
                  },
                  error: function (data) {
                  }
              });
          });
      });
      $(document).ready(function() {
          $('.add-new').on('click',function (){
              $('#userForm').trigger("reset");
              $('#exampleModalLabel').html("Add User");
              $('#submitButton').prop('class','btn btn-primary');
              $('#submitButton').text('Create');
              $('#submitButton').val('create');
          });



      });
  </script>
@endsection
