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
					    <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>Contact Us</strong></h1>
				</div>
			</div>
		</div>
	</div>
	{{-- End of header --}}
	{{-- Content --}}
	<div id="padding_content"></div>
	<div id="contact_us_content">
		<div class="row mt-5">
			<div class="d-none d-sm-block col-6 col-xl-6 col-md-6 col-sm-12">
				<p>Our team is happy to answer your query. Fill out the form and we will be in touch as soon as possible.</p>

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

				{!! Form::open(['method' => 'POST', 'route' => 'contact_us.store']) !!}
				
				<div class="form-group">
					<input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" placeholder="Your name *" value="{{old('name')}}">
					@if ($errors->has('name'))
                        <span class="error invalid-feedback">{{$errors->first('name')}}</span>
                    @endif
				</div>

				<div class="form-group">
					<input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" placeholder="Your e-mail address *" value="{{old('email')}}">
					@if ($errors->has('email'))
                        <span class="error invalid-feedback">{{$errors->first('email')}}</span>
                    @endif
				</div>
				
				<div class="form-group">
					<textarea name="message" id="message" class="form-control {{ $errors->has('message') ? ' is-invalid' : '' }}" placeholder="Your message here *" rows="15">{{old('message')}}</textarea>
					@if ($errors->has('message'))
                        <span class="error invalid-feedback">{{$errors->first('message')}}</span>
                    @endif
				</div>

				<div class="form-group">
					{!! NoCaptcha::renderJs() !!}
                	{!! NoCaptcha::display() !!}

	                @if ($errors->has('g-recaptcha-response'))
					    <span class="error invalid-feedback">
					        {{ $errors->first('g-recaptcha-response')}}
					    </span>
					@endif
                </div>

				<button type="submit" class="btn btn-success btn-block" style="margin-bottom: 50px;">Send</button>

				{!! Form::close() !!}
			</div>
			<div class="d-none d-sm-block col-6 col-xl-6 col-md-6 col-sm-12">
				<h3 class="mb-4">How to reach us:</h3>
				<hr class="mb-5">
				<i class="fa fa-map-marker-alt"></i> <b>Address:</b> {{$contacts['Address BPI']}}<br> 
				<i class="fa fa-map-marker-alt"></i> <b>Address:</b> {{$contacts['Address PhilRice']}} <br>
				<i class="fa fa-fax"></i> <b>Telefax:</b> {{$contacts['Telefax']}}<br>
				<i class="fa fa-phone"></i> <b>Mobile No.:</b> {{$contacts['Mobile No.']}}<br>
				<i class="fa fa-envelope"></i> <b>Email:</b> {{$contacts['Email BPI']}}<br>
				<i class="fa fa-envelope"></i> <b>Email:</b> {{$contacts['Email PhilRice']}}<br>
				<i class="far fa-hand-point-up"></i> <b>Website:</b> {{$contacts['Website']}}<br>
				<hr class="mt-5 mb-4">
				<div class="contact_header_item item_right">
				<div class="contact_icon_background">
					<a href="http://www.facebook.com"><i class="fab fa-facebook-f fa-lg"></i></a>
				</div>
				</div>
				<div class="contact_header_item item_right">
					<div class="contact_icon_background">
						<a href="http://www.youtube.com"><i class="fab fa-youtube fa-lg"></i></a>
					</div>
				</div>
				<div class="contact_header_item item_right">
					<div class="contact_icon_background">
						<a href="http://www.flickr.com"><i class="fab fa-flickr fa-lg"></i></a>
					</div>
				</div>
				<div class="contact_header_item item_right">
					<div class="contact_icon_background">
						<a href="http://www.twitter.com"><i class="fab fa-twitter fa-lg"></i></a>
					</div>
				</div>

				<div class="mt-5">
					<div class="mb-4" id="map"></div>

					<div id="map2"></div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="d-block d-sm-none col-xs-12">
				<p>Our team is happy to answer your query. Fill out the form and we will be in touch as soon as possible.</p>

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

				{!! Form::open(['method' => 'POST', 'route' => 'contact_us.store']) !!}
				
				<div class="form-group">
					<input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" placeholder="Your name *" value="{{old('name')}}">
					@if ($errors->has('name'))
                        <span class="error invalid-feedback">{{$errors->first('name')}}</span>
                    @endif
				</div>

				<div class="form-group">
					<input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" placeholder="Your e-mail address *" value="{{old('email')}}">
					@if ($errors->has('email'))
                        <span class="error invalid-feedback">{{$errors->first('email')}}</span>
                    @endif
				</div>
				
				<div class="form-group">
					<textarea name="message" id="message" class="form-control {{ $errors->has('message') ? ' is-invalid' : '' }}" placeholder="Your message here *" rows="15">{{old('message')}}</textarea>
					@if ($errors->has('message'))
                        <span class="error invalid-feedback">{{$errors->first('message')}}</span>
                    @endif
				</div>

				<div class="form-group">
					{!! NoCaptcha::renderJs() !!}
                	{!! NoCaptcha::display() !!}

	                @if ($errors->has('g-recaptcha-response'))
					    <span class="error invalid-feedback">
					        {{ $errors->first('g-recaptcha-response')}}
					    </span>
					@endif
                </div>

				<button type="submit" class="btn btn-success btn-block" style="margin-bottom: 50px;">Send</button>

				{!! Form::close() !!}
			</div>
		</div>
		<div class="row">
			<div class="d-block d-sm-none col-xs-12">
				<h3 class="mb-4">How to reach us:</h3>
				<hr class="mb-5">
				<i class="fa fa-map-marker-alt"></i> <b>Address:</b> {{$contacts['Address BPI']}}<br> 
				<i class="fa fa-map-marker-alt"></i> <b>Address:</b> {{$contacts['Address PhilRice']}} <br>
				<i class="fa fa-fax"></i> <b>Telefax:</b> {{$contacts['Telefax']}}<br>
				<i class="fa fa-phone"></i> <b>Mobile No.:</b> {{$contacts['Mobile No.']}}<br>
				<i class="fa fa-envelope"></i> <b>Email:</b> {{$contacts['Email BPI']}}<br>
				<i class="fa fa-envelope"></i> <b>Email:</b> {{$contacts['Email PhilRice']}}<br>
				<i class="far fa-hand-point-up"></i> <b>Website:</b> {{$contacts['Website']}}<br>
				<hr class="mt-5 mb-4">
				<div class="contact_header_item item_right">
				<div class="contact_icon_background">
					<a href="http://www.facebook.com"><i class="fab fa-facebook-f fa-lg"></i></a>
				</div>
				</div>
				<div class="contact_header_item item_right">
					<div class="contact_icon_background">
						<a href="http://www.youtube.com"><i class="fab fa-youtube fa-lg"></i></a>
					</div>
				</div>
				<div class="contact_header_item item_right">
					<div class="contact_icon_background">
						<a href="http://www.flickr.com"><i class="fab fa-flickr fa-lg"></i></a>
					</div>
				</div>
				<div class="contact_header_item item_right">
					<div class="contact_icon_background">
						<a href="http://www.twitter.com"><i class="fab fa-twitter fa-lg"></i></a>
					</div>
				</div>

				<div class="mt-5">
					<div class="mb-4" id="mapSmall"></div>

					<div id="map2Small"></div>
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
<script>
	function myMap() {
		var mapProp= {
			center: new google.maps.LatLng(14.6560798,121.0479252),
		  	zoom: 15,
		};
		var mapProp2= {
			center: new google.maps.LatLng(15.6714589,120.8909235),
		  	zoom: 13,
		};

		var map = new google.maps.Map(document.getElementById("map"),mapProp);
		var map2 = new google.maps.Map(document.getElementById("map2"),mapProp2);

		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(14.6560798,121.0479252),
		    map: map
		});

		var marker2 = new google.maps.Marker({
		    position: new google.maps.LatLng(15.6714589,120.8909235),
		    map: map2
		});

		var mapPropSmall= {
			center: new google.maps.LatLng(14.6560798,121.0479252),
		  	zoom: 15,
		};
		var mapProp2Small= {
			center: new google.maps.LatLng(15.6714589,120.8909235),
		  	zoom: 13,
		};

		var mapSmall = new google.maps.Map(document.getElementById("mapSmall"),mapPropSmall);
		var map2Small = new google.maps.Map(document.getElementById("map2Small"),mapProp2Small);

		var markerSmall = new google.maps.Marker({
			position: new google.maps.LatLng(14.6560798,121.0479252),
		    map: mapSmall
		});

		var marker2Small = new google.maps.Marker({
		    position: new google.maps.LatLng(15.6714589,120.8909235),
		    map: map2Small
		});
	}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnZZ3odGZVl8V0ZTfaE_-F_8l2VS7yFyc&callback=myMap"></script>
@endpush