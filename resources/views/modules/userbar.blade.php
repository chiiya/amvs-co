<div class="clear"></div>
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
                @if ($user->location != null && $user->location != 'null'
                    && $user->location != '')
                <p>{{ $user->location }}</p>
                @else 
                <p>-</p>
                @endif
            </li>
            <li>
                <h3>Studio</h3>
                @if ($user->studio != null && $user->studio != 'null'
                    && $user->studio != '')
                <p>{{ $user->studio }}</p>
                @else 
                <p>-</p>
                @endif
            </li>
            <li>
                <h3>Website</h3>
                @if ($user->website != null && $user->website != 'null'
                    && $user->website != '')
                <p><a href="{{ $user->website }}">Visit Website</a></p>
                @else
                <p>-</p>
                @endif
            </li>
        </ul>
    </div>
</div>