@extends('landing_page_layouts.index')

@push('styles')
	{{-- Slickmap --}}
	<link rel="stylesheet" type="text/css" media="screen,print" href="{{asset('public/assets/slickmap/slickmap.css')}}">
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
					    <li class="breadcrumb-item active" aria-current="page">Sitemap</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>Sitemap</strong></h1>
				</div>
			</div>
		</div>
	</div>
	{{-- End of header --}}
	{{-- Content --}}
	<div id="sitemap_content" class="col_no_padding">
		<div class="row mt-5">
			<div class="col-12 d-none d-md-block d-lg-block d-xl-block">
				@guest
					<div class="sitemap">
						<nav class="utilityNav">
							<ul>
								@foreach($pages as $page)
									@if($page->url == "/login" || $page->url == "/register")
										<li><a href="{{url('/').$page->url}}">{{$page->display_name}}</a>
									@endif
								@endforeach
							</ul>
						</nav>

						<nav class="primaryNav">
							<ul>
								@foreach($pages as $page)
									@if($page->is_public && $page->url != "/login" && $page->url != "/register")
										<li {{($page->url == '/') ? 'id=home' : ''}}><a href="{{url('/').$page->url}}">{{$page->display_name}}</a>
											<ul>
											@foreach($sections as $section)
												@if($section->page_id == $page->page_id && $section->is_public == 1)
													<li><a href="{{url('/').$page->url.$section->url}}">{{$section->display_name}}</a></li>
												@endif
											@endforeach
											</ul>
										</li>
									@endif
								@endforeach
							</ul>
						</nav>
					</div>
				@endguest

				@auth
					<div class="sitemap">
						<nav class="primaryNav">
							<ul>
								@foreach($pages as $page)
									@if($page->url != "/login" && $page->url != "/register")
										<li {{($page->url == '/') ? 'id=home' : ''}}><a href="{{url('/').$page->url}}">{{$page->display_name}}</a>
											<ul>
											@foreach($sections as $section)
												@if($section->page_id == $page->page_id)
													@if($section->url == "/settings")
														@permission('view_settings')
															<li><a href="{{url('/').$page->url.$section->url}}">{{$section->display_name}}</a></li>
														@endpermission
													@else
														<li><a href="{{url('/').$page->url.$section->url}}">{{$section->display_name}}</a></li>
													@endif
												@endif
											@endforeach
											</ul>
										</li>
									@endif
								@endforeach
							</ul>
						</nav>
					</div>
				@endauth
			</div>

			<div class="col-12 d-none d-sm-block d-xs-block d-md-none d-lg-none d-xl-none">
				@guest
					@foreach($pages as $page)
						@if($page->is_public && $page->url != "/register")
							<h4><a href="{{url('/').$page->url}}" style="color: #246e49;">{{$page->display_name}}</a></h4>
							<ul style="margin-left: 30px;">
							@foreach($sections as $section)
								@if($section->page_id == $page->page_id && $section->is_public == 1)
									<li><a href="{{url('/').$page->url.$section->url}}" style="color: #246e49;">{{$section->display_name}}</a></li>
								@endif
							@endforeach
							</ul>
						@endif
					@endforeach
				@endguest

				@auth
					@foreach($pages as $page)
						@if($page->url != "/login" && $page->url != "/register")
							<h4><a href="{{url('/').$page->url}}" style="color: #246e49;">{{$page->display_name}}</a></h4>
							<ul style="margin-left: 30px;">
							@foreach($sections as $section)
								@if($section->page_id == $page->page_id)
									@if($section->url == "/settings")
										@permission('view_settings')
											<li><a href="{{url('/').$page->url.$section->url}}" style="color: #246e49;">{{$section->display_name}}</a></li>
										@endpermission
									@else
										<li><a href="{{url('/').$page->url.$section->url}}" style="color: #246e49;">{{$section->display_name}}</a></li>
									@endif
								@endif
							@endforeach
							</ul>
						@endif
					@endforeach
				@endauth
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