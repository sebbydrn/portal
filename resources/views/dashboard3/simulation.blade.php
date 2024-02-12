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
		<div class="row">
			<div class="col-12 text-center">
				<h3>Seed Production Volume Estimates</h3>
			</div>
		</div>

		<div class="row mt-4">
			<div class="col-12">
				<div id="seed_prod_estimates"></div>
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
    @include('dashboard3.js.simulation')
@endpush