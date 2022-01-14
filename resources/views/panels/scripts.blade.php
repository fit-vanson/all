<!-- BEGIN: Vendor JS-->
<script src="{{ asset(('vendors/js/vendors.min.js')) }}"></script>
<script src="{{ asset(('js/hp.js')) }}"></script>
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
@if($configData['blankPage'] === false)
<script src="{{ asset(('js/scripts/customizer.js')) }}"></script>
@endif
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
