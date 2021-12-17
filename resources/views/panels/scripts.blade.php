<!-- BEGIN: Vendor JS-->
<script src="{{ asset(('vendors/js/vendors.min.js')) }}"></script>
<!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset(('vendors/js/ui/jquery.sticky.js'))}}"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(('js/core/app.js')) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

<!-- custome scripts file for user -->
<script src="{{ asset(('js/core/scripts.js')) }}"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#avatar').click(function(){
                $('#insert_image').click();
            });
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
                    console.log('jQuery bind complete');
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

        // load_images();
        //
        // function load_images()
        // {
        //     $.ajax({
        //         url:"fetch_images.php",
        //         success:function(data)
        //         {
        //             $('#store_image').html(data);
        //         }
        //     })
        // }

    });
</script>

@if($configData['blankPage'] === false)
<script src="{{ asset(('js/scripts/customizer.js')) }}"></script>
@endif
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
