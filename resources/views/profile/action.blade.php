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
					    <li class="breadcrumb-item active" aria-current="page">Activity Log</li>
					    <li class="breadcrumb-item active" aria-current="page">Log Access</li>
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
	<div id="profile_content" class="col_no_padding">
		<div class="row">
			<div class="col-3 profile_sidebar">
				<ul class="list-group">
				  <li class="list-group-item "><a href="{{url('/') . '/profile'}}">PROFILE</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/password'}}">PASSWORD</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/portal'}}">PORTAL</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/profile/analytics'}}">ANALYTICS</a></li>
				  {{-- @permission(['view_seed_sale_logs','view_access_logs','view_action_logs']) --}}
				  <li class="list-group-item"><a href="#activity" class="dropdown-toggle" data-toggle="collapse">ACTIVITY LOG</a>
				  	<div class="list-group collapsed" id="activity">
				  		{{-- @permission('view_seed_sale_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/seedsale'}}" class="list-group-item">
					      <p class="ml-3 h4">Purchase Transaction</p>
					    </a>
					    {{-- @endpermission
					    @permission('view_access_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/logaccess'}}" class="list-group-item ">
					      <p class="ml-3 h4">Log Access</p>
					    </a>
					    {{-- @endpermission
					    @permission('view_action_logs') --}}
					    <a href="{{url('/') . '/profile/activity_log/logaction'}}" class="list-group-item active">
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
			<div class="col-9 col-xl-9 col-md-8 col-sm-12">
				<section class="content">
			        <div class="container-fluid">
			            <div class="row">
			                <div class="col-md-12 col-sm-12 col-xs-12">
			                    <div class=" card card-primary">
			                        <div class="card-header">
			                            <div class="row" id="table_filters">
			                            	@permission(['view_national_data'])
				                            	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
				                                    <label>Region</label>
				                                    <select name="region" id="region" class="form-control">
			                                            <option value="0" selected>Region</option>
			                                            @foreach ($regions as $region)
			                                            	<option value="{{$region->region_id}}">{{$region->name}}</option>
			                                            @endforeach
			                                        </select>
				                                </div>
				                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
				                                    <label>Province</label>
				                                    <select name="province" id="province" class="form-control">
			                                            <option value="0" selected>Province</option>
			                                            
			                                        </select>
				                                </div>
				                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
				                                    <label>Municipality</label>
				                                    <select name="municipality" id="municipality" class="form-control">
			                                            <option value="0" selected>Municipality</option>
			                                        </select>
				                                </div>
			                            	@endpermission

			                            	@permission(['view_regional_data'])
			                            		<input type="hidden" id="region" value="0">
				                            	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
				                                    <label>Province</label>
				                                    <select name="province" id="province" class="form-control">
			                                            <option value="0" selected>Province</option>
			                                            @foreach ($provinces as $province)
			                                            	<option value="{{$province->province_id}}">{{$province->name}}</option>
			                                            @endforeach
			                                        </select>
				                                </div>
				                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
				                                    <label>Municipality</label>
				                                    <select name="municipality" id="municipality" class="form-control">
			                                            <option value="0" selected>Municipality</option>
			                                        </select>
				                                </div>
			                            	@endpermission

			                            	@permission(['view_provincial_data'])
			                            		<input type="hidden" id="region" value="0">
			                            		<input type="hidden" id="province" value="0">
				                            	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
				                                    <label>Municipality</label>
				                                    <select name="municipality" id="municipality" class="form-control">
			                                            <option value="0" selected>Municipality</option>
			                                            @foreach ($municipalities as $municipality)
			                                            	<option value="{{$municipality->municipality_id}}">{{$municipality->name}}</option>
			                                            @endforeach
			                                        </select>
				                                </div>
			                            	@endpermission
			                            	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
			                                    <label>Activity</label>
			                                    <select class="form-control" id="dropdownActivity">
			                                    	<option value="none" selected>Select Activity</option>
			                                        @foreach($activities as $activity)
			                                            <option value="{{$activity}}">{{$activity}}</option>
			                                        @endforeach
			                                    </select>
			                                </div>

			                            	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
			                                    <label>Date From</label>
			                                    <input type="text" class="form-control date_from" readonly>
			                                </div>
			                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
			                                    <label>Date To</label>
			                                    <input type="text" class="form-control date_to"  readonly>
			                                </div>
			                                <div class="col-md-12 mt-1">
			                                    <button type="button" class="btn btn-primary filter" data-id="filter_logaction">
			                                        <i class="fa fa-filter"></i> Filter
			                                    </button>
			                                    <button type="button" class="btn btn-secondary" id="reset">
			                                        Reset Dates
			                                    </button>
			                                    <button id="exportLogAction" class="btn btn-success float-right">Export to CSV</button>

			                                </div>
			                            </div>
			                        </div>
			                        <div class="card-body">
			                        	<table class="table table-bordered table-striped" id="logaction_table" style="width: 100%;">
			                                <thead>
			                                    <tr>
			                                    	<th>Action</th>
			                                    	@permission(['view_national_data','view_regional_data','view_provincial_data','view_municipal_data'])
			                                    		<th>User</th>
			                                    	@endpermission
			                                    	<th>Updated Value</th>
			                                        <th>Device</th>
			                                        <th>Browser</th>
			                                        <th>IP Address</th>
			                                        <th>Date</th>
			                                    </tr>
			                                </thead>
			                                <tbody>
			                                </tbody>
			                            </table>
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </section>
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