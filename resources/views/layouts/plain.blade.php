<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@yield('title', $title ?? 'Untitled')</title>
	<!-- core:css -->
	<link rel="stylesheet" href="{{ url('/vendor/nobleui/template/assets/vendors/core/core.css') }}">
	<!-- endinject -->
  <!-- plugin css for this page -->
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
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="{{ url('/vendor/nobleui/template/assets/vendors/feather-icons/feather.min.js') }}"></script>
	<script src="{{ url('/vendor/nobleui/template/assets/js/template.js') }}"></script>
	<!-- endinject -->
  <!-- custom js for this page -->
	<!-- end custom js for this page -->
</head>
<body>
	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">
        @yield('content')
			</div>
		</div>
	</div>

</body>
</html>