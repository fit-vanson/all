@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Directlink ')

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
            <div class="card-header">
                <h4 class="card-title">Web Home</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form class="row" id="directlinkSiteForm">
                            <input type="hidden" name="id" id="id" value="{{$site->id}}">
                            <input  id="header_image" type="file" name="header_image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Direct Link</label>
                                <input type="text" id="directlink" class="form-control" placeholder="Direct Link" value="{{$site->directlink}}" name="directlink">
                            </div>
                            <div class="mb-1">
                                <button type="submit" class="btn btn-success" id="submitButton" value="update">Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
      <!-- /Categories table -->
    </div>
    <!--/ User Content -->
  </div>
</section>


@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{asset('js/scripts/components/components-navs.js')}}"></script>
  <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script>


      $(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          ('use strict');
          var url = window.location.pathname;
          var  directlinkSiteForm = $('#directlinkSiteForm');
          if (directlinkSiteForm.length) {
              directlinkSiteForm.validate({
                  errorClass: 'error',
                  rules: {
                      'directlink': {
                          required: true
                      }

                  }
              });
              directlinkSiteForm.on('submit', function (e) {
                  var isValid = directlinkSiteForm.valid();
                  e.preventDefault();
                  var formData = new FormData($("#directlinkSiteForm")[0]);
                  if(isValid){
                      if($('#submitButton').val() == 'update'){
                          $.ajax({
                              data: formData,
                              url: url +"/update",
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
                                  }
                              },
                          });
                      }
                  }


              });
          }
      });

  </script>

@endsection
