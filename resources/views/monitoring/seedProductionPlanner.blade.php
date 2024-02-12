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
                        <li class="breadcrumb-item active" aria-current="page">Data Monitoring</li>
                      </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1><strong>Data Monitoring</strong></h1>
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
				  <li class="list-group-item active"><a href="{{route('monitoring.grow_app')}}">GROWAPP</a></li>
				  <li class="list-group-item active"><a href="{{route('monitoring.seed_production_planner')}}">SEED PRODUCTION PLANNER</a></li>
			</div>
			<div class="d-none d-sm-block col-9 col-xl-9 col-md-8 col-sm-12">
				<div class="row">
					<div class="col-12">
						<h4 style="text-align: center;">Seed Production Planner Data Submission Per Station</h4>
						<h4 style="text-align: center;">{{$croppingYear}} {{$semesterText}}</h4>
						<table class="table table-bordered mt-4">
							<thead>
								<tr>
									<th style="width: 40%;">PhilRice Station</th>
									<th style="width: 20%;">Area with Production Plan Created</th>
									<th style="width: 20%;">Seed Production Area</th>
									<th style="width: 20%;">Percent Completion</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $item)
									<tr>
										<td>{{$item['station']}}</td>
										<td>{{$item['area']}}</td>
										<td>{{$item['production_area']}}</td>
										<td>

											<div class="progress">
											  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$item['percentCompleted']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$item['percentCompleted']}}%">{{$item['percentCompleted']}}%</div>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
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