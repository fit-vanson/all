
@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard VietMMO')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
    {{-- Page css files --}}
    <link rel="stylesheet" href="{{ asset(('css/base/pages/dashboard-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/charts/chart-apex.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- Dashboard Ecommerce Starts -->
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <!-- Medal Card -->
            <div class="col-xl-4 col-md-6 col-12">
                <div class="card card-congratulation-medal">
                    <div class="card-body">
                        <h5>Xin chúc mừng 🎉 {{$data_users[0]['name']}}</h5>
                        <p class="card-text font-small-3">Upload nhiều trong tháng</p>
                        <h3 class="mb-75 mt-2 pt-50">
                            <span  style="color: #7367f0">{{$data_users[0]['countUpload']}} </span><span class="card-text font-small-3">Files Upload</span>

                        </h3>
                        <img src="{{asset('images/illustration/badge.svg')}}" class="congratulation-medal" alt="Medal Pic" />
                    </div>
                </div>
            </div>
            <!--/ Medal Card -->

            <!-- Statistics Card -->
            <div class="col-xl-8 col-md-6 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Statistics</h4>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="trending-up" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{count($file)}}</h4>
                                        <p class="card-text font-small-3 mb-0">File</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{count($data_users)}}</h4>
                                        <p class="card-text font-small-3 mb-0">User</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-danger me-2">
                                        <div class="avatar-content">
                                            <i data-feather="box" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{count($data_tags)}}</h4>
                                        <p class="card-text font-small-3 mb-0">Tags</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics Card -->
        </div>

        <div class="row match-height">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-transaction">
                    <div class="card-header">
                        <h4 class="card-title">Top Upload</h4>
                        <div class="col-lg-5">
                            <select class="select2-size-sm form-control" id="select_time" onchange="timeTop()">
                                <option value="inMonth">Trong Tháng</option>
                                <option value="lastMonth">Tháng trước</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body" id="topTime">
                        @foreach($data_users as $data)
                            <div class="transaction-item">
                                <div class="d-flex">
                                    {!! $data['avatar'] !!}
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title">{{$data['name']}}</h6>
                                        <small>{{$data['email']}}</small>
                                    </div>
                                </div>
                                <div class="fw-bolder  @if($data['diff'] > 0 ) text-success @elseif($data['diff'] < 0 ) text-danger @endif ">{{$data['countUpload']}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-browser-states">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Top Tags</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($data_tags as $data)
                            <div class="browser-states">
                            <div class="d-flex">
                                <h6 class="align-self-center mb-0">{{$data['tags_name']}}</h6>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold text-body-heading me-1">{{$data['tags_count']}}</div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>


            <!--/ Revenue Report Card -->
        </div>
    </section>
    <!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script>
        function timeTop () {
            var time = document.getElementById("select_time").value;
            $.get('{{route('home.topUpload')}}/?time='+time,function (data) {
                var html = '';
                data.forEach(function(item, index, array) {
                    if(item.diff > 0 ) {
                        diff =  'text-success';
                    }else if(item.diff < 0 ){
                        diff =  'text-danger';
                    }else{
                        diff  = '';
                    }
                    html += '<div class="transaction-item">'+
                        '<div class="d-flex">'+item.avatar+
                        '<div class="transaction-percentage">'+
                        '<h6 class="transaction-title">'+item.name+'</h6>'+
                    '<small>'+item.email+'</small>'+
                '</div>'+
                '</div>'+
                    '<div class="fw-bolder '+diff+' ">'+item.countUpload+'</div>'+
                '</div>';
                })
                console.log(html)
                $('#topTime').html(html);
            })
        }
    </script>
@endsection
