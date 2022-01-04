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
                        <a href="//{{$site->site_name}}" target="_blank" >
                            <h3>{{$site->site_name}}</h3>
                        </a>
                    </div>
                </div>
            </div>

            <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
            <div class="info-container">
                <ul class="list-unstyled">
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Site Name:</span>
                        <span>{{$site->name_site}}</span>
                    </li>
                    <li class="mb-75 site_categories">
                        <span class="fw-bolder me-25">Categories:</span>
                        <span>{{count($site->category)}}</span>
                    </li>
                    <li class="mb-75 site_feature_images">
                        <span class="fw-bolder me-25">Feature Images:</span>
                        <span>{{count($site->feature_images)}}</span>
                    </li>
                    <li class="mb-75 site_ads">
                        <span class="fw-bolder me-25">ADs:</span>
                        @if($site->ad_switch != 0)
                            <a data-id="{{$site->id}}" class="badge bg-light-success changeAds">Active</a>
                        @else
                            <a data-id="{{$site->id}}" class="badge bg-light-danger changeAds">Deactivated</a>
                        @endif
                    </li>
                    <li class="mb-75 site_block_ip">
                        <span class="fw-bolder me-25">Block Ip:</span>
                        @if(count($site->blockIps) != 0)
                            <span class="badge bg-light-success">Active</span>
                        @else
                            <span class="badge bg-light-danger">Deactivated</span>
                        @endif

                    </li>
                    <li class="mb-75 site_policy">
                        <span class="fw-bolder me-25">Policy:</span>
                        @if(strip_tags($site->policy) != null)
                            <span class="badge bg-light-success">Active</span>
                        @else
                            <span class="badge bg-light-danger">Deactivated</span>
                        @endif
                    </li>

                    <li class="mb-75 site_load_feature">
                        <span class="fw-bolder me-25">Load Feature:</span>
                        @if($site->load_view_by == 0 )
                            <span class="badge bg-light-secondary">Random</span>
                        @elseif($site->load_view_by == 1 )
                            <span class="badge bg-light-success">Manual</span>
                        @elseif($site->load_view_by == 2 )
                            <span class="badge bg-light-info">Most View</span>
                        @elseif($site->load_view_by == 3 )
                            <span class="badge bg-light-primary">Feature Wallpaper</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /User Card -->
</div>
{{--@include('panels/scripts')--}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click','.changeAds', function (data){
            var id = $(this).data("id");
            $.ajax({
                type: "get",
                url: "{{asset('admin/site')}}/"+id+"/change-ads",
                // url: '../'+ id + "/change-ads",
                success: function (data) {
                    $(".site_ads").load(" .site_ads");
                    toastr['success']('', data.success, {
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut',
                        timeOut: 2000,
                    });
                },
                error: function (data) {
                }
            });
        });
    });
</script>



