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
					    <li class="breadcrumb-item active" aria-current="page">Helpdesk</li>
					  </ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center">
					<h1><strong>Helpdesk</strong></h1>
				</div>
			</div>
		</div>
	</div>
	{{-- End of header --}}
	{{-- Content --}}
	<div id="helpdesk_content" class="col_no_padding">
		<div class="row mt-5">
			<div class="col-12">
				@if($_SERVER['SERVER_NAME'] == "stagingdev.philrice.gov.ph")
	              <iframe src="https://stagingdev.philrice.gov.ph/rsis/helpdesk" frameborder="0" scrolling="yes" onload="resizeIframe(this)"></iframe>
	            @elseif($_SERVER['SERVER_NAME'] == "rsis.philrice.gov.ph")
	              <iframe src="https://rsis.philrice.gov.ph/helpdesk" frameborder="0" scrolling="yes" onload="resizeIframe(this)"></iframe>
	            @else
	              <iframe src="https://rsis.philrice.gov.ph/helpdesk" frameborder="0" scrolling="yes" onload="resizeIframe(this)"></iframe>
	            @endif
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
	<script>
		function resizeIframe(obj) {
			obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
		}
	</script>
@endpush