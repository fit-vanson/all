<ul class="nav nav-pills mb-2">
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.site_index' ? 'active' :'' }} " href="{{asset('admin/site/view/'.$site->site_name)}}">
            <i data-feather="folder" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Categories</span></a
        >
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.BlockIps' ? 'active' :'' }}" href="{{asset('admin/site/view/'.$site->site_name.'/block-ips')}}">
            <i data-feather="lock" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Block Ips</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.home' ? 'active' :'' }}" href="{{asset('admin/site/view/'.$site->site_name.'/home')}}">
            <i data-feather="home" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Web Home</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.policy' ? 'active' :'' }}" href="{{asset('admin/site/view/'.$site->site_name.'/policy')}}">
            <i data-feather="file-text" class="font-medium-3 me-50"></i><span class="fw-bold">Policy</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.FeatureImages' ? 'active' :'' }}" href="{{asset('admin/site/view/'.$site->site_name.'/feature-images')}}">
            <i data-feather="image" class="font-medium-3 me-50"></i><span class="fw-bold">Feature Images</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.LoadFeature' ? 'active' :'' }}"  href="{{asset('admin/site/view/'.$site->site_name.'/load-feature')}}">
            <i data-feather="loader" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Load Feature</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.listIP' ? 'active' :'' }}"  href="{{asset('admin/site/view/'.$site->site_name.'/list-ip')}}">
            <i data-feather="list" class="font-medium-3 me-50"></i>
            <span class="fw-bold">List IP</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.ads' ? 'active' :'' }}" href="{{asset('admin/site/view/'.$site->site_name.'/ads')}}">
            <i data-feather="file-text" class="font-medium-3 me-50"></i><span class="fw-bold">Ads</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName() == 'site.directlink' ? 'active' :'' }} " href="{{asset('admin/site/view/'.$site->site_name.'/directlink')}}">
            <i data-feather="link" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Directlink </span>
        </a>
    </li>
</ul>
