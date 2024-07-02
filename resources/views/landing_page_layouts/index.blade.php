<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, inital-scale=1.0">
	<title>Rice Seed Information System</title>

	<link rel="shortcut icon" href="" type="image/x-icon">

	@include('landing_page_layouts.cssLinks')

    @stack('styles')
</script>
</head>
<body>
	<div class="main">
		@include('landing_page_layouts.navbar')
		
		<div class="container-fluid">
			@yield('content')

			@include('landing_page_layouts.footer')
		</div>
	</div>

	@auth
		@include('landing_page_layouts.lockscreen')
	@endauth

    @include('landing_page_layouts.jsLinks')

    @stack('scripts')
</body>
</html>