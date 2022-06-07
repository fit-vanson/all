@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Block Ips')

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
    @include('content.site.url')
      <!--/ User Pills -->

      <!-- Categories table -->
      <div class="card">
        <h4 class="card-header">Site List Block Ips</h4>
          <div class="card-datatable table-responsive pt-0">
              <table class="datatable-site-block-ips table">
                  <thead class="table-light">
                  <tr>
                      <th>Ip Address </th>
                      <th>Created at</th>
                      <th>Action</th>
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
@include('content.site.modal_add_site_block_ips')
@include('content.blockip.modal-block-ip')
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
  <script>
      $('#block_ips_site').select2();
      $(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          ('use strict');
          var url = window.location.pathname;
          var  addBlockIpsSiteForm = $('#addBlockIpsSiteForm');
          var dtTableBlockIps = $('.datatable-site-block-ips').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: url +"/get",
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
                              '<a data-id="'+full.id+'" class="btn btn-sm btn-icon deleteSiteBlockIp">' +
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
                      className: 'add_new_block_ips btn btn-primary',
                      attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#addSiteBlockIpsModal',
                          'data-id': '1'
                      },
                      init: function (api, node, config) {
                          $(node).removeClass('btn-secondary');
                      }
                  }
              ],

          });

          $(document).on('click','.add_new_block_ips', function (data){
              $.ajax({
                  type: "get",
                  url: url +"/edit",
                  success: function (data) {
                      $('#id_site').val(data.id);
                      var id_block_ip =[];
                      $.each(data.block_ips, function(i, item) {
                          id_block_ip.push(item.id)
                      });
                      $('#block_ips_site').val(id_block_ip);
                      $('#block_ips_site').select2();
                  },
                  error: function (data) {
                  }
              });
          });
          $(document).on('click','.deleteSiteBlockIp', function (data){
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
                          url: url +'/'+ id + "/delete",
                          success: function (data) {
                              console.log(data)
                              if(data.success){
                                  dtTableBlockIps.draw();
                                  $(".site_block_ip").load(" .site_block_ip");
                                  Swal.fire({
                                      icon: 'success',
                                      title: 'Deleted!',
                                      text: 'Your file has been deleted.',
                                      timer: 1000,
                                      customClass: {
                                          confirmButton: 'btn btn-success'
                                      }
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

          addBlockIpsSiteForm.on('submit', function (e) {
              e.preventDefault();
              var formData = new FormData($("#addBlockIpsSiteForm")[0]);
              $.ajax({
                  data: formData,
                  url: url +"/update-block-ips",
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
                          dtTableBlockIps.draw();
                          $(".site_block_ip").load(" .site_block_ip");
                          $('#addBlockIpsSiteForm').trigger("reset");
                          $('#addSiteBlockIpsModal').modal('hide');
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

          $("#blockIpForm").on('submit', function (e) {
              e.preventDefault();
              var formData = new FormData($("#blockIpForm")[0]);
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
                          $('#blockIpForm').trigger("reset");
                          $(".site_block_ip").load(" .site_block_ip");
                          $('#BlockIpModal').modal('hide');

                          if(typeof data.allBlockIps == 'undefined'){
                              data.allBlockIps = {};
                          }
                          if(typeof rebuildBlockIpsOption == 'function'){
                              rebuildBlockIpsOption(data.allBlockIps)
                          }
                      }
                  },
              });

          });
          function rebuildBlockIpsOption(blockIps){
              var elementSelect = $("#block_ips_site");

              if(elementSelect.length <= 0){
                  return false;
              }
              elementSelect.empty();
              $.ajax({
                  type: "get",
                  url: url +"/edit",
                  success: function (data) {
                      $('#id_site').val(data.id);
                      var id_block_ip =[];
                      $.each(data.block_ips, function(i, item) {
                          id_block_ip.push(item.id)
                      });
                      $('#block_ips_site').val(id_block_ip);
                      $('#block_ips_site').select2();
                  },
                  error: function (data) {
                  }
              });

              for(var blockIp  of blockIps){
                  elementSelect.append(
                      $("<option></option>", {
                          value : blockIp.id
                      }).text(blockIp.ip_address)
                  );
              }
          }
      });
  </script>

@endsection
