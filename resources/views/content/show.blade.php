@extends('layouts/contentLayoutMaster')
@section('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet" href="{{ asset(('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('title', 'Wallpaper VietMMO')
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(('css/base/pages/app-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- Wishlist Starts -->
    <div class="scrolling-pagination">
    <section id="wishlist" class="grid-view wishlist-items">
        @foreach($data as $item)
            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="{{asset('storage/wallpapers/download/'.$item['origin_image'])}}">
                        <img src="{{asset('storage/wallpapers/thumbnail/'.$item['thumbnail_image'])}}" class="img-fluid" alt="img-placeholder" />
                    </a>
                </div>
                <div class="card-body">
                    <div class="item-name">
                        <a href="{{asset('storage/wallpapers/download/'.$item['origin_image'])}}">{{$item['name']}}</a>
                    </div>
                    <p class="card-text item-description">
                        {{@$item['category']['category_name']}}
                    </p>
                </div>
            </div>
        @endforeach
    </section>
    {{ $data->links() }}
    </div>

    <!-- Wishlist Ends -->
@endsection

@section('vendor-script')
    <!-- Vendor js files -->
    <script src="{{ asset(('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(('js/scripts/pages/app-ecommerce-wishlist.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>

{{--    <script>--}}
{{--        $(window).scroll(wallpaper);--}}
{{--        function wallpaper(){--}}
{{--            var page =;--}}
{{--        }--}}
{{--    </script>--}}
@endsection
