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
					    <li class="breadcrumb-item active" aria-current="page">Password</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>Welcome! {{strtoupper(Auth::user()->username)}}</strong></h1>
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
				  <li class="list-group-item active"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/portal'}}">PORTAL</a></li>
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
				{!! Form::open(['method' => 'PATCH', 'route' => ['profile.updatePassword'], 'name' => 'usersupdatepassword']) !!}
				<input type="hidden" name="user_id" value="{{$user_id}}">
				<div class="content">
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<p class="h5">Current Password:</p>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<input type="password" name="old_password" id="old_password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}">
								@if ($errors->has('old_password'))
                                    <span class="error invalid-feedback">{{$errors->first('old_password')}}</span>
                                @endif
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<p class="h5">New Password:</p>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<input type="password" name="new_password" id="new_password" class="form-control  {{ $errors->has('new_password') ? ' is-invalid' : '' }}">
								@if ($errors->has('new_password'))
                                    <span class="error invalid-feedback">{{$errors->first('new_password')}}</span>
                                @endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<p class="h5">Re-type new Password:</p>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<input type="password" name="password_confirmation" id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
								@if ($errors->has('password_confirmation'))
                                    <span class="error invalid-feedback">{{$errors->first('password_confirmation')}}</span>
                                @endif
							</div>
						</div>
					</div>
					<button type="submit" name="submit" class="btn btn-success" style="float: right; margin-top: 30px;"><i class="fa fa-check"></i> Save New Password</button>
				</div>
			</div>
			{!! Form::close() !!}
		</div>

		<div class="row mb-4">
			<div class="d-block d-sm-none col-xs-12">

				<ul class="list-group list-group-horizontal">
				  <li class="list-group-item"><a href="{{url('/') . '/profile'}}">PROFILE</a></li>
				  <li class="list-group-item active"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/portal'}}">PORTAL</a></li>
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
				{!! Form::open(['method' => 'PATCH', 'route' => ['profile.updatePassword'], 'name' => 'usersupdatepassword']) !!}
				<input type="hidden" name="user_id" value="{{$user_id}}">
				<div class="content">
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<p class="h5">Current Password:</p>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<input type="password" name="old_password" id="old_password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}">
								@if ($errors->has('old_password'))
                                    <span class="error invalid-feedback">{{$errors->first('old_password')}}</span>
                                @endif
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<p class="h5">New Password:</p>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<input type="password" name="new_password" id="new_password" class="form-control  {{ $errors->has('new_password') ? ' is-invalid' : '' }}">
								@if ($errors->has('new_password'))
                                    <span class="error invalid-feedback">{{$errors->first('new_password')}}</span>
                                @endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<p class="h5">Re-type new Password:</p>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<input type="password" name="password_confirmation" id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
								@if ($errors->has('password_confirmation'))
                                    <span class="error invalid-feedback">{{$errors->first('password_confirmation')}}</span>
                                @endif
							</div>
						</div>
					</div>
					<button type="submit" name="submit" class="btn btn-success" style="float: right; margin-top: 30px;"><i class="fa fa-check"></i> Save New Password</button>
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

@push('scripts')
@include('profile.script')
<script>
	function myMap() {
		var mapProp= {
			center: new google.maps.LatLng(15.6714589,120.8909235),
		  	zoom: 13,
		};
		var map = new google.maps.Map(document.getElementById("map"),mapProp);

		var marker = new google.maps.Marker({
		    position: new google.maps.LatLng(15.6714589,120.8909235),
		    map: map
		});
	}
</script>
	
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnZZ3odGZVl8V0ZTfaE_-F_8l2VS7yFyc&callback=myMap"></script>
@endpush