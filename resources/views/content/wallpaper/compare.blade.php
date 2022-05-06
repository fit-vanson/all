@extends('layouts/contentLayoutMaster')

@section('title', 'Compare')

@section('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(('css/base/pages/app-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- Wishlist Starts -->
    <section id="wishlist" class="grid-view wishlist-items">
        @foreach($compares as $item)
{{--            @foreach($compare as $item)--}}
{{--                @dd($item)--}}
{{--            {{$item->id}}--}}
            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="{{url('app/ecommerce/details')}}">
                        <img src="{{asset('storage/wallpapers/thumbnail/'.$item->thumbnail_image)}}" class="img-fluid" alt="img-placeholder" />
                    </a>
                </div>
                <div class="card-body">
                    <div class="item-name">
                        <a href="{{url('app/ecommerce/details')}}">{{$item->name}}</a>
                    </div>
                    <p class="card-text item-description">
                        {{$item->category->category_name}}
                    </p>
                </div>
                <div class="item-options text-center">
                    <button type="button" class="btn btn-light btn-wishlist remove-wishlist">
                        <i data-feather="x"></i>
                        <span>Remove</span>
                    </button>
                </div>
            </div>
{{--            @endforeach--}}
        @endforeach
    </section>
    <!-- Wishlist Ends -->
@endsection

@section('vendor-script')
    <!-- Vendor js files -->
    <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(('js/scripts/pages/app-ecommerce-wishlist.js')) }}"></script>
@endsection
