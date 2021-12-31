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
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
      <!-- User Card -->
      <div class="card">
        <div class="card-body">
          <div class="user-avatar-section">
            <div class="d-flex align-items-center flex-column">
              <img
                class="img-fluid rounded mt-3 mb-2"
                src="{{asset('/storage/sites/'.$site->logo)}}"
                height="110"
                width="110"
                alt="User avatar"
              />
              <div class="user-info text-center">
                <h4>{{$site->site_name}}</h4>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-around my-2 pt-75">
            <div class="d-flex align-items-start me-2">
              <span class="badge bg-light-primary p-75 rounded">
                <i data-feather="check" class="font-medium-2"></i>
              </span>
              <div class="ms-75">
                <h4 class="mb-0">1.23k</h4>
                <small>Tasks Done</small>
              </div>
            </div>
            <div class="d-flex align-items-start">
              <span class="badge bg-light-primary p-75 rounded">
                <i data-feather="briefcase" class="font-medium-2"></i>
              </span>
              <div class="ms-75">
                <h4 class="mb-0">568</h4>
                <small>Projects Done</small>
              </div>
            </div>
          </div>
          <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
          <div class="info-container">
            <ul class="list-unstyled">
              <li class="mb-75">
                <span class="fw-bolder me-25">Username:</span>
                <span>violet.dev</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Billing Email:</span>
                <span>vafgot@vultukir.org</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Status:</span>
                <span class="badge bg-light-success">Active</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Role:</span>
                <span>Author</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Tax ID:</span>
                <span>Tax-8965</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Contact:</span>
                <span>+1 (609) 933-44-22</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Language:</span>
                <span>English</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Country:</span>
                <span>Wake Island</span>
              </li>
            </ul>
            <div class="d-flex justify-content-center pt-2">
              <a href="javascript:;" class="btn btn-primary me-1" data-bs-target="#editUser" data-bs-toggle="modal">
                Edit
              </a>
              <a href="javascript:;" class="btn btn-outline-danger suspend-user">Suspended</a>
            </div>
          </div>
        </div>
      </div>
      <!-- /User Card -->
    </div>
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
          <a class="nav-link" href="{{asset('app/user/view/notifications')}}">
            <i data-feather="bell" class="font-medium-3 me-50"></i><span class="fw-bold">Notifications</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{asset('app/user/view/connections')}}">
            <i data-feather="link" class="font-medium-3 me-50"></i><span class="fw-bold">Connections</span>
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
                            <img id="logo_header_image" class="thumbnail" style="width: 200px" src="@if($home) {{asset('storage/homes/'.$home->header_image)}} @else {{asset('images/avatars/1.png')}} @endif">
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Header Title</label>
                                <input type="text" id="header_title" class="form-control" placeholder="Header Title" @if($home) value="{{$home->header_title}}" @endif name="header_title">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Header Content</label>
                                <textarea class="form-control" id="header_content" name="header_content" rows="8" placeholder="Header Content">@if($home) {{$home->header_content}} @endif</textarea>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Body Title</label>
                                <input type="text" id="body_title" class="form-control" placeholder="Body Title" @if($home)  value="{{$home->body_title}}" @endif name="body_title">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Body Content</label>
                                <textarea class="form-control" id="body_content" name="body_content" rows="8" placeholder="Body Content">@if($home) {{$home->body_content}}@endif</textarea>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-icon-default-uname">Footer Title</label>
                                <input type="text" id="footer_title" class="form-control" placeholder="Footer Title" @if($home)  value="{{$home->footer_title}}" @endif name="footer_title">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Footer Content</label>
                                <textarea class="form-control" id="footer_content" name="footer_content" rows="8" placeholder="Footer Content">@if($home) {{$home->footer_content}} @endif</textarea>
                            </div>
                            <div class="mb-1">
                                <button type="submit" class="btn btn-primary" id="submitButton" value="create">Update</button>
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
                  if($('#submitButton').val() == 'create'){
                      $.ajax({
                          data: formData,
                          url: '{{route('home.create')}}',
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
                                  $('#HomeSiteForm').trigger("reset");
                                  $('#HomeSiteModal').modal('hide');
                              }
                          },
                      });
                  }
                  if($('#submitButton').val() == 'update'){
                      $.ajax({
                          data: formData,
                          url: '{{route('home.update')}}',
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
                                  $('#HomeSiteModal').modal('hide');
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
      });

  </script>

@endsection
