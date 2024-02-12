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
				<div class="row mt-2">
					<div class="col-10">
						<hr>
					</div>
					<div class="col-2 appendable">
						<a href="{{url('/') . '/profile'}}" class="text-black-50">Cancel Update</a>
					</div>
				</div>
				<div class="content mt-3">
					{!! Form::open(['method' => 'PATCH', 'route' => ['profile.update', $user_data->user_id], 'name' => 'usersupdateform']) !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <p><span class="required_field">*</span> Required fields</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    {{-- User country --}}
                                    <?php $country = str_replace(' ', '', $user_data->country); ?>

                                    {{-- User affiliation --}}
                                    <?php $user_affiliation2 = ($user_affiliation != '') ? $user_affiliation->affiliation_id : ''; ?>

                                    {{-- User philrice station --}}
                                    <?php $philrice_station_id = ($user_affiliation != '') ? $user_affiliation->philrice_station_id : ''; ?>

                                    <input type="hidden" name="old_firstname" value="{{$user_data->firstname}}">
                                    <input type="hidden" name="old_middlename" value="{{$user_data->middlename}}">
                                    <input type="hidden" name="old_lastname" value="{{$user_data->lastname}}">
                                    <input type="hidden" name="old_extname" value="{{$user_data->extname}}">
                                    <input type="hidden" name="old_username" value="{{$user_data->username}}">
                                    <input type="hidden" name="old_email" value="{{$user_data->email}}">
                                    <input type="hidden" name="old_secondaryemail" value="{{$user_data->secondaryemail}}">
                                    <input type="hidden" name="old_birthday" value="{{$user_data->birthday}}">
                                    <input type="hidden" name="old_sex" value="{{$user_data->sex}}">
                                    <input type="hidden" name="old_contact_no" value="{{$user_data->contact_no}}">
                                    <input type="hidden" name="old_country" value="{{$country}}">
                                    <input type="hidden" name="old_region" value="{{$user_data->region}}">
                                    <input type="hidden" name="old_province" value="{{$user_data->province}}">
                                    <input type="hidden" name="old_municipality" value="{{$user_data->municipality}}">
                                    <input type="hidden" name="old_barangay" value="{{$user_data->barangay}}">
                                    <input type="hidden" name="old_designation" value="{{$user_data->designation}}">
                                    <input type="hidden" name="old_affiliation" value="{{$user_affiliation2}}">
                                    <input type="hidden" name="old_station" value="{{$philrice_station_id}}">
                                    <input type="hidden" name="old_philrice_idno" value="{{$user_data->philrice_idno}}">
                                    <input type="hidden" name="old_fullname" value="{{$user_data->fullname}}">
                                    <input type="hidden" name="old_coop" value="{{$user_data->cooperative}}">
                                    <input type="hidden" name="old_agency" value="{{$user_data->agency}}">
                                    <input type="hidden" name="old_school" value="{{$user_data->school}}">

                                    <div class="form-group">
                                        <label for="firstname"><span class="required_field">*</span> First Name</label>
                                        <input type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" name="firstname" value="{{$user_data->firstname}}">
                                        @if ($errors->has('firstname'))
                                        <span class="error invalid-feedback">{{$errors->first('firstname')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" class="form-control{{ $errors->has('middlename') ? ' is-invalid' : '' }}" name="middlename" value="{{$user_data->middlename}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="lastname"><span class="required_field">*</span> Last Name</label>
                                        <input type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" name="lastname" value="{{$user_data->lastname}}">
                                        @if ($errors->has('lastname'))
                                        <span class="error invalid-feedback">{{$errors->first('lastname')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="extname">Extension Name</label>
                                        <input type="text" class="form-control{{ $errors->has('extname') ? ' is-invalid' : '' }} col-lg-6" name="extname" value="{{$user_data->extname}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="username"><span class="required_field">*</span> Username</label>
                                        <input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{$user_data->username}}">
                                        @if ($errors->has('username'))
                                        <span class="error invalid-feedback">{{$errors->first('username')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="email"><span class="required_field">*</span> E-mail Address</label>
                                        <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$user_data->email}}">
                                        @if ($errors->has('email'))
                                        <span class="error invalid-feedback">{{$errors->first('email')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="secondaryemail">Alternate E-mail Address</label>
                                        <input type="text" class="form-control {{ $errors->has('secondaryemail') ? ' is-invalid' : '' }}" name="secondaryemail" value="{{$user_data->secondaryemail}}">
                                        @if ($errors->has('secondaryemail'))
                                        <span class="error invalid-feedback">{{$errors->first('secondaryemail')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="birthday">Birthday</label>
                                        <input type="text" class="form-control birthday {{ $errors->has('birthday') ? ' is-invalid' : '' }}" name="birthday" value="{{$user_data->birthday}}" readonly="readonly">
                                        @if ($errors->has('birthday'))
                                        <span class="error invalid-feedback" style="{{$errors->first('birthday') ? 'display: block' : ''}}">{{$errors->first('birthday')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="sex"><span class="required_field">*</span> Sex</label>
                                        <div class="form-check">
                                            <input type="radio" name="sex" value="Male" class="form-check-input" {{($user_data->sex == "Male") ? 'checked' : ''}}>
                                            <label class="form-check-label">Male</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="sex" value="Female" class="form-check-input" {{($user_data->sex == "Female") ? 'checked' : ''}}>
                                            <label class="form-check-label">Female</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_no">Contact No.</label>
                                        <input type="text" class="form-control input_mask {{ $errors->has('contact_no') ? ' is-invalid' : '' }}" name="contact_no" value="{{$user_data->contact_no}}" data-inputmask="'mask': '9999-999-9999'">
                                        @if ($errors->has('contact_no'))
                                        <span class="error invalid-feedback">{{$errors->first('contact_no')}}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="country"><span class="required_field">*</span> Country</label>
                                        <select name="country" id="country" class="form-control {{$errors->has('country') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Country</option>
                                            @foreach($countries as $key => $value)
                                                <option value="{{$key}}" {{$country == $key ? 'selected' : ''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="error invalid-feedback">{{$errors->first('country')}}</span>
                                        @endif
                                    </div>

                                    <input type="hidden" name="region" id="region" value="{{$user_data->region}}">
                                    
                                    <div class="form-group" id="province_input" style="display: {{$errors->has('province')||$country == "PH" ? 'block' : 'none'}};">
                                        <label for="province"><span class="required_field">*</span> Province</label>
                                        <select name="province" id="province" class="form-control {{$errors->has('province') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Province</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->prov_code}}" region_id="{{$province->region_id}}" province_id="{{$province->province_id}}" {{$user_data->province == $province->prov_code ? 'selected' : ''}}>{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province'))
                                            <span class="error invalid-feedback">{{$errors->first('province')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="municipality_input" style="display: {{$errors->has('municipality')||$country == "PH" ? 'block' : 'none'}};">
                                        <label for="municipality"><span class="required_field">*</span> Municipality</label>
                                        <select name="municipality" id="municipality" class="form-control {{$errors->has('municipality') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Municipality</option>
                                            @if($municipalities != '')
                                                @foreach($municipalities as $municipality)
                                                    <option value="{{$municipality->mun_code}}" {{$user_data->municipality == $municipality->mun_code ? 'selected' : ''}}>{{$municipality->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('municipality'))
                                            <span class="error invalid-feedback">{{$errors->first('municipality')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="barangay_input" style="display: {{$errors->has('barangay')||$country == "PH" ? 'block' : 'none'}};">
                                        <label for="barangay">*Barangay</label>
                                        <input type="text" class="form-control {{$errors->has('barangay') ? 'is-invalid' : ''}}" name="barangay" value="{{$user_data->barangay}}" placeholder="Enter your barangay">
                                        @if ($errors->has('barangay'))
                                            <span class="error invalid-feedback">{{$errors->first('barangay')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="affiliation"><span class="required_field">*</span> Affiliation</label>
                                        <select name="affiliation" id="affiliation" class="form-control {{$errors->has('affiliation') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Affiliation</option>
                                            @foreach($affiliations as $affiliation)
                                                <option value="{{$affiliation->affiliation_id}}" {{$user_affiliation2 == $affiliation->affiliation_id ? 'selected' : ''}}>{{$affiliation->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('affiliation'))
                                            <span class="error invalid-feedback">{{$errors->first('affiliation')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" name="designation" value="{{$user_data->designation}}" placeholder="Enter your designation">
                                        @if ($errors->has('designation'))
                                            <span class="error invalid-feedback">{{$errors->first('designation')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="station_input" style="display: {{$errors->has('station')||$user_affiliation2 == 1 ? 'block' : 'none'}};">
                                        <label for="station"><span class="required_field">*</span> PhilRice Station</label>
                                        <select class="form-control {{$errors->has('station') ? 'is-invalid' : ''}}" name="station" id="station">
                                            <option value="0" selected disabled>Select PhilRice station</option>
                                            @foreach($stations as $station)
                                                <option value="{{$station->philrice_station_id}}" {{$philrice_station_id == $station->philrice_station_id ? 'selected' : ''}}>{{$station->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('station'))
                                            <span class="error invalid-feedback">{{$errors->first('station')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="philrice_idno_input" style="display: {{$errors->has('philrice_idno')||$user_affiliation2 == 1  ? 'block' : 'none'}};">
                                        <label for="philrice_idno"><span class="required_field">*</span> PhilRice ID No.</label>
                                        <input type="text" name="philrice_idno" id="philrice_idno" class="form-control input_mask {{$errors->has('philrice_idno') ? 'is-invalid' : ''}}" data-inputmask="'mask': '99-9999'" value="{{$user_data->philrice_idno}}">
                                        @if ($errors->has('philrice_idno'))
                                            <span class="error invalid-feedback">{{$errors->first('philrice_idno')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="coop" style="display: {{$errors->has('coop')||$user_affiliation2 == 3 || $user_affiliation2 == 9 ? 'block' : 'none'}};">
                                        <label for="coop">Cooperative</label>
                                        <input type="text" class="form-control" name="coop" value="{{$user_data->cooperative}}" placeholder="Enter your cooperative">
                                        @if ($errors->has('coop'))
                                            <span class="error invalid-feedback">{{$errors->first('coop')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="agency" style="display: {{$errors->has('agency')||$user_affiliation2 == 6 ? 'block' : 'none'}};">
                                        <label for="agency">Agency</label>
                                        <input type="text" class="form-control" name="agency" value="{{$user_data->agency}}" placeholder="Enter your agency">
                                        @if ($errors->has('agency'))
                                            <span class="error invalid-feedback">{{$errors->first('agency')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="school" style="display: {{$errors->has('school')||$user_affiliation2 == 5 ? 'block' : 'none'}};">
                                        <label for="school">School</label>
                                        <input type="text" class="form-control" name="school" value="{{$user_data->school}}" placeholder="Enter your school">
                                        @if ($errors->has('school'))
                                            <span class="error invalid-feedback">{{$errors->first('school')}}</span>
                                        @endif
                                    </div>

                                    <button type="submit" name="save" class="btn btn-success" style="float: right; margin-top: 30px;"><i class="fa fa-check"></i> Save Changes</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
				</div>
			</div>
		</div>

        <div class="row mb-4">
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
                <div class="row mt-2">
                    <div class="col-10">
                        <hr>
                    </div>
                    <div class="col-2 appendable">
                        <a href="{{url('/') . '/profile'}}" class="text-black-50">Cancel Update</a>
                    </div>
                </div>
                <div class="content mt-3">
                    {!! Form::open(['method' => 'PATCH', 'route' => ['profile.update', $user_data->user_id], 'name' => 'usersupdateform']) !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <p><span class="required_field">*</span> Required fields</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    {{-- User country --}}
                                    <?php $country = str_replace(' ', '', $user_data->country); ?>

                                    {{-- User affiliation --}}
                                    <?php $user_affiliation2 = ($user_affiliation != '') ? $user_affiliation->affiliation_id : ''; ?>

                                    {{-- User philrice station --}}
                                    <?php $philrice_station_id = ($user_affiliation != '') ? $user_affiliation->philrice_station_id : ''; ?>

                                    <input type="hidden" name="old_firstname" value="{{$user_data->firstname}}">
                                    <input type="hidden" name="old_middlename" value="{{$user_data->middlename}}">
                                    <input type="hidden" name="old_lastname" value="{{$user_data->lastname}}">
                                    <input type="hidden" name="old_extname" value="{{$user_data->extname}}">
                                    <input type="hidden" name="old_username" value="{{$user_data->username}}">
                                    <input type="hidden" name="old_email" value="{{$user_data->email}}">
                                    <input type="hidden" name="old_secondaryemail" value="{{$user_data->secondaryemail}}">
                                    <input type="hidden" name="old_birthday" value="{{$user_data->birthday}}">
                                    <input type="hidden" name="old_sex" value="{{$user_data->sex}}">
                                    <input type="hidden" name="old_contact_no" value="{{$user_data->contact_no}}">
                                    <input type="hidden" name="old_country" value="{{$country}}">
                                    <input type="hidden" name="old_region" value="{{$user_data->region}}">
                                    <input type="hidden" name="old_province" value="{{$user_data->province}}">
                                    <input type="hidden" name="old_municipality" value="{{$user_data->municipality}}">
                                    <input type="hidden" name="old_barangay" value="{{$user_data->barangay}}">
                                    <input type="hidden" name="old_designation" value="{{$user_data->designation}}">
                                    <input type="hidden" name="old_affiliation" value="{{$user_affiliation2}}">
                                    <input type="hidden" name="old_station" value="{{$philrice_station_id}}">
                                    <input type="hidden" name="old_philrice_idno" value="{{$user_data->philrice_idno}}">
                                    <input type="hidden" name="old_fullname" value="{{$user_data->fullname}}">
                                    <input type="hidden" name="old_coop" value="{{$user_data->cooperative}}">
                                    <input type="hidden" name="old_agency" value="{{$user_data->agency}}">
                                    <input type="hidden" name="old_school" value="{{$user_data->school}}">

                                    <div class="form-group">
                                        <label for="firstname"><span class="required_field">*</span> First Name</label>
                                        <input type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" name="firstname" value="{{$user_data->firstname}}">
                                        @if ($errors->has('firstname'))
                                        <span class="error invalid-feedback">{{$errors->first('firstname')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" class="form-control{{ $errors->has('middlename') ? ' is-invalid' : '' }}" name="middlename" value="{{$user_data->middlename}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="lastname"><span class="required_field">*</span> Last Name</label>
                                        <input type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" name="lastname" value="{{$user_data->lastname}}">
                                        @if ($errors->has('lastname'))
                                        <span class="error invalid-feedback">{{$errors->first('lastname')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="extname">Extension Name</label>
                                        <input type="text" class="form-control{{ $errors->has('extname') ? ' is-invalid' : '' }} col-lg-6" name="extname" value="{{$user_data->extname}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="username"><span class="required_field">*</span> Username</label>
                                        <input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{$user_data->username}}">
                                        @if ($errors->has('username'))
                                        <span class="error invalid-feedback">{{$errors->first('username')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="email"><span class="required_field">*</span> E-mail Address</label>
                                        <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$user_data->email}}">
                                        @if ($errors->has('email'))
                                        <span class="error invalid-feedback">{{$errors->first('email')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="secondaryemail">Alternate E-mail Address</label>
                                        <input type="text" class="form-control {{ $errors->has('secondaryemail') ? ' is-invalid' : '' }}" name="secondaryemail" value="{{$user_data->secondaryemail}}">
                                        @if ($errors->has('secondaryemail'))
                                        <span class="error invalid-feedback">{{$errors->first('secondaryemail')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="birthday">Birthday</label>
                                        <input type="text" class="form-control birthday {{ $errors->has('birthday') ? ' is-invalid' : '' }}" name="birthday" value="{{$user_data->birthday}}" readonly="readonly">
                                        @if ($errors->has('birthday'))
                                        <span class="error invalid-feedback" style="{{$errors->first('birthday') ? 'display: block' : ''}}">{{$errors->first('birthday')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="sex"><span class="required_field">*</span> Sex</label>
                                        <div class="form-check">
                                            <input type="radio" name="sex" value="Male" class="form-check-input" {{($user_data->sex == "Male") ? 'checked' : ''}}>
                                            <label class="form-check-label">Male</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="sex" value="Female" class="form-check-input" {{($user_data->sex == "Female") ? 'checked' : ''}}>
                                            <label class="form-check-label">Female</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_no">Contact No.</label>
                                        <input type="text" class="form-control input_mask {{ $errors->has('contact_no') ? ' is-invalid' : '' }}" name="contact_no" value="{{$user_data->contact_no}}" data-inputmask="'mask': '9999-999-9999'">
                                        @if ($errors->has('contact_no'))
                                        <span class="error invalid-feedback">{{$errors->first('contact_no')}}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="country"><span class="required_field">*</span> Country</label>
                                        <select name="country" id="country" class="form-control {{$errors->has('country') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Country</option>
                                            @foreach($countries as $key => $value)
                                                <option value="{{$key}}" {{$country == $key ? 'selected' : ''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="error invalid-feedback">{{$errors->first('country')}}</span>
                                        @endif
                                    </div>

                                    <input type="hidden" name="region" id="region" value="{{$user_data->region}}">
                                    
                                    <div class="form-group" id="province_input" style="display: {{$errors->has('province')||$country == "PH" ? 'block' : 'none'}};">
                                        <label for="province"><span class="required_field">*</span> Province</label>
                                        <select name="province" id="province" class="form-control {{$errors->has('province') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Province</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->prov_code}}" region_id="{{$province->region_id}}" province_id="{{$province->province_id}}" {{$user_data->province == $province->prov_code ? 'selected' : ''}}>{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province'))
                                            <span class="error invalid-feedback">{{$errors->first('province')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="municipality_input" style="display: {{$errors->has('municipality')||$country == "PH" ? 'block' : 'none'}};">
                                        <label for="municipality"><span class="required_field">*</span> Municipality</label>
                                        <select name="municipality" id="municipality" class="form-control {{$errors->has('municipality') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Municipality</option>
                                            @if($municipalities != '')
                                                @foreach($municipalities as $municipality)
                                                    <option value="{{$municipality->mun_code}}" {{$user_data->municipality == $municipality->mun_code ? 'selected' : ''}}>{{$municipality->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('municipality'))
                                            <span class="error invalid-feedback">{{$errors->first('municipality')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="barangay_input" style="display: {{$errors->has('barangay')||$country == "PH" ? 'block' : 'none'}};">
                                        <label for="barangay">*Barangay</label>
                                        <input type="text" class="form-control {{$errors->has('barangay') ? 'is-invalid' : ''}}" name="barangay" value="{{$user_data->barangay}}" placeholder="Enter your barangay">
                                        @if ($errors->has('barangay'))
                                            <span class="error invalid-feedback">{{$errors->first('barangay')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="affiliation"><span class="required_field">*</span> Affiliation</label>
                                        <select name="affiliation" id="affiliation" class="form-control {{$errors->has('affiliation') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Select Affiliation</option>
                                            @foreach($affiliations as $affiliation)
                                                <option value="{{$affiliation->affiliation_id}}" {{$user_affiliation2 == $affiliation->affiliation_id ? 'selected' : ''}}>{{$affiliation->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('affiliation'))
                                            <span class="error invalid-feedback">{{$errors->first('affiliation')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" name="designation" value="{{$user_data->designation}}" placeholder="Enter your designation">
                                        @if ($errors->has('designation'))
                                            <span class="error invalid-feedback">{{$errors->first('designation')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="station_input" style="display: {{$errors->has('station')||$user_affiliation2 == 1 ? 'block' : 'none'}};">
                                        <label for="station"><span class="required_field">*</span> PhilRice Station</label>
                                        <select class="form-control {{$errors->has('station') ? 'is-invalid' : ''}}" name="station" id="station">
                                            <option value="0" selected disabled>Select PhilRice station</option>
                                            @foreach($stations as $station)
                                                <option value="{{$station->philrice_station_id}}" {{$philrice_station_id == $station->philrice_station_id ? 'selected' : ''}}>{{$station->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('station'))
                                            <span class="error invalid-feedback">{{$errors->first('station')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="philrice_idno_input" style="display: {{$errors->has('philrice_idno')||$user_affiliation2 == 1  ? 'block' : 'none'}};">
                                        <label for="philrice_idno"><span class="required_field">*</span> PhilRice ID No.</label>
                                        <input type="text" name="philrice_idno" id="philrice_idno" class="form-control input_mask {{$errors->has('philrice_idno') ? 'is-invalid' : ''}}" data-inputmask="'mask': '99-9999'" value="{{$user_data->philrice_idno}}">
                                        @if ($errors->has('philrice_idno'))
                                            <span class="error invalid-feedback">{{$errors->first('philrice_idno')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="coop" style="display: {{$errors->has('coop')||$user_affiliation2 == 3 || $user_affiliation2 == 9 ? 'block' : 'none'}};">
                                        <label for="coop">Cooperative</label>
                                        <input type="text" class="form-control" name="coop" value="{{$user_data->cooperative}}" placeholder="Enter your cooperative">
                                        @if ($errors->has('coop'))
                                            <span class="error invalid-feedback">{{$errors->first('coop')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="agency" style="display: {{$errors->has('agency')||$user_affiliation2 == 6 ? 'block' : 'none'}};">
                                        <label for="agency">Agency</label>
                                        <input type="text" class="form-control" name="agency" value="{{$user_data->agency}}" placeholder="Enter your agency">
                                        @if ($errors->has('agency'))
                                            <span class="error invalid-feedback">{{$errors->first('agency')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="school" style="display: {{$errors->has('school')||$user_affiliation2 == 5 ? 'block' : 'none'}};">
                                        <label for="school">School</label>
                                        <input type="text" class="form-control" name="school" value="{{$user_data->school}}" placeholder="Enter your school">
                                        @if ($errors->has('school'))
                                            <span class="error invalid-feedback">{{$errors->first('school')}}</span>
                                        @endif
                                    </div>

                                    <button type="submit" name="save" class="btn btn-success" style="float: right; margin-top: 30px;"><i class="fa fa-check"></i> Save Changes</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
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