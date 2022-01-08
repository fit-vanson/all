@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Category')

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
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
  <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">

  <link rel="stylesheet" href="{{ asset(('js/scripts/searchable/css/bootstrap-select.min.css')) }}">
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
          <a class="nav-link active" href="{{asset('admin/site/view/'.$site->site_name)}}">
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
            <a class="nav-link" href="{{asset('admin/site/view/'.$site->site_name.'/feature-images')}}">
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
              <table class="datatable-site-categories table">
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
      <!-- /Categories table -->
    </div>
    <!--/ User Content -->
  </div>
</section>

{{--@include('content/_partials/_modals/modal-edit-user')--}}
@include('content.site.modal_edit_category')
@include('content.site.modal_add_category')
@include('content.category.modal-category')
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(('js/scripts/searchable/js/bootstrap-select.min.js')) }}"></script>
  <script>
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
          var dtTable = $('.datatable-site-categories').DataTable({
              processing: true,
              serverSide: true,
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
                      orderable: false,
                      responsivePriority: 4,
                      render: function (data, type, full, meta) {
                          var $output ='<img src="{{asset('storage/categories')}}/'+data+'" alt="Avatar" height="100px">';
                          return $output;
                      }
                  },

                  {
                      targets: 1,
                      responsivePriority: 1,
                      render: function (data, type, full, meta) {
                          var $output ='<a href="/admin/wallpaper?category='+data+'"> <span class="fw-bolder">'+data+'</span>';
                          return $output;
                      }
                  },

                  {
                      // User Role
                      targets: 3,
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
                      className: 'addNewCategorySite btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#AddSiteCategoryModal',
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
                              $(".site_categories").load(" .site_categories");
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
          $(document).on('click','.editSiteCategory', function (data){
              var id = $(this).data("id");
              $.ajax({
                  type: "get",
                  url: url +"/category/" + id + "/edit",
                  success: function (data) {
                      $('#EditSiteCategoryModal').modal('show');
                      $('#id').val(data[0].id);
                      $('#category_site_name_edit').val(data[1].category_name);
                      if(data[0].image){
                          $('#avatar_edit').attr('src','{{asset('storage/categories')}}/'+data[0].image);
                      }else {
                          $('#avatar_edit').attr('src','{{asset('storage/categories')}}/'+data[1].image);
                      }

                  },
                  error: function (data) {
                  }
              });
          });
          $(document).on('click','.addNewCategorySite', function (data){
              console.log(url)
              $.ajax({
                  type: "get",
                  url: url +"/category/edit",
                  success: function (data) {
                      $('#id_site').val(data.id);
                      var id_categoris =[];
                      $.each(data.category, function(i, item) {
                          id_categoris.push(item.id.toString())
                      });
                      $('#select_category').selectpicker('val', id_categoris);
                  },
                  error: function (data) {
                  }
              });
          });

          var  AddCategorySiteForm = $('#AddCategorySiteForm');
          AddCategorySiteForm.on('submit', function (e) {
              e.preventDefault();
              var formData = new FormData($("#AddCategorySiteForm")[0]);
                  $.ajax({
                      data: formData,
                      url: url +"/add-category",
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
                              $(".site_categories").load(" .site_categories");
                              $('#AddCategorySiteForm').trigger("reset");
                              $('#AddSiteCategoryModal').modal('hide');
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
          $("#categoryForm").on('submit', function (e) {
              e.preventDefault();
              var formData = new FormData($("#categoryForm")[0]);
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

          });
          function rebuildCategoryOption(categories){
              var elementSelect = $("#select_category");

              if(elementSelect.length <= 0){
                  return false;
              }
              elementSelect.empty();
              $.ajax({
                  type: "get",
                  url: url +"/category/edit",
                  success: function (data) {
                      $('#id_site').val(data.id);
                      var id_categoris =[];
                      $.each(data.category, function(i, item) {
                          id_categoris.push(item.id.toString())
                      });
                      $('#select_category').selectpicker('val', id_categoris);
                  },
                  error: function (data) {
                  }
              });

              for(var category of categories){
                  elementSelect.append(
                      $("<option></option>", {
                          value : category.id
                      }).text(category.category_name)
                  );
              }
              $('#select_category').selectpicker('refresh');
          }
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
