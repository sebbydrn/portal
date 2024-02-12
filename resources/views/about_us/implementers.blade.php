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
					    <li class="breadcrumb-item"><a href="{{url('/') . '/about_us/rsis'}}">About Us</a></li>
					    <li class="breadcrumb-item active" aria-current="page">Implementers</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>About Us</strong></h1>
				</div>
			</div>
		</div>
	</div>
	{{-- End of header --}}
	{{-- Content --}}
	<div id="padding_content"></div>
	<div id="about_us_content" class="col_no_padding">
		<div class="row">
			<div class="d-none d-sm-block col-3 col-xl-3 col-md-4 col-sm-12">
				<ul class="list-group">
				  <li class="list-group-item"><a href="{{url('/') . '/about_us/rsis'}}">RSIS</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/about_us/objectives'}}">OBJECTIVES</a></li>
				  <li class="list-group-item active"><a href="{{url('/') . '/about_us/implementers'}}">IMPLEMENTERS</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/about_us/partners'}}">PARTNERS</a></li>
				</ul>
			</div>
			<div class="d-none d-sm-block col-9 col-xl-9 col-md-8 col-sm-12">
				@foreach($contents as $content)
					@if($content->subtitle)
						<h4 class="mb-3">{{$content->subtitle}}</h4>
					@endif
					{!!htmlspecialchars_decode($content->content)!!}
				@endforeach
			</div>
		</div>

		<div class="row mb-4">
			<div class="d-block d-sm-none col-xs-12">
				<ul class="list-group list-group-horizontal">
				  <li class="list-group-item"><a href="{{url('/') . '/about_us/rsis'}}">RSIS</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/about_us/objectives'}}">OBJECTIVES</a></li>
				  <li class="list-group-item active"><a href="{{url('/') . '/about_us/implementers'}}">IMPLEMENTERS</a></li>
				  <li class="list-group-item"><a href="{{url('/') . '/about_us/partners'}}">PARTNERS</a></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="d-block d-sm-none col-xs-12">
				@foreach($contents as $content)
					@if($content->subtitle)
						<h4 class="mb-3">{{$content->subtitle}}</h4>
					@endif
					{!!htmlspecialchars_decode($content->content)!!}
				@endforeach
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