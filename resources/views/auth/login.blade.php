@extends('landing_page_layouts.index')

@section('content')
    {{-- Content --}}
    <div id="padding_content"></div>
    <div id="login_content" class="col_no_padding">
        <div class="row mt-5">
            <div class="d-none d-sm-block col-4 col-xl-4 col-md-6 col-sm-8 mt-3 mx-auto">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg text-center">Log in to start your session</p>

                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            @if ($errors->has('username'))
                                <span class="error invalid-feedback mb-2 d-block">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif

                            <div class="input-group mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember">
                                        <label for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                        <p class="mb-1">
                            <a href="password/reset">I forgot my password</a>
                        </p>
                        <p class="mb-0">
                            <a href="register" class="text-center">Register a new account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
             <div class="d-block d-sm-none col-xs-12 mt-3 mx-auto">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg text-center">Log in to start your session</p>

                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            @if ($errors->has('username'))
                                <span class="error invalid-feedback mb-2 d-block">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif

                            <div class="input-group mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-7">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember">
                                        <label for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-5">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                        <p class="mb-1">
                            <a href="password/reset">I forgot my password</a>
                        </p>
                        <p class="mb-0">
                            <a href="register" class="text-center">Register a new account</a>
                        </p>
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

@push('scripts')
    <script>
        $(document).ready(()=> {
            // remove session storage locked if any
            sessionStorage.removeItem("is_locked") // Set is_locked to 0
        })
    </script>
@endpush
