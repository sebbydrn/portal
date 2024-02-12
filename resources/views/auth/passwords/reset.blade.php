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
                        <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="input-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" type="email" name="email" class="form-control" placeholder="Email Address" value="{{ $email or old('email') }}" required autofocus readonly>
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

                            <div class="input-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" placeholder="Password" class="form-control" name="password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="input-group mb-3{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="row">
                                <!-- /.col -->
                                <div class="form-group">
                                    <div class="col-md-12 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Reset Password
                                        </button>
                                    </div>
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
                        <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="input-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" type="email" name="email" class="form-control" placeholder="Email Address" value="{{ $email or old('email') }}" required autofocus readonly>
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

                            <div class="input-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" placeholder="Password" class="form-control" name="password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="input-group mb-3{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="row">
                                <!-- /.col -->
                                <div class="form-group">
                                    <div class="col-md-12 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Reset Password
                                        </button>
                                    </div>
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
