@extends('landing_page_layouts.index')

@section('content')
	{{-- Slider --}}
	<div class="row">
		<div class="col-12 col_no_padding">
			<div id="rsis_carousel" class="carousel slide" data-ride="carousel">
			  	<div class="carousel-inner">
			  		<?php $slider_count = 1; ?>
			  		@foreach($sliders as $slider)
				    	<div class="carousel-item {{($slider_count == 1)? 'active' : ''}}">
				      		{{-- <a href="{{$slider->link}}"><img class="d-block w-100" src="{{url("/").'/public/images/sliders/Slide.png'}}" alt="First slide"></a> --}}
				      		<a href="{{$slider->link}}"><img class="d-block w-100" src="{{url("/").'/public/uploads/'.$slider->image}}" alt="{{$slider->name}}"></a>
				    	</div>
				    	<?php $slider_count++; ?>
			    	@endforeach
			  	</div>
			  	<a class="carousel-control-prev" href="#rsis_carousel" role="button" data-slide="prev">
			    	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			    	<span class="sr-only">Previous</span>
			  	</a>
			  	<a class="carousel-control-next" href="#rsis_carousel" role="button" data-slide="next">
			    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
			    	<span class="sr-only">Next</span>
			  	</a>
			</div>
		</div>
	</div>
	{{-- End of slider --}}
	{{-- Mission and Vision section --}}
	<div class="row mb-4">
		<div class="d-none d-sm-block col-xl-12 col_no_padding">
			<div id="mission_vision">
				<div class="card-deck">
					<div class="card">
						<div class="card-body mx-auto">
							<img src="{{url("/").'/public/images/icons_home_layer2/mission.png'}}" alt="" class="d-block icon_home_layer2 mb-2 mx-auto img-responsive">
							<h5 class="card-title mb-2">MISSION</h5>
							<p class="card-text">{{strip_tags(str_limit(htmlspecialchars_decode($mission->content), 155, '...'))}}.</p>
						</div>
						<div class="card-footer mx-auto">
							<a href="{{url('/') . '/about_us/rsis'}}" class="btn btn-default">Read more</a>
						</div>
					</div>
					<div class="card">
						<div class="card-body mx-auto">
							<img src="{{url("/").'/public/images/icons_home_layer2/vision.png'}}" alt="" class="d-block icon_home_layer2 mb-2 mx-auto img-responsive">
							<h5 class="card-title mb-2">VISION</h5>
							<p class="card-text">{{strip_tags(htmlspecialchars_decode($vision->content))}}</p>
						</div>
						<div class="card-footer mx-auto">
							<a href="{{url('/') . '/about_us/rsis'}}" class="btn btn-default">Read more</a>
						</div>
					</div>
					<div class="card">
						<div class="card-body mx-auto">
							<img src="{{url("/").'/public/images/icons_home_layer2/objectives.png'}}" alt="" class="d-block icon_home_layer2 mb-2 mx-auto img-responsive">
							<h5 class="card-title mb-2">OBJECTIVE</h5>
							<p class="card-text">{{strip_tags(strtok(htmlspecialchars_decode($objectives->content), '.'))}}.</p>
						</div>
						<div class="card-footer mx-auto">
							<a href="{{url('/') . '/about_us/objectives'}}" class="btn btn-default">Read more</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="d-block d-sm-none col-xs-12 col_no_padding">
			<div id="mission_vision">
				<div id="padding_content"></div>
				<div class="row mission_vision_mobile">
					<div class="col-6">
						<div class="card">
							<div class="card-body mx-auto">
								<img src="{{url("/").'/public/images/icons_home_layer2/mission.png'}}" alt="" class="d-block icon_home_layer2 mb-2 mx-auto img-responsive">
								<h5 class="card-title mb-2">MISSION</h5>
								<p class="card-text">{{strip_tags(str_limit(htmlspecialchars_decode($mission->content), 155, '...'))}}.</p>
							</div>
							<div class="card-footer mx-auto">
								<a href="{{url('/') . '/about_us/rsis'}}" class="btn btn-default">Read more</a>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card">
							<div class="card-body mx-auto">
								<img src="{{url("/").'/public/images/icons_home_layer2/vision.png'}}" alt="" class="d-block icon_home_layer2 mb-2 mx-auto img-responsive">
								<h5 class="card-title mb-2">VISION</h5>
								<p class="card-text">{{strip_tags(htmlspecialchars_decode($vision->content))}}</p>
							</div>
							<div class="card-footer mx-auto">
								<a href="{{url('/') . '/about_us/rsis'}}" class="btn btn-default">Read more</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- End of mission and vision section --}}
	{{-- Partners --}}
	<div class="row">
		<div class="col-12">
			<h1 id="partners_title" style="text-align: center;">PARTNERS</h1>
		</div>
	</div>
	<div class="row">
		<div class="mx-auto" id="partner_underline_top"></div>
	</div>
	<div class="row mb-5">
		<div class="mx-auto" id="partner_underline_bottom"></div>
	</div>
	<div class="row">
		<div class="col-12">
			<div id="partners">
				<div class="row">
					@foreach($partners as $partner)
						<div class="col-lg-6 col-md-12 col-sm-12">
							<div class="partner_card mb-4">
								<div class="row">
									<div class="col-5">
										<img src="{{url("/").'/public/uploads/'.$partner->logo}}" alt="{{$partner->logo}}" class="d-block mx-auto img-responsive partner_logo">
									</div>
									<div class="col-7" style="padding: 15px;">
										<h2 class="mb-3" style="margin-right: 10px;"><strong>{{$partner->short_name}}</strong></h2>
										{!!strtok(htmlspecialchars_decode($partner->description), '.')!!}.
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-12 text-center">
			<div class="mx-auto" id="partners_btn">
				<a href="{{url('/about_us/partners')}}" class="btn btn-info">Read more</a>
			</div>
		</div>
	</div>
	{{-- End of partners section --}}
	
	{{-- Login alert --}}
	@if($message = Session::get('success'))
		<div id="LoginModal" class="modal fade">
		  <div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header" style="background-color: #246e49; color: white;">
		        <h4 class="modal-title">RSIS Privacy Notice</h4>
		      </div>
		      <div class="modal-body">
		        <p>{{$message}}</p>
		        <div class="d-flex justify-content-center">
		        	<a href="{{url('/website_terms_and_conditions')}}" class="btn btn-info">Read More</a>
		        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif
@endsection

@push('scripts')
	<script type="text/javascript">
		$(window).on('load',function(){
	        $('#LoginModal').modal('show');
	    });
	</script>
@endpush