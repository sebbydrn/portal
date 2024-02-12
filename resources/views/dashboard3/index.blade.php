@extends('landing_page_layouts.index')

@push('styles')
	<style>
		.no-right-border {
			border-right:  0px !important;
		}

		.radius-10 {
			border-radius:  10px !important;
		}

		.border-info {
			border-left: 5px solid  #0dcaf0 !important;
		}

		.border-danger {
			border-left: 5px solid  #fd3550 !important;
		}

		.border-success {
			border-left: 5px solid  #15ca20 !important;
		}

		.border-warning {
			border-left: 5px solid  #ffc107 !important;
		}

		.dashboard-card {
			position: relative;
		    display: flex;
		    flex-direction: column;
		    min-width: 0;
		    word-wrap: break-word;
		    background-color: #fff;
		    background-clip: border-box;
		    border: 0px solid rgba(0, 0, 0, 0);
		    border-radius: .25rem;
		    margin-bottom: 1.5rem;
		    box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
		}

		.bg-gradient-scooter {
			background: #17ead9;
		    background: -webkit-linear-gradient( 
		45deg
		 , #17ead9, #6078ea)!important;
		    background: linear-gradient( 
		45deg
		 , #17ead9, #6078ea)!important;
		}

		.widgets-icons-2 {
			width: 56px;
		    height: 56px;
		    display: flex;
		    align-items: center;
		    justify-content: center;
		    background-color: #ededed;
		    font-size: 27px;
		    border-radius: 10px;
		}

		.rounded-circle {
		    border-radius: 50%!important;
		}
		.text-white {
		    color: #fff!important;
		}
		.ms-auto {
		    margin-left: auto!important;
		}

		.bg-gradient-bloody {
		    background: #f54ea2;
		    background: -webkit-linear-gradient( 
		45deg
		 , #f54ea2, #ff7676)!important;
		    background: linear-gradient( 
		45deg
		 , #f54ea2, #ff7676)!important;
		}

		.bg-gradient-ohhappiness {
		    background: #00b09b;
		    background: -webkit-linear-gradient( 
		45deg
		 , #00b09b, #96c93d)!important;
		    background: linear-gradient( 
		45deg
		 , #00b09b, #96c93d)!important;
		}

		.bg-gradient-blooker {
		    background: #ffdf40;
		    background: -webkit-linear-gradient( 
		45deg
		 , #ffdf40, #ff8359)!important;
		    background: linear-gradient( 
		45deg
		 , #ffdf40, #ff8359)!important;
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
                    <h1><strong>Dashboard</strong></h1>
                </div>
            </div>
        </div>
    </div>
    {{-- End of header --}}

    {{-- Content --}}
	<div id="padding_content"></div>
	<?php /**<div id="dashboard_content" class="col_no_padding">
		{{-- <div class="row mt-5">
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
		</div> --}}

		<!-- RS-CS seed production volume estimates -->
		<div class="mt-5 shadow-lg p-4 mb-5 bg-white rounded">
			<div class="row">
				<div class="col-12 text-center">
					<h3>Registered - Certified Seed Production Volume Estimates</h3>
					{{-- <p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($production_estimates->timestamp))}}</p> --}}
				</div>
			</div>

			<div class="row mt-5">
				<div class="col-12">
					<div id="seed_prod_estimates"></div>
				</div>
			</div>
		</div>

		<!-- RS-CS seed production data-->
		<div class="mt-5 shadow-lg p-4 mb-5 bg-white rounded">
			<div class="row">
				<div class="col-12 text-center">
					<h3>Registered - Certified Seed Production Data</h3>
				</div>
			</div>

			<div class="row mt-5 justify-content-center">
			   	{{-- <div class="col-4 no-right-border">
					<div class="card radius-10 border-start border-0 border-3 border-success dashboard-card">
				   		<div class="card-body">
					   		<div class="d-flex align-items-center">
						   		<div>
							   		<p class="mb-0 text-secondary">Available RS in PhilRice</p>
							   		<h4 class="my-1 text-success">{{number_format($cs_production_data->available_rs_philrice)}} kg</h4>
							   		<p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
						   		</div>
						   		<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fa fa-seedling"></i>
						   		</div>
					   		</div>
				   		</div>
					</div>
			  	</div>  --}}
		    	<div class="col-4 no-right-border">
				 	<div class="card radius-10 border-start border-0 border-3 border-success dashboard-card">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">RS Purchased in PhilRice</p>
									@if($cs_production_data != null && !empty($cs_production_data))
										<h4 class="my-1 text-success">{{number_format($cs_production_data->purchased_rs_philrice)}} kg</h4>
										<p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
									@else
										<h4 class="my-1 text-success">NO DATA</h4>
									@endif
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fa fa-shopping-cart"></i>
								</div>
							</div>
						</div>
					</div>
			   	</div>
			   	<div class="col-4 no-right-border">
					<div class="card radius-10 border-start border-0 border-3 border-success dashboard-card">
				   		<div class="card-body">
					   		<div class="d-flex align-items-center">
						   		<div>
							   		<p class="mb-0 text-secondary">Area Applied for Seed Certification</p>
							   		@if($cs_production_data != null && !empty($cs_production_data))
							   			<h4 class="my-1 text-success">{{number_format($cs_production_data->rs_area_planted, 2)}} ha</h4>
							   			<p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
							   		@else
										<h4 class="my-1 text-success">NO DATA</h4>
							   		@endif
						   		</div>
						   		<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fa fa-tractor"></i>
						   		</div>
					   		</div>
				   		</div>
					</div>
			  	</div> 
			</div>

			<div class="row mt-1 justify-content-center">
				<div class="col-4 no-right-border">
					<div class="card radius-10 border-start border-0 border-3 border-success dashboard-card">
				   		<div class="card-body">
					   		<div class="d-flex align-items-center">
						   		<div>
							   		<p class="mb-0 text-secondary">Area Passed Prelim Insp.</p>
							   		@if($cs_production_data != null && !empty($cs_production_data))
							   			<h4 class="my-1 text-success">{{number_format($cs_production_data->rs_area_passed_prelim_insp, 2)}} ha</h4>
							   			<p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
							   		@else
										<h4 class="my-1 text-success">NO DATA</h4>
							   		@endif
						   		</div>
						   		<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fa fa-search"></i>
						   		</div>
					   		</div>
				   		</div>
					</div>
			  	</div> 
			  	<div class="col-4 no-right-border">
					<div class="card radius-10 border-start border-0 border-3 border-success dashboard-card">
				   		<div class="card-body">
					   		<div class="d-flex align-items-center">
						   		<div>
							   		<p class="mb-0 text-secondary">Area Passed Final Insp.</p>
							   		@if($cs_production_data != null && !empty($cs_production_data))
							   			<h4 class="my-1 text-success">{{number_format($cs_production_data->rs_area_passed_final_insp, 2)}} ha</h4>
							   			<p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
							   		@else
										<h4 class="my-1 text-success">NO DATA</h4>
							   		@endif
						   		</div>
						   		<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fa fa-search"></i>
						   		</div>
					   		</div>
				   		</div>
					</div>
			  	</div> 
			</div>

			<div class="row mt-1 justify-content-center">
			   	<div class="col-4 no-right-border">
					<div class="card radius-10 border-start border-0 border-3 border-info dashboard-card">
				   		<div class="card-body">
					   		<div class="d-flex align-items-center">
							   	<div>
								   <p class="mb-0 text-secondary">CS Under Test</p>
								   	@if($cs_production_data != null && !empty($cs_production_data))
									   <h4 class="my-1 text-info">{{number_format($cs_production_data->cs_under_test)}} kg</h4>
									   <p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
									@else
										<h4 class="my-1 text-info">NO DATA</h4>
									@endif
							   	</div>
						   		<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class="fa fa-microscope"></i>
						   		</div>
					   		</div>
				  	 	</div>
					</div>
			  	</div>
			   	<div class="col-4 no-right-border">
					<div class="card radius-10 border-start border-0 border-3 border-info dashboard-card">
				   		<div class="card-body">
					   		<div class="d-flex align-items-center">
							   	<div>
								   <p class="mb-0 text-secondary">CS Passed</p>
								   @if($cs_production_data != null && !empty($cs_production_data))
								   	<h4 class="my-1 text-info">{{number_format($cs_production_data->cs_passed)}} kg</h4>
								   	<p class="mb-0 font-13">as of {{date('M d, Y g:i A', strtotime($cs_production_data->timestamp))}}</p>
								   @else
										<h4 class="my-1 text-info">NO DATA</h4>
								   @endif
							   	</div>
						   		<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class="fa fa-seedling"></i>
						   		</div>
					   		</div>
				  	 	</div>
					</div>
			  	</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-6">
				<!--RS area planted and applied thru growapp per region-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Areas Applied for Seed Certification (RS-CS)<br /> {{$semesterText}}</h5>
							@if($rs_area_applied_region != null && !empty($rs_area_applied_region))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_applied_region->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_applied_region"></div>
						</div>
					</div>
				</div>

				<!--Varieties applied (RS) thru growapp per region-->
				<div class="shadow-lg p-4 mt-5 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Varieties Applied for Seed Certification (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_applied_region != null && !empty($rs_area_applied_region))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_varieties_applied->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_varieties_applied"></div>
						</div>
					</div>
				</div>

				
			</div>

			
			<div class="col-6">
				<!-- RS planted and applied thru growapp per region in map presentation-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Areas Applied for Seed Certification (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_applied_region != null && !empty($rs_area_applied_region))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_applied_region->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_applied_per_region_map" style="height: 700px;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-12">
				<!--Varities applied and area per region-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Varieties Applied for Seed Certification (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_applied_region != null && !empty($rs_area_applied_region))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_varieties_applied_region->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_varieties_applied_stacked_chart"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-5 mb-5">
			<div class="col-6">
				<!--Area applied for different rice programs-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Area Applied For Seed Certification For Different Rice Programs (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_insp_passed != null && !empty($rs_area_insp_passed))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_insp_passed->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_applied_per_program_column"></div>
						</div>
					</div>
				</div>

				<!--Varieties applied for seed certification (RS-CS) for different rice programs-->
				<div class="shadow-lg p-4 mt-5 bg-white rounder">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Varieties Applied for Seed Certification (RS-CS) For Different Rice Programs <br /> {{$semesterText}}</h5>
							@if($rs_varieties_applied_programs != null && !empty($rs_varieties_applied_programs))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_varieties_applied_programs->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="varieties_applied_per_program"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-6">
				<!--Area applied for different rice programs-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Area Applied For Seed Certification For Different Rice Programs (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_insp_passed != null && !empty($rs_area_insp_passed))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_insp_passed->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_applied_per_program_pie"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-5 mb-5">
			<div class="col-6">
				<!--Area applied for seed certification of seed cooperatives-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Area Applied For Seed Certification of Seed Cooperatives (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_applied_coop != null && !empty($rs_area_applied_coop))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_applied_coop->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_applied_coops"></div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-12 text-center">
							<button class="btn btn-primary btn-sm" onclick="showAreaAppliedCoops()">Click Here to Show Table</button>
						</div>
					</div>
				</div>
			</div>

			<div class="col-6">
				<!--Area applied for seed certification of seed cooperatives for different rice programs-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Area Applied For Seed Certification of Seed Cooperatives For Different Rice Programs (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_applied_coop != null && !empty($rs_area_applied_coop))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_applied_coop->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_applied_coop_programs"></div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-12 text-center">
							<button class="btn btn-primary btn-sm" onclick="showAreaAppliedCoopPrograms()">Click Here to Show Table</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row mt-5 mb-5">
			<div class="col-6">
				<!--Estimated Harvest/Yield of CS-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Estimated Yield of Varieties Applied for Seed Certification (RS-CS)<br /> {{$semesterText}}</h5>
							@if($cs_estimated_yield != null && !empty($cs_estimated_yield))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($cs_estimated_yield->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="cs_estimated_yield"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-6">
				<!--Estimated Harvest/Yield of CS-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Estimated Yield of Seeds Applied for Certification (RS-CS)<br /> {{$semesterText}}</h5>
							@if($cs_estimated_yield != null && !empty($cs_estimated_yield))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($cs_estimated_yield->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="cs_estimated_yield_line"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-5 mb-5">
			<div class="col-6"></div>
			
			<div class="col-6">
				<!--Estimated Harvest/Yield of CS-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Estimated Yield of Seeds Applied for Certification (RS-CS)<br /> {{$semesterText}}</h5>
							@if($cs_estimated_yield != null && !empty($cs_estimated_yield))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($cs_estimated_yield->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="cs_estimated_yield_region_line"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-5 mb-5">
			<div class="col-12">
				<!--Target area applied and area passed prelim and final inspection-->
				<div class="shadow-lg p-4 bg-white rounded">
					<div class="row">
						<div class="col-12 text-center">
							<h5>Areas Passed Prelim and Final Inspection (RS-CS) <br /> {{$semesterText}}</h5>
							@if($rs_area_insp_passed != null && !empty($rs_area_insp_passed))
								<p class="mt-2">Data as of {{date('M d, Y g:i A', strtotime($rs_area_insp_passed->timestamp))}}</p>
							@else
								<h4 class="my-1">NO DATA</h4>
							@endif
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12">
							<div id="rs_area_passed_prelim_final_column_chart"></div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row mt-5"></div>

		{{-- @if(Auth::guest())
			<div class="row mt-5">
				<div class="col-12 text-center">
					<h4><i><a href="{{route('login')}}">Login</a> or <a href="{{route('register')}}">Register</a> to view more data</i></h4>
				</div>
			</div>
		@else
			<div class="row mt-5">
				<div class="col-4">
					<div class="card system_card">
						<a href="{{route('dashboard3.seed_production')}}" class="card-link" style="float: right;">
							<div class="card-body">
								<h4 class="card-title system_name">Seed Production</h4>
								<i class="fa fa-arrow-right float-right mb-4"></i>
							</div>
						</a>
					</div>
				</div>
				@if(Auth::user()->can('view_sales_data', false, 10))
					<div class="col-4">
						<div class="card system_card">
							<a href="{{route('dashboard3.sales')}}" class="card-link" style="float: right;">
								<div class="card-body">
									<h4 class="card-title system_name">Seed Sales</h4>
									<i class="fa fa-arrow-right float-right mb-4"></i>
								</div>
							</a>
						</div>
					</div>
				@endif
			</div>
		@endif --}}

		@include('dashboard3.modals.area_applied_coops')
		@include('dashboard3.modals.area_applied_coop_programs')
	</div> **/?>
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
    {{-- @include('dashboard3.js.production_estimates')
    @include('dashboard3.js.region.rs_applied_column_chart')
    @include('dashboard3.js.varieties.rs_applied_pie_chart')
    @include('dashboard3.js.map.rs_area_applied_region_map')
    @include('dashboard3.js.region.rs_passed_prelim_final_column_chart')
    @include('dashboard3.js.varieties.rs_applied_stacked_chart')
    @include('dashboard3.js.programs.rs_area_applied_nat')
    @include('dashboard3.js.programs.rs_varieties_applied')
    @include('dashboard3.js.cooperatives.rs_area_applied')
    @include('dashboard3.js.cooperatives.rs_area_applied_programs')
    @include('dashboard3.js.modals.area_applied_coops')
    @include('dashboard3.js.yield.cs_estimated_yield')
    @include('dashboard3.js.filter_data') --}}
@endpush