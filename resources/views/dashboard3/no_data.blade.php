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
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                      </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1><strong>Dashboard</strong></h1>
                </div>
            </div>
        </div>
    </div>
    {{-- End of header --}}

    {{-- Content --}}
	<div id="padding_content"></div>
	<div id="profile_content" class="col_no_padding">
		<div class="row mt-5">
			<div class="col-12">
				<div class="alert alert-info">
					<h4 class="alert-heading">Data Sources</h4>
					<hr>
					<ul class="mb-0">
						<li>Warehouse Online System (DA-PhilRice) - Available Seeds Data</li>
						<li>Seed Ordering System (DA-PhilRice) - Sales Data</li>
						<li>Application for Seed Certification (DA-PhilRice) - GrowApp</li>
						<li>SI (Seed Inspector) App (DA-BPI NSQCS) - Prelim and Final Inspection Data</li>
						<li>Databank (DA-BPI NSQCS) - Sampling and Laboratory Analysis Data</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-2">
				<select name="year" id="year" class="form-control">
					<option selected disabled>Select year</option>
					@foreach($years as $y)
						<option value="{{$y->year}}">{{$y->year}}</option>
					@endforeach
				</select>
			</div>

			<div class="col-2">
				<select name="sem" id="sem" class="form-control">
					<option selected disabled>Select sem</option>
					<option value="1">1st</option>
					<option value="2">2nd</option>
				</select>
			</div>

			<div class="col-2">
				<button class="btn btn-primary" onclick="show_data()">Show</button>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-12">
				<h1 class="text-center">NO DATA</h1>
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
	@include('dashboard3.js.filter_data')
@endpush