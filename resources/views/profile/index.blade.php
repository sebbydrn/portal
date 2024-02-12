@extends('landing_page_layouts.index')
@push('styles')
	<link rel="stylesheet" type="text/css" href="{{asset('public/assets/Cropper.js/cropper.min.css')}}">
@endpush
@section('content')
	{{-- Header --}}
	<div class="row mb-3">
		<div id="page_header" class="col-12 col_no_padding">
			<div class="row">
				<div class="col-12">
					<nav aria-label="breadcrumb">
					  <ol class="breadcrumb">
					    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
					    <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
				  <li class="list-group-item active"><a href="{{url('/') . '/profile'}}">PROFILE</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
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
				@if($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{$message}}
                    </div>
                @endif

                @if($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h5><i class="icon fas fa-ban"></i> Oops!</h5>
                        {{$message}}
                    </div>
                @endif

				<div class="content mt-4">
					<div class="row">
						<div class="col-xl-3 col-md-12 col-sm-12 text-center">
							<div class="image text-center">
								<?php $name = Auth::user()->firstname . ' ' . Auth::user()->lastname; ?>
								@if($avatar == null)
									<img id="profilepic" data-id="none" src="{{ Avatar::create($name)->toBase64() }}">
								@else
									<img src="{{ $avatar->image_name }}" width="200" id="profilepic" data-id="{{$avatar->profile_pic_id}}" />
								@endif
							</div>
							<label for="uploadImage">
								<p class="small text-black-50 text-center" id="updatePicture">Upload Photo</p>
								<input type="file" name="image" class="image" id="uploadImage" style="display:none" />
							</label>
							
						</div>

						<div class="col-xl-8 col-md-12 col-sm-12">
							<div class="row">
								<div class="col-8"><p class="h4">{{$user->fullname}}</p></div>
								<div class="col-4 "> <a class="btn btn-success float-right" href="{{route('profile.edit', Auth::id())}}">Edit Profile</a></div>
							</div>
							<div class="row"></div>
							<p class="h6">{{$user->designation}}</p>
							<p class="h6">{{$user->affiliation_name}}</p>
							<hr>
							<p class="h4 text-black-50 mb-3">Basic Information</p>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Birthday:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->birthday}}</p>
								</div>
								
							</div>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Sex:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->sex}}</p>
								</div>

							</div>
							@if($user->philrice_idno != null)
								<div class="row">
									<div class="col-xl-3 col-md-4 col-sm-12">
										<p class="h5">PhilRice ID No:</p>
									</div>
									<div class="col-xl-9 col-md-8 col-sm-12">
										<p class="h5">{{$user->philrice_idno}}</p>
									</div>
								</div>
							@endif

							<p class="h4 text-black-50 mb-3">Contact Information</p>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Phone No:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->contact_no}}</p>
								</div>
								
							</div>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Username:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->username}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Email:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->email}}</p>
								</div>
							</div>
							@if($user->secondaryemail != null)
							<div class="row">
								<div class="col-xl-4 col-md-4 col-sm-12">
									<p class="h5">Secondary Email:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->secondaryemail}}</p>
								</div>
							</div>
							@endif
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Address:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">
										@if($user->barangay != null)
											{{$user->barangay.', '}}
										@endif

										@if($user->municipality != null)
											{{$municipality->name.', '}}
										@endif

										@if($user->province != null)
										{{$province->name.', '}}
										@endif

										@if($user->country != null)
										{{$user->country}}
										@endif
									</p>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="content mt-3">
					
				</div>
			</div>
		</div>

		<div class="row">
			<div class="d-block d-sm-none col-xs-12">

				<ul class="list-group list-group-horizontal">
				  <li class="list-group-item active"><a href="{{url('/') . '/profile'}}">PROFILE</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
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
				@if($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{$message}}
                    </div>
                @endif

                @if($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h5><i class="icon fas fa-ban"></i> Oops!</h5>
                        {{$message}}
                    </div>
                @endif

				<div class="content mt-4">
					<div class="row">
						<div class="col-xl-3 col-md-12 col-sm-12 text-center">
							<div class="image text-center">
								<?php $name = Auth::user()->firstname . ' ' . Auth::user()->lastname; ?>
								@if($avatar == null)
									<img id="profilepic" data-id="none" src="{{ Avatar::create($name)->toBase64() }}">
								@else
									<img src="{{ $avatar->image_name }}" width="200" id="profilepic" data-id="{{$avatar->profile_pic_id}}" />
								@endif
							</div>
							<label for="uploadImage">
								<p class="small text-black-50 text-center" id="updatePicture">Upload Photo</p>
								<input type="file" name="image" class="image" id="uploadImage" style="display:none" />
							</label>
							
						</div>

						<div class="col-xl-8 col-md-12 col-sm-12">
							<div class="row">
								<div class="col-8"><p class="h4">{{$user->fullname}}</p></div>
								<div class="col-4 "> <a class="btn btn-success float-right" href="{{route('profile.edit', Auth::id())}}">Edit Profile</a></div>
							</div>
							<div class="row"></div>
							<p class="h6">{{$user->designation}}</p>
							<p class="h6">{{$user->affiliation_name}}</p>
							<hr>
							<p class="h4 text-black-50 mb-3">Basic Information</p>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Birthday:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->birthday}}</p>
								</div>
								
							</div>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Sex:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->sex}}</p>
								</div>

							</div>
							@if($user->philrice_idno != null)
								<div class="row">
									<div class="col-xl-3 col-md-4 col-sm-12">
										<p class="h5">PhilRice ID No:</p>
									</div>
									<div class="col-xl-9 col-md-8 col-sm-12">
										<p class="h5">{{$user->philrice_idno}}</p>
									</div>
								</div>
							@endif

							<p class="h4 text-black-50 mb-3">Contact Information</p>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Phone No:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->contact_no}}</p>
								</div>
								
							</div>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Username:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->username}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Email:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->email}}</p>
								</div>
							</div>
							@if($user->secondaryemail != null)
							<div class="row">
								<div class="col-xl-4 col-md-4 col-sm-12">
									<p class="h5">Secondary Email:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">{{$user->secondaryemail}}</p>
								</div>
							</div>
							@endif
							<div class="row">
								<div class="col-xl-3 col-md-4 col-sm-12">
									<p class="h5">Address:</p>
								</div>
								<div class="col-xl-9 col-md-8 col-sm-12">
									<p class="h5">
										@if($user->barangay != null)
											{{$user->barangay.', '}}
										@endif

										@if($user->municipality != null)
											{{$municipality->name.', '}}
										@endif

										@if($user->province != null)
										{{$province->name.', '}}
										@endif

										@if($user->country != null)
										{{$user->country}}
										@endif
									</p>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="content mt-3">
					
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

	<!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crop Image Before Upload</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="img-container">
						<div class="row">
							<div class="col-md-8">
								<img src="" id="sample_image" width="500" height="500"/>
							</div>
							<div class="col-md-4">
								<div class="preview"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="crop" class="btn btn-primary">Crop</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('public/assets/Cropper.js/cropper.min.js')}}"></script>
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