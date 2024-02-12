@extends('landing_page_layouts.index')

@section('content')
	{{-- Header --}}
	<div class="row mb-3">
		<div id="page_header" class="col-12 col_no_padding">
			<div class="row">
				<div class="col-12">
					<nav aria-label="breadcrumb">
					  <ol class="breadcrumb">
					    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
					    <li class="breadcrumb-item"><a href="{{url('/').'/profile'}}">Profile</a></li>
					    <li class="breadcrumb-item active" aria-current="page">Portal</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>Portal</strong></h1>
				</div>
			</div>
		</div>
	</div>
	{{-- End of header --}}
	{{-- Content --}}
	<div id="padding_content"></div>
	<div id="profile_content" class="col_no_padding">
		<div class="row">
			<div class="d-none d-sm-block col-3 col-xl-3 col-md-4 col-sm-12">
				<ul class="list-group">
				  <li class="list-group-item"><a href="{{url('/') . '/profile'}}">PROFILE</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
				  <li class="list-group-item active"><a href="{{url('/') . '/profile/portal'}}">PORTAL</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/analytics'}}">ANALYTICS</a></li>
				  {{-- @permission(['view_seed_sale_logs','view_access_logs','view_action_logs']) --}}
				  <li class="list-group-item"><a href="#activity" class="dropdown-toggle" data-toggle="collapse">ACTIVITY LOG</a>
				  	<div class="list-group collapse" id="activity">
				  		{{-- @permission('view_seed_sale_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/seedsale'}}" class="list-group-item">
					      <p class="ml-3 h4">Purchase Transaction</p>
					    </a>
					    {{-- @endpermission
					    @permission('view_access_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/logaccess'}}" class="list-group-item">
					      <p class="ml-3 h4">Log Access</p>
					    </a>
					    {{-- @endpermission
					    @permission('view_action_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/logaction'}}" class="list-group-item">
					      <p class="ml-3 h4">Action/Update</p>
					    </a>
					    {{-- @endpermission --}}
					  </div>
				 </li>
				 {{-- @endpermission --}}
				  @permission('view_settings')
				  <li class="list-group-item"><a href="{{url('/') . '/profile/settings'}}">SETTINGS</a></li>
				  @endpermission
				  <li class="list-group-item"><a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT</a></li>

				  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                	{{ csrf_field() }}
            	  </form>
				</ul>
			</div>
			<div class="d-none d-sm-block col-9 col-xl-9 col-md-8 col-sm-12">
				<h2 style="color: #246e49;"><strong>SeedTrace Modules</strong></h2>
				<hr>
				<div class="row mb-4">
					@foreach($data as $system)
						@if($system['display_name'] != 'RSIS Portal' && $system['group'] == 1)
							<div class="col-6 mb-4">
								<div class="card system_card">
									@if($system['name'] == "warehouse" || $system['name'] == "seed_grow" || $system['name'] == "geotag_web" || $system['name'] == "seed_trace" || $system['name'] == "marketplace/admin")
										<a href="{{$system['url']}}" class="card-link" target="_blank" style="float: right;">
									@else
										<a href="{{$system['url']}}" class="card-link" style="float: right;">
									@endif
										<div class="card-body">
											<h4 class="card-title system_name">{{$system['display_name']}}</h4>
											<i class="fa fa-arrow-right float-right mb-4"></i>
										</div>
									</a>
								</div>
							</div>
						@endif
					@endforeach
				</div>

				<h2 style="color: #246e49;"><strong>Admin Modules</strong></h2>
				<hr>
				<div class="row mb-4">
					@foreach($data as $system)
						@if($system['display_name'] != 'RSIS Portal' && $system['group'] == 2)
							<div class="col-6 mb-4">
								<div class="card system_card">
									@if($system['name'] == "warehouse" || $system['name'] == "seed_grow" || $system['name'] == "geotag_web" || $system['name'] == "seed_trace")
										<a href="{{$system['url']}}" class="card-link" target="_blank" style="float: right;">
									@else
										<a href="{{$system['url']}}" class="card-link" style="float: right;">
									@endif
										<div class="card-body">
											<h4 class="card-title system_name">{{$system['display_name']}}</h4>
											<i class="fa fa-arrow-right float-right mb-4"></i>
										</div>
									</a>
								</div>
							</div>
						@endif
					@endforeach
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="d-block d-sm-none col-xs-12">
				<ul class="list-group list-group-horizontal">
				  <li class="list-group-item"><a href="{{url('/') . '/profile'}}">PROFILE</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
				  <li class="list-group-item active"><a href="{{url('/') . '/profile/portal'}}">PORTAL</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/analytics'}}">ANALYTICS</a></li>
				  {{-- @permission(['view_seed_sale_logs','view_access_logs','view_action_logs']) --}}
				  <li class="list-group-item"><a href="#activity" class="dropdown-toggle" data-toggle="collapse">ACTIVITY LOG</a>
				  	<div class="list-group collapse" id="activity">
				  		{{-- @permission('view_seed_sale_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/seedsale'}}" class="list-group-item">
					      <p class="ml-3 h4">Purchase Transaction</p>
					    </a>
					    {{-- @endpermission
					    @permission('view_access_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/logaccess'}}" class="list-group-item">
					      <p class="ml-3 h4">Log Access</p>
					    </a>
					    {{-- @endpermission
					    @permission('view_action_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/logaction'}}" class="list-group-item">
					      <p class="ml-3 h4">Action/Update</p>
					    </a>
					    {{-- @endpermission --}}
					  </div>
				 </li>
				 {{-- @endpermission --}}
				  @permission('view_settings')
				  <li class="list-group-item"><a href="{{url('/') . '/profile/settings'}}">SETTINGS</a></li>
				  @endpermission
				  <li class="list-group-item"><a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT</a></li>

				  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                	{{ csrf_field() }}
            	  </form>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="d-block d-sm-none col-xs-12">
				<h2 style="color: #246e49;"><strong>SeedTrace Modules</strong></h2>
				<hr>
				<div class="row mb-4">
					@foreach($data as $system)
						@if($system['display_name'] != 'RSIS Portal' && $system['group'] == 1)
							<div class="col-6 mb-4">
								<div class="card system_card">
									@if($system['name'] == "warehouse" || $system['name'] == "seed_grow" || $system['name'] == "geotag_web" || $system['name'] == "seed_trace")
										<a href="{{$system['url']}}" class="card-link" target="_blank" style="float: right;">
									@else
										<a href="{{$system['url']}}" class="card-link" style="float: right;">
									@endif
										<div class="card-body">
											<h4 class="card-title system_name">{{$system['display_name']}}</h4>
											<i class="fa fa-arrow-right float-right mb-4"></i>
										</div>
									</a>
								</div>
							</div>
						@endif
					@endforeach
				</div>

				<h2 style="color: #246e49;"><strong>Admin Modules</strong></h2>
				<hr>
				<div class="row mb-4">
					@foreach($data as $system)
						@if($system['display_name'] != 'RSIS Portal' && $system['group'] == 2)
							<div class="col-6 mb-4">
								<div class="card system_card">
									@if($system['name'] == "warehouse" || $system['name'] == "seed_grow" || $system['name'] == "geotag_web" || $system['name'] == "seed_trace")
										<a href="{{$system['url']}}" class="card-link" target="_blank" style="float: right;">
									@else
										<a href="{{$system['url']}}" class="card-link" style="float: right;">
									@endif
										<div class="card-body">
											<h4 class="card-title system_name">{{$system['display_name']}}</h4>
											<i class="fa fa-arrow-right float-right mb-4"></i>
										</div>
									</a>
								</div>
							</div>
						@endif
					@endforeach
				</div>
			</div>
		</div>
	</div>
	{{-- End of content --}}
	{{-- Bottom --}}
	<div class="row">
		<div class="col-12 col_no_padding">
			<img src="{{url("/").'/public/images/bottom.png'}}" alt="" class="img-responsive" style="width: 100%;">
		</div>
	</div>
	{{-- End of bottom --}}
@endsection