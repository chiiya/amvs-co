@if(isset($latest))
    @if ($latest['bg'])
    <header style="background: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url(/img/bgs/{{ $latest->bg }}) bottom right / cover no-repeat">
    @else
    <header>
    @endif
        <div class="clear"></div>
        <div class="container">
            <p>{{ $user->name . "'s" }} latest AMV:</p>
            <h1>{{ $latest->title }}</h1>

            <ul>
                <li>{{ $latest->genre }}</li>
                <li>{{ $latest->created_at->format('M Y') }}</li>
            </ul>
            <a class="button icon play" href="/user/{{ $user->name }}/{{ $latest->url }}"><span>Watch Now</span></a>
@else
    <header>
        <div class="clear"></div>
        <div class="container">
            <h1>{{ $user->name }}</h1>
            <p>No AMVs uploaded yet for this user.</p>
@endif
        </div>
    </header>