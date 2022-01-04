@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Home')

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
      <ul class="nav nav-pills mb-2">
        <li class="nav-item">
          <a class="nav-link " href="{{asset('admin/site/view/'.$site->site_name)}}">
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
            <a class="nav-link active" href="{{asset('admin/site/view/'.$site->site_name.'/home')}}">
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
            <div class="card-header">
                <h4 class="card-title">Web Home</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form class="row" id="HomeSiteForm">
                            <input type="hidden" name="id" id="id" value="{{$site->id}}">
                            <input  id="header_image" type="file" name="header_image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                            <img id="logo_header_image" class="thumbnail" style="width: 200px" src="@if($site->header_image) {{asset('storage/homes/'.$site->header_image)}} @else {{asset('images/avatars/1.png')}} @endif">
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Header Title</label>
                                <input type="text" id="header_title" class="form-control" placeholder="Header Title" value="{{$site->header_title}}" name="header_title">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Header Content</label>
                                <textarea class="form-control" id="header_content" name="header_content" rows="8" placeholder="Header Content">{{$site->header_content}}</textarea>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Body Title</label>
                                <input type="text" id="body_title" class="form-control" placeholder="Body Title" value="{{$site->body_title}}" name="body_title">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Body Content</label>
                                <textarea class="form-control" id="body_content" name="body_content" rows="8" placeholder="Body Content">{{$site->body_content}}</textarea>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Footer Title</label>
                                <input type="text" id="footer_title" class="form-control" placeholder="Footer Title" value="{{$site->footer_title}}" name="footer_title">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Footer Content</label>
                                <textarea class="form-control" id="footer_content" name="footer_content" rows="8" placeholder="Footer Content">{{$site->footer_content}}</textarea>
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
      $(document).ready(function() {
          $('#logo_header_image').click(function(){
              $('#header_image').click();
          });
      });
      function changeImg(input){
          if(input.files && input.files[0]){
              var reader = new FileReader();
              reader.onload = function(e){
                  $('#logo_header_image').attr('src',e.target.result);
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
          var  HomeSiteForm = $('#HomeSiteForm');
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

              });
          }
      });

  </script>

@endsection
