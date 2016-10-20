<div class="userbar">
    <div class="container">
        <ul>
            <li>
                @if ($user->avatar)
                <img class="img--circle" src="{{ $user->avatar }}">
                @else 
                <img class="img--circle" src="/img/avatars/default.jpg">
                @endif
                 {{ $user->name }}
            </li>
            <li>
                <h3>Location</h3>
                <p>{{ $user->location or '-' }}</p>
            </li>
            <li>
                <h3>Studio</h3>
                <p>{{ $user->studio or '-'}}</p>
            </li>
            <li>
                <h3>Website</h3>
                @if ($user->website)
                <p><a href="{{ $user->website }}">Visit Website</a></p>
                @else
                <p>-</p>
                @endif
            </li>
        </ul>
    </div>
</div>