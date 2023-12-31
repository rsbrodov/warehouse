<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-black navbar-light d-flex justify-content-between" style="height: 57px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars fa-lg" aria-hidden="true"></i></a>
        </li> --}}


        <li class="nav-item d-none d-sm-inline-block title">
            @if(App\Models\UrlTitle::where('url', Route::current()->getName())->first())
                {{App\Models\UrlTitle::where('url', Route::current()->getName())->first()->title}}
            @else
                {{Route::current()->getName()}}
            @endif
        </li>
{{--        <li class="nav-item d-none d-sm-inline-block">--}}
{{--            <a href="/home" class="nav-link">Кабинет</a>--}}
{{--        </li>--}}
{{--        <li class="nav-item d-none d-sm-inline-block">--}}
{{--            <a href="/" class="nav-link">На сайт</a>--}}
{{--        </li>--}}




        {{-- <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Выход') }}
            </a>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form> --}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav">
        <!-- User Profile -->
        <li class="user-panel">
            <div class="d-flex ">
                <div class="mr-2" ><span class="fa fa-question-circle-o fa-lg" aria-hidden="true"></span></div>
                <div class="mr-2"><a href="{{route('users.profile')}}" class="d-block">{{Auth::guard('web')->user()->name}}</a></div>
                <div class="mr-2"><img src="{{asset('/storage/' . Auth::guard('web')->user()->photo)}}" class="img-circle elevation-2" alt="User Image"></div>
            </div>

        </li>
        <!-- Navbar Search -->
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-widget="navbar-search" href="#" role="button">--}}
{{--                <i class="fa fa-search fa-lg" aria-hidden="true"></i>--}}
{{--            </a>--}}
{{--            <div class="navbar-search-block">--}}
{{--                <form class="form-inline">--}}
{{--                    <div class="input-group input-group-sm">--}}
{{--                        <input class="form-control form-control-navbar" type="search" placeholder="Search"--}}
{{--                               aria-label="Search">--}}
{{--                        <div class="input-group-append">--}}
{{--                            <button class="btn btn-navbar" type="submit">--}}
{{--                                <i class="fa fa-search fa-lg" aria-hidden="true"></i>--}}
{{--                            </button>--}}
{{--                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">--}}
{{--                                <i class="fa fa-times fa-lg" aria-hidden="true"></i>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </li>--}}

{{--        <!-- Messages Dropdown Menu -->--}}
{{--        <li class="nav-item dropdown">--}}
{{--            <a class="nav-link" data-toggle="dropdown" href="#">--}}
{{--                <i class="fa fa-comments fa-lg" aria-hidden="true"></i>--}}
{{--                <span class="badge badge-danger navbar-badge">3</span>--}}
{{--            </a>--}}
{{--            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <!-- Message Start -->--}}
{{--                    <div class="media">--}}
{{--                        <img src="{{asset('dist/img/user1-128x128.jpg')}}" alt="User Avatar"--}}
{{--                             class="img-size-50 mr-3 img-circle">--}}
{{--                        <div class="media-body">--}}
{{--                            <h3 class="dropdown-item-title">--}}
{{--                                Brad Diesel--}}
{{--                                <span class="float-right text-sm text-danger"><i class="fa fa-star fa-lg" aria-hidden="true"></i></span>--}}
{{--                            </h3>--}}
{{--                            <p class="text-sm">Call me whenever you can...</p>--}}
{{--                            <p class="text-sm text-muted"> <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> 4 Hours Ago</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Message End -->--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <!-- Message Start -->--}}
{{--                    <div class="media">--}}
{{--                        <img src="{{asset('dist/img/user8-128x128.jpg')}}" alt="User Avatar"--}}
{{--                             class="img-size-50 img-circle mr-3">--}}
{{--                        <div class="media-body">--}}
{{--                            <h3 class="dropdown-item-title">--}}
{{--                                John Pierce--}}
{{--                                <span class="float-right text-sm text-muted"><i class="fa fa-star fa-lg" aria-hidden="true"></i></span>--}}
{{--                            </h3>--}}
{{--                            <p class="text-sm">I got your message bro</p>--}}
{{--                            <p class="text-sm text-muted"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> 4 Hours Ago</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Message End -->--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <!-- Message Start -->--}}
{{--                    <div class="media">--}}
{{--                        <img src="{{asset('dist/img/user3-128x128.jpg')}}" alt="User Avatar"--}}
{{--                             class="img-size-50 img-circle mr-3">--}}
{{--                        <div class="media-body">--}}
{{--                            <h3 class="dropdown-item-title">--}}
{{--                                Nora Silvester--}}
{{--                                <span class="float-right text-sm text-warning"><i class="fa fa-star fa-lg" aria-hidden="true"></i></span>--}}
{{--                            </h3>--}}
{{--                            <p class="text-sm">The subject goes here</p>--}}
{{--                            <p class="text-sm text-muted"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> 4 Hours Ago</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Message End -->--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--        <!-- Notifications Dropdown Menu -->--}}
{{--        <li class="nav-item dropdown">--}}
{{--            <a class="nav-link" data-toggle="dropdown" href="#">--}}
{{--                <i class="fa fa-bell fa-lg" aria-hidden="true"></i>--}}
{{--                <span class="badge badge-warning navbar-badge">15</span>--}}
{{--            </a>--}}
{{--            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
{{--                <span class="dropdown-header">15 Notifications</span>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fa fa-envelope fa-lg" aria-hidden="true"></i> 4 new messages--}}
{{--                    <span class="float-right text-muted text-sm">3 mins</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fa fa-users fa-lg" aria-hidden="true"></i> 8 friend requests--}}
{{--                    <span class="float-right text-muted text-sm">12 hours</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fa fa-file fa-lg" aria-hidden="true"></i> 3 new reports--}}
{{--                    <span class="float-right text-muted text-sm">2 days</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-widget="fullscreen" href="#" role="button">--}}
{{--                <i class="fa fa-expand fa-lg" aria-hidden="true"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">--}}
{{--                <i class="fa fa-th-large fa-lg" aria-hidden="true"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
</nav>
<!-- /.navbar -->
<style>
    .title{
        padding-top: 5px;
        padding-left: 10px;
        font-family: 'Roboto';
        font-style: italic;
        font-weight: 400;
        font-size: 30px;
        line-height: 23px;
        color: #716464;
    }
    
    

</style>
