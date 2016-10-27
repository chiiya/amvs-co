<nav class="nav--main">
    <ul class="container">
        <li><a href="/">AMVS-CO</a></li>
        <li><a href="/">Home</a></li>
        <li><a href="/users">Users</a></li>
        <li><a href="/amvs">AMVs</a></li>
        <li><a href="/contests">Contests</a></li>

        @if (Auth::check())
        <li><a href="/user/{{ Auth::user()->name }}"><img class="avatar" src="{{ Auth::user()->avatar }}" /></a> <a href="/dashboard">View Dashboard</a> | 
        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        @else 
        <li><a href="/login">Login</a></li>
        @endif 
    </ul>
</nav>