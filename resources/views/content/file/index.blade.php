@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','File Manager')
@section('vendor-style')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
@endsection
{{-- page styles --}}
@section('page-style')

@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            <iframe src="/laravel-filemanager" style="width: 100%; height:1000px; overflow: hidden; border: none;"></iframe>
        </div>
    </div>
@endsection

@section('vendor-script')
@endsection



