<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', $title ?? 'Untitled')</title>
	<!-- core:css -->
	<link rel="stylesheet" href="{{ url('/vendor/nobleui/template/assets/vendors/core/core.css') }}">
	<!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{ url('/vendor/nobleui/template/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
	<!-- end plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="{{ url('/vendor/nobleui/template/assets/fonts/feather-font/css/iconfont.css') }}">
	<link rel="stylesheet" href="{{ url('/vendor/nobleui/template/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
	<!-- endinject -->
  <!-- Layout styles -->  
	<link rel="stylesheet" href="{{ url('/vendor/nobleui/template/assets/css/demo_1/style.css') }}">
	<!-- core:js -->
	<script src="{{ url('/vendor/nobleui/template/assets/vendors/core/core.js') }}"></script>
	<!-- endinject -->
  <!-- plugin js for this page -->
  <script src="{{ url('/vendor/nobleui/template/assets/vendors/chartjs/Chart.min.js') }}"></script>
  <script src="{{ url('/vendor/nobleui/template/assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
  <script src="{{ url('/vendor/nobleui/template/assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script>
  <script src="{{ url('/vendor/nobleui/template/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ url('/vendor/nobleui/template/assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ url('/vendor/nobleui/template/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="{{ url('/vendor/nobleui/template/assets/vendors/feather-icons/feather.min.js') }}"></script>
	<script src="{{ url('/vendor/nobleui/template/assets/js/template.js') }}"></script>
	<!-- endinject -->
  <!-- custom js for this page -->
  <script src="{{ url('/vendor/nobleui/template/assets/js/dashboard.js') }}"></script>
  <script src="{{ url('/vendor/nobleui/template/assets/js/datepicker.js') }}"></script>
	<!-- end custom js for this page -->
	<link rel="stylesheet" href="{{ url('/vendor/fontawesome/css/all.css') }}">
</head>
<body>
  <script type="text/javascript">
    $(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
      });
    });
  </script>
  <script src="{{ url('/assets/js/utils/Selector.js') }}"></script>
	<script src="{{ url('/assets/js/utils/BaseUrl.js') }}"></script>
	<script>
		const baseUrl = new BaseUrl(@json(url('')));
	</script>
  @include('loaders.default')
  <script src="{{ url('/assets/js/utils/PageSpinner.js') }}"></script>
  <script type="text/javascript">
    let pageSpinner = new PageSpinner('pageSpinner');
  </script>
	<div class="main-wrapper">
    @include('sidebars.wrapper')
		<div class="page-wrapper">
			@include('navbars.wrapper')
			<div class="page-content">
        @yield('content')
			</div>
			@include('footers.default')
		</div>
	</div>
</body>
</html>