@extends('landing_page_layouts.index')

@section('content')
    {{-- Content --}}
    <div id="padding_content"></div>
    <div id="register_content" class="col_no_padding">
        <div class="row mt-5">
            <div class="col-4 mx-auto">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg text-center">Add password to activate your account</p>
                        
                        @if ($errors->has('password'))
                        <p style="color: red;">{{$errors->first('password')}}</p>
                        @endif

                        {!! Form::open(['route' => ['activate_account.update', request()->segment(count(request()->segments()))], 'method' => 'PUT']) !!}

                            <input type="hidden" name="link" value="{{$link}}">

                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8">
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        {!! Form::close() !!}
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