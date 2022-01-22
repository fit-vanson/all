
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
        {{--            <div class="col-xl-4 col-md-6 col-12">--}}
        {{--                <div class="card card-congratulation-medal">--}}
        {{--                    <div class="card-body">--}}
        {{--                        <h5>Xin chúc mừng 🎉 {{$data_users[0]['name']}}</h5>--}}
        {{--                        <p class="card-text font-small-3">Upload nhiều trong tháng</p>--}}
        {{--                        <h3 class="mb-75 mt-2 pt-50">--}}
        {{--                            <span  style="color: #7367f0">{{$data_users[0]['countUpload']}} </span><span class="card-text font-small-3">Files Upload</span>--}}

        {{--                        </h3>--}}
        {{--                        <img src="{{asset('images/illustration/badge.svg')}}" class="congratulation-medal" alt="Medal Pic" />--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        <!--/ Medal Card -->

            <!-- Statistics Card -->
            <div class="col-xl-8 col-md-6 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Số liệu thống kê</h4>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="file" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{count($categories)}}</h4>
                                        <p class="card-text font-small-3 mb-0">Categories</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <i data-feather="image" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{count($wallpaper)}}</h4>
                                        <p class="card-text font-small-3 mb-0">Wallpapers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-danger me-2">
                                        <div class="avatar-content">
                                            <i data-feather="chrome" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{count($sites)}}</h4>
                                        <p class="card-text font-small-3 mb-0">Sites</p>
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
                        <h4 class="card-title">Top view</h4>
                        <div class="col-lg-5">
                            <select class="select2-size-sm form-control" id="select_time" onchange="timeTop()">
                                <option value="inDay">Trong ngày</option>
                                <option value="inWeek">Trong tuần</option>
                                <option value="inMonth">Trong tháng</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body" id="topTime">
                        @foreach($topViews as $item)
                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="d-flex justify-content-left align-items-center">
                                        <div class="avatar-wrapper">
                                            <div class="avatar me-1">
                                                <img src="{{asset('storage/sites/'.$item['logo'])}}" alt="Avatar" height="40" width="40">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <a href="{{asset('admin/site/view/'.$item['site_name'])}}"> <h6 class="transaction-title">{{$item['name_site']}}</h6></a>
                                        <a href="//{{$item['site_name']}}" target="_blank" ><small>{{$item['site_name']}}</small></a>
                                    </div>
                                </div>
                                <div class="fw-bolder  text-success ">{{$item['count']}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        {{--            <div class="col-lg-4 col-md-6 col-12">--}}
        {{--                <div class="card card-browser-states">--}}
        {{--                    <div class="card-header">--}}
        {{--                        <div>--}}
        {{--                            <h4 class="card-title">Top Tags</h4>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body">--}}
        {{--                        @foreach($data_tags as $data)--}}
        {{--                            <div class="browser-states">--}}
        {{--                            <div class="d-flex">--}}
        {{--                                <h6 class="align-self-center mb-0">{{$data['tags_name']}}</h6>--}}
        {{--                            </div>--}}
        {{--                            <div class="d-flex align-items-center">--}}
        {{--                                <div class="fw-bold text-body-heading me-1">{{$data['tags_count']}}</div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        @endforeach--}}

        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}


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
    <script>
        function timeTop () {
            var time = document.getElementById("select_time").value;
            $.get('{{route('home.topView')}}?time='+time,function (data) {
                var html = '';
                data.forEach(function(item) {
                    html += '<div class="transaction-item">'+
                        '<div class="d-flex">'+

                        '<div class="d-flex justify-content-left align-items-center">'+
                        '<div class="avatar-wrapper">'+
                        '<div class="avatar me-1">'+
                        '<img src="{{asset('storage/sites')}}/'+item.logo+'"  alt="Avatar" height="40" width="40">'+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '<div class="transaction-percentage">'+
                        '<a href="admin/site/view/'+item.site_name+'"><h6 class="transaction-title">'+item.name_site+'</h6></a>'+
                        ' <a href="//'+item.site_name+'" target="_blank"><small>'+item.site_name+'</small></a>'+
                        '</div>'+
                        '</div>'+
                        '<div class="fw-bolder text-success ">'+item.count+'</div>'+
                        '</div>';
                })
                $('#topTime').html(html);
            })
        }
    </script>

@endsection
