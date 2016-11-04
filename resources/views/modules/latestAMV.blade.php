@if(isset($latest))
    @if ($latest['bg'])
    <header style="background: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url({{ $latest->bg }}) bottom right / cover no-repeat">
    @else
    <header>
    @endif
        <div class="clear"></div>
        <div class="container">
            <p>Latest AMV:</p>
            <h1>{{ $latest->title }}</h1>

            <ul>
                @if (!$latest->genres->isEmpty())
                <li>
                    @foreach ($latest->genres as $genre)
                        @if ($loop->last)
                            {{ $genre->name }}
                        @else
                            {{ $genre->name . ' - ' }}
                        @endif
                    @endforeach
                </li>
                @endif
                <li>{{ $latest->created_at->format('M Y') }}</li>
            </ul>
            <a class="button button--rounded button--secondary icon play" 
                href="/user/{{ $user->name }}/{{ $latest->url }}">
                <i class="material-icons">play_circle_outline</i>
                Watch Now
            </a>
@else
    <header>
        <div class="clear"></div>
        <div class="container">
            <h1>{{ $user->name }}</h1>
            <p>No AMVs uploaded yet for this user.</p>
@endif
        </div>
    </header>