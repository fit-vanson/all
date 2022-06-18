@extends('layouts/contentLayoutMaster')

@section('title', 'Site View - Ads')

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
                <h4 class="card-title">MANAGE ADS</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="row" id="AdsSiteForm">
                            <div id="full-wrapper">
                                <div id="full-container">
                                    <input type="hidden" name="id" id="id" value="{{$site->id}}">
                                    <div class="ads_manage">
                                        <span class="fw-bolder me-25">ADs:</span>
                                        @if($site->ad_switch ==1)
                                            <a data-id="{{$site->id}}" class="badge bg-light-success changeAds">Active</a>
                                        @else
                                            <a data-id="{{$site->id}}" class="badge bg-light-danger changeAds">Deactivated</a>
                                        @endif

                                    </div>
                                    <?php
                                    $ads = json_decode($site->ads,true);
                                    ?>
                                    <div class="input_admob">
                                        @if($site->ad_switch ==1)
                                        <div class="mb-1">
                                            <label class="form-label" for="basic-icon-default-uname">Ads Provider</label>
                                            <select class="form-select" id="ads_provider" name="ads_provider" >
                                                <option value="ADMOB">ADMOB</option>
                                                <option value="FACEBOOKBIDDING">FACEBOOKBIDDING</option>
                                                <option value="APPLOVIN">APPLOVIN</option>
                                                <option value="IRONSOURCE">IRONSOURCE</option>
                                                <option value="STARTAPP">STARTAPP</option>
                                            </select>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <h3>ADMOB</h3>
                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob Publisher ID</label>
                                                <input type="text" id="AdMob_Publisher_ID" class="form-control"  value="{{@$ads['AdMob_Publisher_ID']}}" name="AdMob_Publisher_ID">
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob App ID</label>
                                                <input type="text" id="AdMob_App_ID" class="form-control"  value="{{@$ads['AdMob_App_ID']}}" name="AdMob_App_ID">
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob Banner Ad Unit ID</label>
                                                <input type="text" id="AdMob_Banner_Ad_Unit_ID" class="form-control"  value="{{@$ads['AdMob_Banner_Ad_Unit_ID']}}" name="AdMob_Banner_Ad_Unit_ID">
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob Interstitial Ad Unit ID</label>
                                                <input type="text" id="AdMob_Interstitial_Ad_Unit_ID" class="form-control"  value="{{@$ads['AdMob_Interstitial_Ad_Unit_ID']}}" name="AdMob_Interstitial_Ad_Unit_ID">
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob Reward Ad Unit ID</label>
                                                <input type="text" id="AdMob_App_Reward_Ad_Unit_ID" class="form-control"  value="{{@$ads['AdMob_App_Reward_Ad_Unit_ID']}}" name="AdMob_App_Reward_Ad_Unit_ID">
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob Native Ad Unit ID</label>
                                                <input type="text" id="AdMob_Native_Ad_Unit_ID" class="form-control"  value="{{@$ads['AdMob_Native_Ad_Unit_ID']}}" name="AdMob_Native_Ad_Unit_ID">
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label" for="basic-icon-default-uname">AdMob App Open Ad Unit ID</label>
                                                <input type="text" id="AdMob_App_Open_Ad_Unit_ID" class="form-control"  value="{{@$ads['AdMob_App_Open_Ad_Unit_ID']}}" name="AdMob_App_Open_Ad_Unit_ID">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <h3><h3>APPLOVIN</h3></h3>
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label for="applovin_banner">Applovin Banner</label>
                                                    <input class="form-control" placeholder="Tags" name="applovin_banner" type="text" value="applovin_banner" id="applovin_banner">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label for="applovin_interstitial">Applovin Interstitial</label>
                                                    <input class="form-control" placeholder="Tags" name="applovin_interstitial" type="text" value="applovin_interstitial" id="applovin_interstitial">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label for="applovin_reward">Applovin Reward</label>
                                                    <input class="form-control" placeholder="Tags" name="applovin_reward" type="text" value="applovin_reward" id="applovin_reward">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <h3><h3>IRONSOURCE</h3></h3>
                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label for="ironsource_id">Ironsource Id</label>
                                                    <input class="form-control" placeholder="Tags" name="ironsource_id" type="text" value="ironsource_id" id="ironsource_id">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <h3><h3>STARTAPP</h3></h3>
                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label for="startapp_id">Startapp Id</label>
                                                    <input class="form-control" placeholder="Tags" name="startapp_id" type="text" value="startapp_id" id="startapp_id">
                                                </div>
                                            </div>
                                        </div>
                                        @endif
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
          var  AdsSiteForm = $('#AdsSiteForm');
          AdsSiteForm.on('submit', function (e) {
              e.preventDefault();
              // var hvalue = $('.ql-editor').html();
              // var policy = $('#policy').val(hvalue);
              var formData = new FormData($("#AdsSiteForm")[0]);
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
                              console.log(data)
                              $(".input_admob").load(" .input_admob");
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
