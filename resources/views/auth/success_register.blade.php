@extends('landing_page_layouts.index')

@section('content')
    {{-- Content --}}
    <div id="padding_content"></div>
    <div id="register_content" class="col_no_padding">
        <div class="row mt-5 mx-auto">
            <div class="col-12">
                <div class="register-box">
                    <div class="card" style="border: none;">
                        <div class="card-body register-card-body">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <img src="{{url('/').'/public/images/check_circle_green.png'}}" alt="" style="height: 80px;" class="d-flex mx-auto">
                                    <h2>Thank you for registering!</h2>
                                    <p>You have successfully registered. Please check your email for further notifications from the admin.</p>
                                </div>
                            </div>
                        </div>
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