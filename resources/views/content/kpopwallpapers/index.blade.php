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
      <table class="user-list-table table">
        <thead class="table-light">
          <tr>
            <th></th>
            <th>Name</th>
            <th>Image</th>
            <th>View Count</th>
            <th>Real</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
    <!-- Modal to add new user starts-->
    <div class="modal modal-slide-in fade" id="modals-slide-in">
      <div class="modal-dialog">
          <form class="add-new-user modal-content pt-0" id="userForm" novalidate="novalidate" enctype="multipart/form-data">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
              <div class="modal-header mb-1">
                  <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
              </div>
              <div class="modal-body flex-grow-1">
                  <input type="hidden" name="id" id="id" value="">
                  <input type="file" name="insert_image" id="insert_image" hidden  accept="image/*" />
                  <img id="avatar" class="img-fluid rounded mt-3 mb-2" width="110px"  height="110"/>

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
      @include('content.user.modal_insert_avatar')
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
                  url: "{{route('kpopwallpapers.category.getIndex')}}",
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: '' },
                  { data: 'name' },
                  { data: 'image' },
                  { data: 'view_count' },
                  { data: 'checked_ip' },
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
                              $image = full['image'];
                          if ($image) {
                              // For Avatar image
                              var $output =
                                  '<img src="data:image/png;base64,'+ $image + '" alt="Avatar" height="32" width="32">';
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

                              '</div>' +
                              '</div>';
                          return $row_output;
                      }
                  },
                  {
                      // User Role
                      targets: 4,
                      orderable: false,
                      render: function (data, type, full, meta) {
                          console.log(full)

                          var $assignedTo = full.checked_ip,
                              $output = '';
                          var realBadgeObj = {
                              1:
                                  '<span class="badge rounded-pill badge-light-danger">FAKE</span>',
                              0:
                                  '<span class="badge rounded-pill badge-light-success">REAL</span>',
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
                  var formData = $('#userForm').serializeArray();
                  var avatar = $('#avatar').attr('src');
                  formData.push({name: 'image', value: avatar});
                  e.preventDefault();
                  if (isValid) {
                      if (nameValue == "create") {
                          $.ajax({
                              data: formData,
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
                              data: formData,
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
                      console.log(data)
                      $('.modal-slide-in').modal('show');
                      $('#exampleModalLabel').html("Edit User");
                      $('#submitButton').prop('class','btn btn-success');
                      $('#submitButton').text('Update');
                      $('#submitButton').val('update');

                      $('#id').val(data[0].id);
                      $('#user_name').val(data[0].name);
                      $('#user_email').val(data[0].email);
                      $('#user_role').val(data[1]);
                      if(data[0].avatar){
                          $('#avatar').attr('src','data:image/png;base64,'+data[0].avatar);
                      }else {
                          $('#avatar').attr('src', '');
                      }

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
              $('#avatar').attr('src', '');
              // $('#avatar').attr('src','data:image/png;base64,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');
          });
      });

      $('#avatar').click(function(){
          $('#insert_image').click();
      });

      $image_crop = $('#image_demo').croppie({
          enableExif: true,
          viewport: {
              width:200,
              height:200,
              type:'square' //circle
          },
          boundary:{
              width:300,
              height:300
          }
      });

      $('#insert_image').on('change', function(){
          var reader = new FileReader();
          reader.onload = function (event) {
              $image_crop.croppie('bind', {
                  url: event.target.result
              }).then(function(){
                  console.log('complete');
              });
          }
          reader.readAsDataURL(this.files[0]);
          $('#insertimageModal').modal('show');
      });

      $('.crop_image').click(function(event){
          $image_crop.croppie('result', {
              type: 'canvas',
              size: 'viewport'
          }).then(function(response){
              console.log(response)
              $('#avatar').attr('src',response);
              $('#insertimageModal').modal('hide');
          });
      });
  </script>
@endsection
