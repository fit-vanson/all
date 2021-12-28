@extends('layouts/contentLayoutMaster')

@section('title', 'User View - Account')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/forms/form-validation.css')) }}">
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
                                <input type="file" name="insert_image" id="insert_image" hidden accept="image/*" />
                                <img id="avatar" class="img-fluid rounded mt-3 mb-2" width="110px"  height="110"
                                     <?php
                                        echo  ($user->avatar) ?  'src="data:image/png;base64,' . $user->avatar.'" />' : 'src="/images/avatars/1.png" />';
                                     ?>
                                <div class="user-info text-center">
                                    <h4>{{$user->name}}</h4>
                                    <span class="badge bg-light-secondary">{{$role}}</span>
                                </div>
                            </div>
                        </div>



                        <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Username:</span>
                                    <span>{{$user->name}}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Email:</span>
                                    <span>{{$user->email}}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Status:</span>
                                    <span class="badge bg-light-success">Active</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Role:</span>
                                    <span>{{$role}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">

                <!-- Change Password -->
                <div class="card">
                    <h4 class="card-header">Change Password</h4>
                    <div class="card-body">
                        <form id="formChangePassword" onsubmit="return false">
                            <div class="row">
                                <div class="mb-2 col-md-6 form-password-toggle">
                                    <label class="form-label" for="newPassword">New Password</label>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="newPassword"
                                            name="newPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        />
                                        <span class="input-group-text cursor-pointer">
                                            <i data-feather="eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-2 col-md-6 form-password-toggle">
                                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control"
                                            type="password"
                                            name="confirmPassword"
                                            id="confirmPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" id="submitButton" class="btn btn-primary me-2">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->
            </div>
            <!--/ User Content -->
        </div>

        @include('content.user.modal_insert_avatar')
    </section>

@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
    <script src="{{ asset(('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    {{-- data table --}}
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
{{--    <script src="{{ asset(('js/scripts/pages/modal-edit-user.js')) }}"></script>--}}
{{--    <script src="{{ asset(('js/scripts/pages/app-user-view-account.js')) }}"></script>--}}
{{--    <script src="{{ asset(('js/scripts/pages/app-user-view.js')) }}"></script>--}}

    <script>
        $(function () {
            'use strict';
            var formChangePassword = $('#formChangePassword');
            if (formChangePassword.length) {
                formChangePassword.validate({
                    rules: {
                        newPassword: {
                            required: true,
                            minlength: 8
                        },
                        confirmPassword: {
                            required: true,
                            minlength: 8,
                            equalTo: '#newPassword'
                        }
                    },
                    messages: {
                        newPassword: {
                            required: 'Enter new password',
                            minlength: 'Enter at least 8 characters'
                        },
                        confirmPassword: {
                            required: 'Please confirm new password',
                            minlength: 'Enter at least 8 characters',
                            equalTo: 'The password and its confirm are not the same'
                        }
                    }
                });
                formChangePassword.on('submit', function (e) {
                    var isValid = formChangePassword.valid();
                    var nameValue = document.getElementById("submitButton").value;
                    e.preventDefault();
                    console.log(isValid)
                    if (isValid) {
                            $.ajax({
                                data: $('#formChangePassword').serialize(),
                                url: '{{route('user.changeInfo')}}',
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
                                        $('#formChangePassword').trigger("reset");
                                    }
                                },
                            });
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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
                    $.ajax({
                        url:'{{route('user.changeInfo')}}',
                        type:'POST',
                        data:{"image":response},
                        success:function(data){
                            $('#insertimageModal').modal('hide');
                            $('#avatar').attr('src','data:image/png;base64,'+data['image']);
                            $('#avatar1').attr('src','data:image/png;base64,'+data['image']);
                        }
                    })
                });
            });
        });
    </script>
@endsection
