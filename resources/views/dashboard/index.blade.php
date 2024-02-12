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
    <div id="dashboard_content" class="col_no_padding">
        <div class="row mt-5">

            @if (!Entrust::hasRole('seed_producer'))
                <div class="col-12 text-center">
                    <h3>THIS PAGE IS CURRENTLY UNDER CONSTRUCTION <br> PLEASE CHECK BACK SOON</h3>
                </div>
            @endif

            @auth
                @role('seed_producer')
                    {{-- If user is seed grower show this dashboard chart --}}
                    <div class="col-12">
                        <div class="card card-primary" style="border: none;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Seed Production Volume Estimates</h3>
                                        <div id="production_chart"></div>
                                        <div id="production_chart_dd"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card card-primary" style="border: none;">
                            <div class="card-body">
                                <h3>Geotagged Area</h3>
                                <div id="production_area" style="height: 600px;"></div>
                            </div>
                        </div>
                    </div>
                @endrole
            @endauth
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
    @include('dashboard.script')
@endpush