<nav class="navbar navbar-static-top w3-container w3-theme-d3 w3-padding-16">
    <div class="container">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Branding Image -->
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a class="lang-menu" href="{{ url('/') }}">
                        <p><img src="{{asset('./img/Project.png')}}" style="height: 45px;"> </p>
                    </a>
                </li>
                <li>
                    <a class="home" href="{{ url('home') }}">
                        <p > Home</p>
                    </a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">&nbsp;
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li><a href="{{ route('login') }}">Who Are You?</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                                {{--<li class="dropdown-header">User control</li>--}}
                            </ul>
                        </li>
                        @endguest
                        <li><a href="{{ url('setlocale/en')}}" class="">EN</a></li>
                        <li><a href="{{ url('setlocale/ru')}}" class="">RU</a></li>
            </ul>
        </div>
    </div>
</nav>