<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>RSIS Portal</title>

        @include('layouts.cssLinks')

        @stack('styles')
    </head>
    <body class="hold-transition layout-top-nav">
        <div class="wrapper">
            @include('layouts.navbar')

            @include('layouts.sidebar')

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">@yield('page_title')</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    @yield('breadcrumbs')
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>
            </div>

            @include('layouts.footer')
        </div>

        @include('layouts.lockscreen')

        @include('layouts.jsLinks')

        @stack('scripts')
    </body>
</html>
