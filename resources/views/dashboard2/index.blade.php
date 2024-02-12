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
			<div class="d-none d-sm-block col-3 col-xl-3 col-md-4 col-sm-12">
				<ul class="list-group">
				  <li class="list-group-item active"><a href="#">PRODUCTION</a></li>
				  <li class="list-group-item"><a href="#">INVENTORY</a></li>
				  <li class="list-group-item"><a href="#">SALES</a></li>
			</div>
			<div class="d-none d-sm-block col-9 col-xl-9 col-md-8 col-sm-12">
				<div class="row">
					<div class="col-6">
						<div class="card">
							<div class="card-body">
								Total Number of Seed Growers
								<br><br>
								<h1 style="text-align: right;"><a href="#" style="color: black;">{{$sgCount}}</a></h1>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card">
							<div class="card-body">
								Total Number of Seed Cooperatives
								<br><br>
								<h1 style="text-align: right;"><a href="#" style="color: black;">{{$scCount}}</a></h1>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top: 10px;">
					<div class="col-6">
						<div class="card">
							<div class="card-body">
								Submitted Applications through GrowApp
								<br><br>
								<h1 style="text-align: right;"><a href="{{route('dashboard2.growApp')}}" style="color: black;">{{$sgForms}}</a></h1>
							</div>
						</div>
					</div>
					{{-- <div class="col-6">
						<div class="card">
							<div class="card-body">
								Total Number of Seed Cooperatives
								<br><br>
								<h1 style="text-align: right;">{{$scCount}}</h1>
							</div>
						</div>
					</div> --}}
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