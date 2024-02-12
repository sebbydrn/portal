@extends('landing_page_layouts.index')

@push('styles')
	<style>
	.container {
	    margin-top: 100px
	}

	.counter-box {
	    display: block;
	    background: #f6f6f6;
	    padding: 10px 10px 10px;
	    text-align: center
	}

	.counter-box p {
	    margin: 5px 0 0;
	    padding: 0;
	    color: #909090;
	    font-size: 18px;
	    font-weight: 500
	}

	.counter-box i {
	    font-size: 40px;
	    margin: 0 0 15px;
	    color: #d2d2d2
	}

	.counter {
	    display: block;
	    font-size: 25px;
	    font-weight: 700;
	    color: #666;
	    line-height: 25px
	    margin-bottom: 5px;
	}

	.counter-box.colored {
	    background: #3acf87
	}

	.counter-box.colored p,
	.counter-box.colored i,
	.counter-box.colored .counter {
	    color: #fff
	}
	</style>
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
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                      </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1><strong>Seed Sales</strong></h1>
                </div>
            </div>
        </div>
    </div>
    {{-- End of header --}}

    {{-- Content --}}
	<div id="padding_content"></div>
	<div id="dashboard_content" class="col_no_padding">
		<div class="row">
			<div class="col-12">
				<h1 class="text-center">{{$seed_sales->year}} SEM {{$seed_sales->sem}}</h1>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-2">
				<div class="four col-12 mb-4">
					<div class="counter-box colored"> <i class="fa fa-weight"></i> <span class="counter">{{number_format($seed_sales->total_volume_sold)}}</span>
						<p style="font-size: 16px;">Total Volume Sold <br />(kg)</p>
						<small style="color: #fff">As of {{date('y-M-d g:i a', strtotime($seed_sales->timestamp))}}</small>
					</div>
				</div>

				<div class="four col-12 mb-4">
					<div class="counter-box colored"> <i class="fa fa-weight"></i> <span class="counter">{{number_format($seed_sales->fs_volume_sold)}}</span>
						<p style="font-size: 16px;">FS Volume Sold <br />(kg)</p>
						<small style="color: #fff">As of {{date('y-M-d g:i a', strtotime($seed_sales->timestamp))}}</small>
					</div>
				</div>

				<div class="four col-12 mb-4">
					<div class="counter-box colored"> <i class="fa fa-weight"></i> <span class="counter">{{number_format($seed_sales->rs_volume_sold)}}</span>
						<p style="font-size: 16px;">RS Volume Sold <br />(kg)</p>
						<small style="color: #fff">As of {{date('y-M-d g:i a', strtotime($seed_sales->timestamp))}}</small>
					</div>
				</div>

				<div class="four col-12 mb-4">
					<div class="counter-box colored"> <i class="fa fa-users"></i> <span class="counter">{{number_format($seed_sales->ave_transactions_day)}}</span>
						<p style="font-size: 16px;">Ave. Transactions <br />Per Day</p>
						<small style="color: #fff">As of {{date('y-M-d g:i a', strtotime($seed_sales->timestamp))}}</small>
					</div>
				</div>
			</div>

			<div class="col-5">
				<p style="text-align: center;" class="mb-0">
					FS Sold Per Variety <br /> 
					As of 
					@if($fs_sold_var_filter)
						{{date('y-M-d g:i a', strtotime($fs_sold_var_filter->timestamp))}}
					@else
						{{date('y-M-d g:i a')}}
					@endif
				</p>
				<div id="FSSoldVarietyChart" class="mb-4"></div>

				<p style="text-align: center;" class="mb-0">
					FS Sold Per Station <br /> 
					As of 
					@if($fs_sold_station_filter)
						{{date('y-M-d g:i a', strtotime($fs_sold_station_filter->timestamp))}}
					@else
						{{date('y-M-d g:i a')}}
					@endif
				</p>
				<div id="FSSoldStationChart" class="mb-4"></div>

				<p style="text-align: center;" class="mb-0">
					Varieties Sold <br /> 
					As of 
					@if($varieties_sold_filter)
						{{date('y-M-d g:i a', strtotime($varieties_sold_filter->timestamp))}}
					@else
						{{date('y-M-d g:i a')}}
					@endif
				</p>
				<div id="VarietiesSoldPieChart" class="mb-4"></div>
			</div>

			<div class="col-5">
				<p style="text-align: center;" class="mb-0">
					RS Sold Per Variety <br /> 
					As of 
					@if($rs_sold_var_filter)
						{{date('y-M-d g:i a', strtotime($rs_sold_var_filter->timestamp))}}
					@else
						{{date('y-M-d g:i a')}}
					@endif
				</p>
				<div id="RSSoldVarietyChart" class="mb-4"></div>

				<p style="text-align: center;" class="mb-0">
					RS Sold Per Station <br /> 
					As of 
					@if($rs_sold_station_filter)
						{{date('y-M-d g:i a', strtotime($rs_sold_station_filter->timestamp))}}
					@else
						{{date('y-M-d g:i a')}}
					@endif
				</p>
				<div id="RSSoldStationChart" class="mb-4"></div>

				<p style="text-align: center;" class="mb-0">
					Top 5 Varieties Sold <br /> 
					As of 
					@if($varieties_sold_filter)
						{{date('y-M-d g:i a', strtotime($varieties_sold_filter->timestamp))}}
					@else
						{{date('y-M-d g:i a')}}
					@endif
				</p>
				<div id="TopVarietiesSoldChart" class="mb-4"></div>
			</div>

			{{-- <div class="col-3">
				
			</div> --}}
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
    @include('dashboard3.js.sales')
@endpush