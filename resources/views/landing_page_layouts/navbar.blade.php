<div class="container-fluid">
	<div class="row">
		{{-- Contact header --}}
		<div class="d-none d-lg-block col-lg-12 col-xl-12" id="contact_header">
			<div id="contact_header_contents">
				<div class="contact_header_item">
					<img src="{{url("/").'/public/images/contact/gps.png'}}" class="img-responsive">&nbsp;{{$contacts['Address']}}
				</div>
				<div class="contact_header_item">
					<img src="{{url("/").'/public/images/contact/telephone.png'}}" class="img-responsive">&nbsp;{{$contacts['Mobile No.']}}
				</div>
				<div class="contact_header_item">
					<img src="{{url("/").'/public/images/contact/mail.png'}}" class="img-responsive">&nbsp;{{$contacts['Email']}}
				</div>

				<div class="float-right">
					<div class="contact_header_item item_right">
						<div class="contact_icon_background">
							<a href="" target="_blank"><i class="fab fa-facebook-f fa-lg"></i></a>
						</div>
					</div>
					{{-- <div class="contact_header_item item_right">
						<div class="contact_icon_background">
							<a href="#" target="_blank"><i class="fab fa-youtube fa-lg"></i></a>
						</div>
					</div>
					<div class="contact_header_item item_right">
						<div class="contact_icon_background">
							<a href="#" target="_blank"><i class="fab fa-flickr fa-lg"></i></a>
						</div>
					</div>
					<div class="contact_header_item item_right">
						<div class="contact_icon_background">
							<a href="#" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>
						</div>
					</div> --}}
				</div>
			</div>
		</div>
	</div>
</div>
{{-- End of contact header --}}
{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	@if(Request::segment(1) == '')
		<a class="navbar-brand navbar-left" href="{{url('/')}}"><img src="{{url("/").'/public/images/logo2-transformed.png'}}" alt="" class="navbar_logo"></a>
	@else
		<a class="navbar-brand navbar-left" href="{{url('/')}}"><img src="{{url("/").'/public/images/logo2-transformed.png'}}" alt="" class="navbar_logo"></a>
	@endif
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		MENU
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			{{-- navbar items for left side --}}
		</ul>
		<ul class="navbar-nav">
			<li class="nav-item {{Request::segment(1) == '' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/')}}">HOME</a>
			</li>
			<li class="nav-item dropdown {{Request::segment(1) == 'about_us' ? 'active' : ''}}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          		ABOUT US
	        	</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          		{{-- <a class="dropdown-item" href="{{url('/about_us/rsis')}}">RSIS</a> --}}
	          		<a class="dropdown-item" href="{{url('/about_us/objectives')}}">OBJECTIVES</a>
	          		<a class="dropdown-item" href="{{url('/about_us/implementers')}}">IMPLEMENTERS</a>
	          		<a class="dropdown-item" href="{{url('/about_us/partners')}}">PARTNERS</a>
	        	</div>
			</li>
			<li class="nav-item {{Request::segment(1) == 'dashboard' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/dashboard')}}">DASHBOARD</a>
			</li>
			<li class="nav-item {{Request::segment(1) == 'contact_us' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/contact_us')}}">CONTACT US</a>
			</li>
			{{-- <li class="nav-item {{Request::segment(1) == 'helpdesk' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/helpdesk')}}">HELPDESK</a>
			</li>
			<li class="nav-item {{Request::segment(1) == 'links' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/links')}}">LINKS</a>
			</li>
			<li class="nav-item dropdown {{Request::segment(1) == 'resources' ? 'active' : ''}}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          		RESOURCES
	        	</a>
	        	<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	        		<li><a class="dropdown-item" href="{{url('/') . '/resources/video_guides'}}">VIDEO GUIDES</a></li>
	        	</ul>
			</li>
			<li class="nav-item {{Request::segment(1) == 'downloads' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/downloads')}}">DOWNLOADS</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('../marketplace')}}">MARKETPLACE</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="https://bpinsqcs.da.gov.ph/seed-certification-portal/" target="_blank">SG PORTAL</a>
			</li> --}}
			@guest
			<li class="nav-item {{Request::segment(1) == 'login' ? 'active' : ''}}">
				<a class="nav-link" href="{{url('/login')}}">LOGIN</a>
			</li>
			@endguest
			@auth
			<li class="nav-item dropdown {{Request::segment(1) == 'profile' ? 'active' : ''}}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          		{{strtoupper(Auth::user()->username)}}
	        	</a>
	        	<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	        		<li><a class="dropdown-item" href="{{url('/') . '/profile'}}">PROFILE</a></li>
	        		<li><a class="dropdown-item" href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
	        		<li><a class="dropdown-item" href="{{url('/') . '/profile/portal'}}">PORTAL</a></li>
	        		{{-- <li><a class="dropdown-item" href="{{url('/') . '/profile/analytics'}}">ANALYTICS</a></li> --}}
	        		{{-- @permission(['view_seed_sale_logs','view_access_logs','view_action_logs']) --}}
	        		<?php /**<li class="dropright">
	        			<a class="dropdown-item dropdown-toggle" href="#" id="activityLogDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          		ACTIVITY LOG</a>
		          		<ul class="dropdown-menu">
		          			{{-- @permission('view_seed_sale_logs') --}}
							<li><a class="dropdown-item" href="{{url('/') . '/profile/activity_log/seedsale'}}">Purchase Transaction</a></li>
							{{-- @endpermission
							@permission('view_access_logs') --}}
							<li><a class="dropdown-item" href="{{url('/') . '/profile/activity_log/logaccess'}}">Log Access</a></li>
							{{-- @endpermission
							@permission('view_action_logs') --}}
							<li><a class="dropdown-item" href="{{url('/') . '/profile/activity_log/logaction'}}">Action/Update</a></li>
							{{-- @endpermission --}}
						</ul>
	        		</li> **/?>
	        		{{-- @endpermission --}}
	        		@permission('view_settings')
	          		<li><a class="dropdown-item" href="{{url('/') . '/profile/settings'}}">SETTINGS</a></li>
	          		@endpermission
	        		<li><a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT</a></li>
	        		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                		{{ csrf_field() }}
            		</form>
	        	</ul>

			</li>
			@endauth
		</ul>
  </div>
</nav>
{{-- End of navbar --}}