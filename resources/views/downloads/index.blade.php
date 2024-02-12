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
					    <li class="breadcrumb-item active" aria-current="page">Downloads</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>Downloads</strong></h1>
				</div>
			</div>
		</div>
	</div>
	{{-- End of header --}}
	{{-- Content --}}
	<div id="padding_content"></div>
	<div id="profile_content">
		@guest
			@foreach($downloadableCategories as $downloadableCategory)
				@if($downloadableCategory->is_public == 1)
					<h2 style="color: #246e49;"><strong>{{$downloadableCategory->display_name}}</strong></h2>
					<hr>

					<ul>
						@foreach($downloadables as $downloadable)
							@if($downloadableCategory->downloadable_category_id == $downloadable->downloadable_category_id && $downloadable->is_public == 1)
								<li><a href="{{ route('downloads.download', ['download_id' => $downloadable->downloadable_id])}}" target="_blank" style="color: #246e49;" >{{$downloadable->display_name}}</a></li>
							@endif
						@endforeach
					</ul>
				@endif
			@endforeach
		@endguest

		@auth
			@foreach($downloadableCategories as $downloadableCategory)
				<h2 style="color: #246e49;"><strong>{{$downloadableCategory->display_name}}</strong></h2>
				<hr>

				<ul>
					@foreach($downloadables as $downloadable)
						@if($downloadableCategory->downloadable_category_id == $downloadable->downloadable_category_id)
							@foreach($affiliationAccess as $item)
								@if ($item->downloadable_id == $downloadable->downloadable_id && $item->affiliation_id == $user_affiliation->affiliation_id)
									<li><a href="{{ route('downloads.download', ['download_id' => $downloadable->downloadable_id])}}" target="_blank" style="color: #246e49;" >{{$downloadable->display_name}}</a></li>
								@endif
							@endforeach
						@endif
						@if($downloadableCategory->downloadable_category_id == $downloadable->downloadable_category_id && $downloadable->is_public == 1)
							<li><a href="{{ route('downloads.download', ['download_id' => $downloadable->downloadable_id])}}" target="_blank" style="color: #246e49;" >{{$downloadable->display_name}}</a></li>
						@endif
					@endforeach
				</ul>
			@endforeach
		@endauth
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