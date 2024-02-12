<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <a href="#" class="navbar-brand">
                <img src="{{asset('public/images/logo4.png')}}" alt="RSIS Logo" class="brand-image elevation-3" style="opacity: 1; background-color: white;">
                <span class="brand-text font-weight-light">Portal</span>
            </a>
        </ul>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="{{route('logout')}}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fas fa-user"></i> Logout</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</nav>
<!-- /.navbar -->
