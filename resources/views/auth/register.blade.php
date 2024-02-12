@extends('landing_page_layouts.index')

@section('content')
    {{-- Content --}}
    <div id="padding_content"></div>
    <div id="register_content" class="col_no_padding">
        <div class="row mt-5">
            <div class="d-none d-sm-block col-6 col-xl-6 col-md-10 col-sm-8 mt-3 mx-auto">
                <div class="card">
                    <div class="card-body register-card-body">
                        <p class="login-box-msg text-center">REGISTRATION FORM</p>

                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="First Name" name="firstname" value="{{old('firstname')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('firstname'))
                                            <span class="error invalid-feedback">{{$errors->first('firstname')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Middle Name" name="middlename" value="{{old('middlename')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="Last Name" name="lastname" value="{{old('lastname')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('lastname'))
                                            <span class="error invalid-feedback">{{$errors->first('lastname')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Extension Name" name="extname" value="{{old('extname')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="Username" name="username" value="{{($errors->has('username')) ? '' : old('username')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('username'))
                                            <span class="error invalid-feedback">{{$errors->first('username')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail Address" name="email" value="{{($errors->has('email')) ? '' : old('email')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="error invalid-feedback">{{$errors->first('email')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control birthday {{ $errors->has('birthday') ? ' is-invalid' : '' }}" placeholder="Birthday" name="birthday" value="{{($errors->has('birthday')) ? '' : old('birthday')}}" readonly="readonly">
                                        @if ($errors->has('birthday'))
                                            <span class="error invalid-feedback" style="{{$errors->first('birthday') ? 'display: block' : ''}}">{{$errors->first('birthday')}}</span>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control {{ $errors->has('age') ? ' is-invalid' : '' }}" placeholder="Age" name="age" value="{{old('age')}}" min="1" step="1">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('age'))
                                            <span class="error invalid-feedback">{{$errors->first('age')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="sex" id="sex" class="form-control {{ $errors->has('sex') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Sex</option>
                                            <option value="Male" {{old('sex') == "Male" ? 'selected' : ''}}>Male</option>
                                            <option value="Female" {{old('sex') == "Female" ? 'selected' : ''}}>Female</option>
                                        </select>
                                        @if ($errors->has('sex'))
                                            <span class="error invalid-feedback">{{$errors->first('sex')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="region" id="region" value="{{($errors->has('region')) ? '' : old('region')}}">

                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Country</option>
                                            @foreach($countries as $key => $value)
                                                <option value="{{$key}}" {{old('country') == $key ? 'selected' : ''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="error invalid-feedback">{{$errors->first('country')}}</span>
                                        @endif
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6" id="province_input" style="display: {{$errors->has('province') ? 'block' : 'none'}};"> --}}
                                <div class="col-md-6" id="province_input">
                                    <div class="mb-3">
                                        <select name="province" id="province" class="form-control {{ $errors->has('province') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Province</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->prov_code}}" region_id="{{$province->region_id}}" province_id="{{$province->province_id}}" {{old('province') == $province->prov_code ? 'selected' : ''}}>{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province'))
                                            <span class="error invalid-feedback">{{$errors->first('province')}}</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="col-md-6" id="municipality_input" style="display: {{$errors->has('municipality') ? 'block' : 'none'}};"> --}}
                                <div class="col-md-6" id="municipality_input">
                                    <div class="mb-3">
                                        <select name="municipality" id="municipality" class="form-control {{ $errors->has('municipality') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Municipality</option>
                                        </select>
                                        @if ($errors->has('municipality'))
                                            <span class="error invalid-feedback">{{$errors->first('municipality')}}</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="col-md-6" id="barangay_input" style="display: {{$errors->has('barangay') ? 'block' : 'none'}};"> --}}
                                <div class="col-md-6" id="barangay_input">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('barangay') ? ' is-invalid' : '' }}" name="barangay" value="{{old('barangay')}}" placeholder="Barangay">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-map-marker-alt"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('barangay'))
                                            <span class="error invalid-feedback">{{$errors->first('barangay')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="affiliation" id="affiliation" class="form-control {{$errors->has('affiliation') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Affiliation</option>
                                            @foreach($affiliations as $affiliation)
                                                <option value="{{$affiliation->affiliation_id}}" {{old('affiliation') == $affiliation->affiliation_id ? 'selected' : ''}}>{{$affiliation->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('affiliation'))
                                            <span class="error invalid-feedback">{{$errors->first('affiliation')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Designation (optional)" name="designation" value="{{old('designation')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user-tie"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="station_input" style="display: {{$errors->has('station') ? 'block' : 'none'}};">
                                    <div class="mb-3">
                                        <select class="form-control {{$errors->has('station') ? 'is-invalid' : ''}}" name="station" id="station">
                                            <option value="0" selected disabled>PhilRice Station</option>
                                            @foreach($stations as $station)
                                                <option value="{{$station->philrice_station_id}}" {{old('station') == $station->philrice_station_id ? 'selected' : ''}}>{{$station->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('station'))
                                            <span class="error invalid-feedback">{{$errors->first('station')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="philrice_idno_input" style="display: {{$errors->has('philrice_idno') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" name="philrice_idno" id="philrice_idno" class="form-control input_mask {{$errors->has('philrice_idno') ? 'is-invalid' : ''}}" data-inputmask="'mask': '99-9999'" value="{{old('philrice_idno')}}" placeholder="PhilRice ID no.">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-id-card-alt"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('philrice_idno'))
                                            <span class="error invalid-feedback">{{$errors->first('philrice_idno')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="coop" style="display: {{$errors->has('coop') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Cooperative" name="coop" value="{{old('coop')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-users"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="accreditation_no" style="display: {{$errors->has('accreditation_no') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{$errors->has('accreditation_no') ? 'is-invalid' : ''}}" placeholder="Accreditation No." name="accreditation_no" value="{{old('accreditation_no')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('accreditation_no'))
                                            <span class="error invalid-feedback">{{$errors->first('accreditation_no')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="agency" style="display: {{$errors->has('agency') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{$errors->has('agency') ? 'is-invalid' : ''}}" placeholder="Agency" name="agency" value="{{old('agency')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-building"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('agency'))
                                            <span class="error invalid-feedback">{{$errors->first('agency')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="school" style="display: {{$errors->has('school') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{$errors->has('school') ? 'is-invalid' : ''}}" placeholder="University/ School" name="school" value="{{old('school')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-school"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('school'))
                                            <span class="error invalid-feedback">{{$errors->first('school')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="agree" name="agree">
                                        <label for="remember">
                                            <small>I agree to the <a href="{{url('/') . '/website_terms_and_conditions'}}" target="_blank">Terms</a></small>
                                        </label>
                                        @if ($errors->has('agree'))
                                            <span class="error invalid-feedback d-block">{{$errors->first('agree')}}</span>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}

                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="error invalid-feedback">
                                                {{ $errors->first('g-recaptcha-response')}}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-success float-right" style="padding-left: 30px; padding-right: 30px;">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="d-block d-sm-none col-xs-12 mt-3 mx-auto">
                <div class="card">
                    <div class="card-body register-card-body">
                        <p class="login-box-msg text-center">REGISTRATION FORM</p>

                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="First Name" name="firstname" value="{{old('firstname')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('firstname'))
                                            <span class="error invalid-feedback">{{$errors->first('firstname')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Middle Name" name="middlename" value="{{old('middlename')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="Last Name" name="lastname" value="{{old('lastname')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('lastname'))
                                            <span class="error invalid-feedback">{{$errors->first('lastname')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Extension Name" name="extname" value="{{old('extname')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="Username" name="username" value="{{($errors->has('username')) ? '' : old('username')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('username'))
                                            <span class="error invalid-feedback">{{$errors->first('username')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail Address" name="email" value="{{($errors->has('email')) ? '' : old('email')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="error invalid-feedback">{{$errors->first('email')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control birthday {{ $errors->has('birthday') ? ' is-invalid' : '' }}" placeholder="Birthday" name="birthday" value="{{($errors->has('birthday')) ? '' : old('birthday')}}" readonly="readonly">
                                        @if ($errors->has('birthday'))
                                            <span class="error invalid-feedback" style="{{$errors->first('birthday') ? 'display: block' : ''}}">{{$errors->first('birthday')}}</span>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control {{ $errors->has('age') ? ' is-invalid' : '' }}" placeholder="Age" name="age" value="{{old('age')}}" min="1" step="1">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('age'))
                                            <span class="error invalid-feedback">{{$errors->first('age')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="sex" id="sex" class="form-control {{ $errors->has('sex') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Sex</option>
                                            <option value="Male" {{old('sex') == "Male" ? 'selected' : ''}}>Male</option>
                                            <option value="Female" {{old('sex') == "Female" ? 'selected' : ''}}>Female</option>
                                        </select>
                                        @if ($errors->has('sex'))
                                            <span class="error invalid-feedback">{{$errors->first('sex')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="region" id="region_mob" value="{{($errors->has('region')) ? '' : old('region')}}">

                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Country</option>
                                            @foreach($countries as $key => $value)
                                                <option value="{{$key}}" {{old('country') == $key ? 'selected' : ''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="error invalid-feedback">{{$errors->first('country')}}</span>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6" id="province_input">
                                    <div class="mb-3">
                                        <select name="province" id="province_mob" class="form-control {{ $errors->has('province') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Province</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->prov_code}}" region_id="{{$province->region_id}}" province_id="{{$province->province_id}}" {{old('province') == $province->prov_code ? 'selected' : ''}}>{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province'))
                                            <span class="error invalid-feedback">{{$errors->first('province')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="municipality_input">
                                    <div class="mb-3">
                                        <select name="municipality" id="municipality_mob" class="form-control {{ $errors->has('municipality') ? ' is-invalid' : '' }}">
                                            <option selected disabled>Municipality</option>
                                        </select>
                                        @if ($errors->has('municipality'))
                                            <span class="error invalid-feedback">{{$errors->first('municipality')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="barangay_input">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{ $errors->has('barangay') ? ' is-invalid' : '' }}" name="barangay" value="{{old('barangay')}}" placeholder="Barangay">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-map-marker-alt"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('barangay'))
                                            <span class="error invalid-feedback">{{$errors->first('barangay')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="affiliation" id="affiliation" class="form-control {{$errors->has('affiliation') ? 'is-invalid' : ''}}">
                                            <option value="0" selected disabled>Affiliation</option>
                                            @foreach($affiliations as $affiliation)
                                                <option value="{{$affiliation->affiliation_id}}" {{old('affiliation') == $affiliation->affiliation_id ? 'selected' : ''}}>{{$affiliation->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('affiliation'))
                                            <span class="error invalid-feedback">{{$errors->first('affiliation')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Designation (optional)" name="designation" value="{{old('designation')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user-tie"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="station_input" style="display: {{$errors->has('station') ? 'block' : 'none'}};">
                                    <div class="mb-3">
                                        <select class="form-control {{$errors->has('station') ? 'is-invalid' : ''}}" name="station" id="station">
                                            <option value="0" selected disabled>PhilRice Station</option>
                                            @foreach($stations as $station)
                                                <option value="{{$station->philrice_station_id}}" {{old('station') == $station->philrice_station_id ? 'selected' : ''}}>{{$station->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('station'))
                                            <span class="error invalid-feedback">{{$errors->first('station')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="philrice_idno_input" style="display: {{$errors->has('philrice_idno') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" name="philrice_idno" id="philrice_idno" class="form-control input_mask {{$errors->has('philrice_idno') ? 'is-invalid' : ''}}" data-inputmask="'mask': '99-9999'" value="{{old('philrice_idno')}}" placeholder="PhilRice ID no.">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-id-card-alt"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('philrice_idno'))
                                            <span class="error invalid-feedback">{{$errors->first('philrice_idno')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="coop" style="display: {{$errors->has('coop') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Cooperative" name="coop" value="{{old('coop')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-users"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="accreditation_no" style="display: {{$errors->has('accreditation_no') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{$errors->has('accreditation_no') ? 'is-invalid' : ''}}" placeholder="Accreditation No." name="accreditation_no" value="{{old('accreditation_no')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('accreditation_no'))
                                            <span class="error invalid-feedback">{{$errors->first('accreditation_no')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="agency" style="display: {{$errors->has('agency') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{$errors->has('agency') ? 'is-invalid' : ''}}" placeholder="Agency" name="agency" value="{{old('agency')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-building"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('agency'))
                                            <span class="error invalid-feedback">{{$errors->first('agency')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6" id="school" style="display: {{$errors->has('school') ? 'block' : 'none'}};">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control {{$errors->has('school') ? 'is-invalid' : ''}}" placeholder="University/ School" name="school" value="{{old('school')}}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-school"></span>
                                            </div>
                                        </div>
                                        @if ($errors->has('school'))
                                            <span class="error invalid-feedback">{{$errors->first('school')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                             <div class="row">
                                <div class="col-6">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="agree" name="agree">
                                        <label for="remember">
                                            <small>I agree to the <a href="{{url('/') . '/website_terms_and_conditions'}}" target="_blank">Terms</a></small>
                                        </label>
                                        @if ($errors->has('agree'))
                                            <span class="error invalid-feedback d-block">{{$errors->first('agree')}}</span>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}

                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="error invalid-feedback">
                                                {{ $errors->first('g-recaptcha-response')}}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-success float-right" style="padding-left: 30px; padding-right: 30px;">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
    <!-- InputMask -->
    <script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>

    <script type="text/javascript">
        sessionStorage.removeItem("is_locked") // Set is_locked to 0 to remove lockscreen when session timed out

        // Inputmask
        $('.input_mask').inputmask()

        // Birthday datepicker
        $('.birthday').datepicker({
            autoclose: true,
            uiLibrary: 'bootstrap4'
        })

        // Show province, municipality and barangay when selected ph as country
        $('#country').on('change', function() {
            if($(this).val() == "PH") {
                $('#province_input').css('display', 'block')
                $('#municipality_input').css('display', 'block')
                $('#barangay_input').css('display', 'block')
            } else {
                $('#province_input').css('display', 'none')
                $('#municipality_input').css('display', 'none')
                $('#barangay_input').css('display', 'none')
            }
        })

        // Get region when selected province
        $('#province').on('change', ()=>{
            var region_id = $('#province option:selected').attr('region_id')
            var province_id = $('#province option:selected').attr('province_id')

            $('#municipality').empty() // empty municipality
            $('#municipality').append(`<option selected disabled>Loading...</option>`)
            // Get region code
            $.ajax({
                type: 'POST',
                url: "{{route('register.regions.region_code')}}",
                data: {
                    _token: _token,
                    region_id: region_id
                },
                dataType: 'json',
                success: (res)=>{
                    $('#region').val(res)
                }
            })

            // Get municipalities
            $.ajax({
                type: 'POST',
                url: "{{route('register.municipalities')}}",
                data: {
                    _token: _token,
                    province_id: province_id
                },
                dataType: 'json',
                success: (res)=>{
                    $('#municipality').empty() // empty municipality
                    var options = `<option value="0" selected disabled>Municipality</option>`
                    res.forEach((item)=> {
                        options += `<option value="`+item.mun_code+`">`+item.name+`</option>`
                    })
                    $('#municipality').append(options)
                }
            })
        })

        $('#province_mob').on('change', ()=>{
            var region_id = $('#province_mob option:selected').attr('region_id')
            var province_id = $('#province_mob option:selected').attr('province_id')

            $('#municipality_mob').empty() // empty municipality
            $('#municipality_mob').append(`<option selected disabled>Loading...</option>`)
            // Get region code
            $.ajax({
                type: 'POST',
                url: "{{route('register.regions.region_code')}}",
                data: {
                    _token: _token,
                    region_id: region_id
                },
                dataType: 'json',
                success: (res)=>{
                    $('#region_mob').val(res)
                }
            })

            // Get municipalities
            $.ajax({
                type: 'POST',
                url: "{{route('register.municipalities')}}",
                data: {
                    _token: _token,
                    province_id: province_id
                },
                dataType: 'json',
                success: (res)=>{
                    $('#municipality_mob').empty() // empty municipality
                    var options = `<option value="0" selected disabled>Municipality</option>`
                    res.forEach((item)=> {
                        options += `<option value="`+item.mun_code+`">`+item.name+`</option>`
                    })
                    $('#municipality_mob').append(options)
                }
            })
        })

        // Show philrice stations dropdown and id no input when selected philrice as affiliation
        $('#affiliation').on('change', ()=>{
            var affiliation_id = $('#affiliation option:selected').val()
            if (affiliation_id == 1) {
                $('#station_input').css('display', 'block')
                $('#philrice_idno_input').css('display', 'block')
                $('#coop').css('display', 'none')
                $('#accreditation_no').css('display', 'none');
                $('#agency').css('display', 'none')
                $('#school').css('display', 'none')
            } else if(affiliation_id == 3 || affiliation_id == 9) {
                $('#station_input').css('display', 'none')
                $('#philrice_idno_input').css('display', 'none')
                $('#coop').css('display', 'block')
                $('#accreditation_no').css('display', 'block')
                $('#agency').css('display', 'none')
                $('#school').css('display', 'none')
            } else if(affiliation_id == 5) {
                $('#station_input').css('display', 'none')
                $('#philrice_idno_input').css('display', 'none')
                $('#coop').css('display', 'none')
                $('#accreditation_no').css('display', 'none');
                $('#agency').css('display', 'none')
                $('#school').css('display', 'block')
            } else if(affiliation_id == 6) {
                $('#station_input').css('display', 'none')
                $('#philrice_idno_input').css('display', 'none')
                $('#coop').css('display', 'none')
                $('#accreditation_no').css('display', 'none');
                $('#agency').css('display', 'block')
                $('#school').css('display', 'none')
            } else {
                $('#station_input').css('display', 'none')
                $('#philrice_idno_input').css('display', 'none')
                $('#coop').css('display', 'none')
                $('#accreditation_no').css('display', 'none');
                $('#agency').css('display', 'none')
                $('#school').css('display', 'none')
            }
        })

        /*
         * For Laravel validation
         *
         * If country selected is Philippines and province or municipality has value
         * and validation returned error on other fields
         * province, municipality and barangay fields must be displayed
         *
         * If affiliation selected is PhilRice and PhilRice station or PhilRice id no has value
         * and validation returned error on other fields
         * PhilRice station or PhilRice id no must be displayed
         * 
         * If affiliation selected is Seed Grower
         * and validation returned error on other fields
         * Accreditation number must be displayed
         *
         */
        $(document).ready(()=> {
            // Country
            // var country = $('#country option:selected').val()
            // if (country == "PH") {
            //     $('#province_input').css('display', 'block')
            //     $('#municipality_input').css('display', 'block')
            //     $('#barangay_input').css('display', 'block')
            // }

            // Affiliation
            var affiliation = $('#affiliation option:selected').val()
            if (affiliation == 1) {
                $('#station_input').css('display', 'block')
                $('#philrice_idno_input').css('display', 'block')
            } else if (affiliation == 3) {
                $('#coop').css('display', 'block')
                $('#accreditation_no').css('display', 'block')
            }

            var province_id = $('#province option:selected').attr('province_id')

            if (province_id != 0) {
                // Get municipalities
                $.ajax({
                    type: 'POST',
                    url: "{{route('register.municipalities')}}",
                    data: {
                        _token: _token,
                        province_id: province_id
                    },
                    dataType: 'json',
                    success: (res)=>{
                        $('#municipality').empty() // empty municipality
                        var options = `<option value="0" selected disabled>Municipality</option>`
                        res.forEach((item)=> {
                            options += `<option value="`+item.mun_code+`">`+item.name+`</option>`
                        })
                        $('#municipality').append(options)
                        var old_municipality = "{{old('municipality')}}"
                        console.log(old_municipality)
                        if (old_municipality) {
                            $('#municipality option[value="'+old_municipality+'"]').prop('selected', true)
                        }
                    }
                })
            }
        })
    </script>
@endpush
