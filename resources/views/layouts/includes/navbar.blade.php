<nav class="navbar navbar-light navbar-expand-sm" style="background:#f39200">
    @guest
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav mr-auto">
        </ul>
    @else
    <a class="navbar-brand" href="/harjoitustyo/public/home">
        <span>Home</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
            <a class="nav-link rounded" href="/harjoitustyo/public/dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
            <a class="nav-link rounded" href="/harjoitustyo/public/import">Import</a>
            </li>
            <li class="nav-item">
            <a class="nav-link rounded" href="/harjoitustyo/public/export">Export (WIP)</a>
            </li>
            <li class="nav-item">
            <a class="nav-link rounded" href="/harjoitustyo/public/addnewitem">Add new item</a>
            </li>
        </ul>
    @endguest
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link rounded" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link rounded" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link rounded dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span>{{ Auth::user()->name }}</span> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background:#f39200">
                        <a class="dropdown-item" href="#" disabled>
                            <span><del>Account settings</del> (WIP)</span>
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>  
</nav>
