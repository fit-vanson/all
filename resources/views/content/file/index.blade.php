@extends('layouts/contentLayoutMaster')
@section('title', 'File Manage')
@section('vendor-style')
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endsection

@section('content')
<!-- users list start -->
<section class="app-user-list">
  <!-- list and filter start -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
        <div style="height: 900px;">
            <div id="fm"></div>
        </div>
    </div>
  </div>
  <!-- list and filter end -->
</section>
<!-- users list ends -->
@endsection
@section('vendor-script')
  <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
@endsection

