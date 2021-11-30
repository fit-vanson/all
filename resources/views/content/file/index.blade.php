@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','File Manager')
@section('vendor-style')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
@endsection
{{-- page styles --}}
@section('page-style')
{{--    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">--}}
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            <iframe src="/laravel-filemanager" style="width: 100%; height: 700px; overflow: hidden; border: none;"></iframe>
        </div>
    </div>





@endsection

@section('vendor-script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#uploadForm').on('submit',function (event){
            console.log(event)
            event.preventDefault();
            var formData = new FormData($("#uploadForm")[0]);

                $.ajax({
                    // data: $('#projectForm2').serialize(),
                    data: formData,
                    url: "{{ route('file.upload') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#uploadForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#uploadForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });


        });


    </script>
@endsection



