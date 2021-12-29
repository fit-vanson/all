@extends('layouts/contentLayoutMaster')

@section('title', 'Category List')

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
            <h3 class="fw-bolder mb-75">{{$categoriesReal + $categoriesPhace}}</h3>
            <span>Total Category</span>
          </div>
          <div class="avatar bg-light-primary p-50">
            <span class="avatar-content">
              <i data-feather="file" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{$categoriesReal}}</h3>
            <span>Category Real</span>
          </div>
          <div class="avatar bg-light-success p-50">
            <span class="avatar-content">
              <i data-feather="file-plus" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{$categoriesPhace}}</h3>
            <span>Category Fake</span>
          </div>
          <div class="avatar bg-light-danger p-50">
            <span class="avatar-content">
              <i data-feather="file-minus" class="font-medium-4"></i>
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

              <th>Image</th>
              <th>Name</th>
              <th>View Count</th>
              <th>Real</th>
              <th>Count Image</th>
{{--              <th>Count Site</th>--}}
              <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
    <!-- Modal to add new user starts-->
    <div class="modal modal-slide-in fade" id="modals-slide-in">
      <div class="modal-dialog">

      </div>
    </div>
  </div>

{{--@include('content.category.modal-add-category')--}}
{{--@include('content.category.modal-edit-category')--}}
@include('content.category.modal-category')
{{--@include('content.category.modal-add-category')--}}
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
          var  CategoryForm = $('#categoryForm');
          // Users List datatable
          var dtTable = $('.list-table').DataTable({
              processing: true,
              serverSide: true,
              displayLength: 50,
              ajax: {
                  url: "{{route('category.getIndex')}}",
                  type: "post"
              },
              columns: [
                  // columns according to JSON
                  { data: 'image',className: "text-center"},
                  { data: 'category_name' },
                  { data: 'view_count' },
                  { data: 'checked_ip' },
                  { data: 'image_count' },
                  { data: 'action' }
              ],
              columnDefs: [
                  {
                      targets: 0,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                          var $output ='<img src="{{asset('storage/categories')}}/'+data+'" alt="Avatar" height="100px">';
                      return $output;
                      }
                  },
                  {
                      targets: 1,
                      responsivePriority: 1,
                      render: function (data, type, full, meta) {
                          var $output ='<span class="fw-bolder">'+data+'</span>';
                          return $output;
                      }
                  },
                  {
                      targets: 3,
                      orderable: false,
                      render: function (data, type, full, meta) {
                          var $assignedTo = full.checked_ip,
                              $output = '';
                          var realBadgeObj = {
                              1:'<span class="badge rounded-pill badge-light-danger">FAKE</span>',
                              0:'<span class="badge rounded-pill badge-light-success">REAL</span>',
                          };
                          $output = realBadgeObj[full.checked_ip];
                          return $output
                      }
                  },
                  {
                      // Actions
                      responsivePriority: 2,
                      targets: -1,
                      title: 'Actions',
                      orderable: false,
                      render: function (data, type, full, meta) {
                          return (
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon editCategory">' +
                              feather.icons['edit'].toSvg({ class: 'font-medium-2 text-warning' }) +
                              '</a>'+
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteCategory">' +
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
                      className: 'add-new btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#CategoryModal'
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
              $('.categoryModalLabel').html("Add Category");
              $('#submitButton').prop('class','btn btn-primary ');
              $('#submitButton').text('Create');
              $('#submitButton').val('create');
              $('#avatar').attr('src','images/avatars/1.png');
          });

          CategoryForm.on('submit', function (e) {
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
                              dtTable.draw();
                              // $('#categoryForm').trigger("reset");
                              // $('#CategoryModal').modal('hide');
                          }
                      },
                  });
              }
              if($('#submitButton').val() == 'update'){
                  $.ajax({
                      data: formData,
                      url: '{{route('category.update')}}',
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
                              $('#categoryForm').trigger("reset");
                              $('#CategoryModal').modal('hide');
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

          $(document).on('click','.deleteCategory', function (data){
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
                          url: "category/" + id +"/delete",
                          success: function (data) {
                              console.log(data)
                              if(data.success){
                                  dtTable.draw();
                                  toastr['success']('', data.success, {
                                      showMethod: 'fadeIn',
                                      hideMethod: 'fadeOut',
                                      timeOut: 2000,
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
          $(document).on('click','.editCategory', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: "category/" + id + "/edit",
                  success: function (data) {
                      console.log(data)
                      if(data.error){
                          toastr['error']('', data.error, {
                              showMethod: 'fadeIn',
                              hideMethod: 'fadeOut',
                              timeOut: 3000,
                          });
                      }
                      if(data.success) {
                          $('#CategoryModal').modal('show');
                          $('.categoryModalLabel').html("Edit Category");
                          $('#submitButton').prop('class','btn btn-success');
                          $('#submitButton').text('Update');
                          $('#submitButton').val('update');
                          $('#id').val(data.data.id);
                          $('#category_order').val(data.data.category_order);
                          $('#category_name').val(data.data.category_name);
                          $('#view_count').val(data.data.view_count);
                          if(data.data.checked_ip == 1){
                              $('#checked_ip').prop('checked', true);
                          }else {
                              $('#checked_ip').prop('checked', false);
                          }
                          $('#avatar').attr('src','storage/categories/'+data.data.image);
                      }
                  },
                  error: function (data) {
                  }
              });
          });

          document.getElementById('checked_ip').onclick = function(e){
              var category_name = $('#category_name').val();
              if (this.checked){
                  $('#category_name').val(category_name.replaceAll('Phace_', ''))
              }
              else{
                  $('#category_name').val('Phace_'+category_name)
              }
          };
      });

  </script>
@endsection
