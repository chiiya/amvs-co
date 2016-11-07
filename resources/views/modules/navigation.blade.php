<nav role="navigation" class="nav--main">
    <div class="nav-wrapper container">
        <a href="#" data-activates="nav-mobile" class="button-collapse valign-wrapper"><i class="material-icons">menu</i> AMVS-CO</a>
        <ul class="left hide-on-med-and-down">
            <li><a href="/">AMVS-CO</a></li>
            <li><a href="/">Home</a></li>
            <li><a href="/users">Users</a></li>
            <li><a href="/amvs">AMVs</a></li>
            <li><a href="/contests">Contests</a></li>
        </ul>
        <ul class="right hide-on-med-and-down">
            @if (Auth::check())
                <li><a href="/user/{{ Auth::user()->name }}">
                @if (Auth::user()->avatar)
                    <img class="avatar" src="{{ Auth::user()->avatar }}" />
                @else 
                    <img class="avatar" src="/img/avatars/default.jpg" />
                @endif
                </a> <a href="/dashboard">View Dashboard</a> | 
                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else 
                <li><a href="/login">Login</a></li>
            @endif
        </ul>

        <ul id="nav-mobile" class="side-nav">
            <li><a href="/">AMVS-CO</a></li>
            <li><a href="/">Home</a></li>
            <li><a href="/users">Users</a></li>
            <li><a href="/amvs">AMVs</a></li>
            <li><a href="/contests">Contests</a></li>
            @if (Auth::check())
                <li><a href="/user/{{ Auth::user()->name }}">
                @if (Auth::user()->avatar)
                    <img class="avatar" src="{{ Auth::user()->avatar }}" />
                @else 
                    <img class="avatar" src="/img/avatars/default.jpg" />
                @endif
                 {{ Auth::user()->name }}
                </a> <a href="/dashboard">View Dashboard</a>
                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else 
                <li><a href="/login">Login</a></li>
            @endif
        </ul>
    </div>
</nav>