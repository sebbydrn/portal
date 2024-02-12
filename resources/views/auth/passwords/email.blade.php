@extends('landing_page_layouts.index')

@section('content')
    <div id="padding_content"></div>
    <div id="login_content" class="col_no_padding">
        <div class="row mt-5">
            <div class="d-none d-sm-block col-4 col-xl-4 col-md-6 col-sm-8 mt-3 mx-auto">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg text-center">Reset Password</p>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}

                            @if ($errors->has('username'))
                                <span class="error invalid-feedback mb-2 d-block">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif

                            <div class="input-group mb-3">
                                <input type="email" type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="row">
                                <!-- /.col -->
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="d-block d-sm-none col-xs-12 mt-3 mx-auto">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg text-center">Reset Password</p>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}

                            @if ($errors->has('username'))
                                <span class="error invalid-feedback mb-2 d-block">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif

                            <div class="input-group mb-3">
                                <input type="email" type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="row">
                                <!-- /.col -->
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col_no_padding">
            <img src="{{url("/").'/public/images/bottom.png'}}" alt="" class="img-responsive" style="width: 100%;">
        </div>
    </div>
@endsection
