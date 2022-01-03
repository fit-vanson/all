@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Policy')

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

  <link rel="stylesheet" href="{{ asset(('vendors/css/editors/quill/katex.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/editors/quill/quill.snow.css')) }}">
  <link rel="stylesheet" href="{{ asset(('vendors/css/editors/quill/quill.bubble.css')) }}">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap" rel="stylesheet">

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
            <a class="nav-link " href="{{asset('admin/site/view/'.$site->site_name.'/home')}}">
            <i data-feather="home" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Web Home</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{{asset('admin/site/view/'.$site->site_name.'/policy')}}">
            <i data-feather="file-text" class="font-medium-3 me-50"></i><span class="fw-bold">Policy</span>
          </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{asset('admin/site/view/'.$site->site_name.'/feature-images')}}">
                <i data-feather="image" class="font-medium-3 me-50"></i><span class="fw-bold">Feature Images</span>
            </a>
        </li>
      </ul>
      <!--/ User Pills -->

      <!-- Categories table -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Web Policy</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="row" id="PolicySiteForm">
                            <div id="full-wrapper">
                                <div id="full-container">
                                    <input type="hidden" name="id" id="id" value="{{$site->id}}">
                                    <input name="policy" id="policy" type="hidden">
                                    <div class="editor">
                                         {!! $site->policy !!}
                                    </div>

                                </div>
                            </div>
                            <div class="mb-1">
                                <button type="submit" class="btn btn-success" id="submitButton" style="margin-top: 10px;" value="update">Update</button>
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
  <script src="{{ asset(('vendors/js/editors/quill/katex.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/editors/quill/highlight.min.js')) }}"></script>
  <script src="{{ asset(('vendors/js/editors/quill/quill.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset(('js/scripts/forms/form-quill-editor.js')) }}"></script>
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
          var  PolicySiteForm = $('#PolicySiteForm');
          PolicySiteForm.on('submit', function (e) {
              e.preventDefault();
              var hvalue = $('.ql-editor').html();
              var policy = $('#policy').val(hvalue);
              var formData = new FormData($("#PolicySiteForm")[0]);
              if($('#submitButton').val() == 'update'){
                  $.ajax({
                      url: url +"/update",
                      data: formData,
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
      });

  </script>

@endsection
