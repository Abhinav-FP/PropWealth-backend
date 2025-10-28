<li class="nav-item dropdown dropdown-authentication">
    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="media">
            <div class="media-img-wrap">
                <div class="avatar">
                    <img src="{{ asset('public/admin/assets/default/default.png') }}" alt="user" class="avatar-img rounded-circle" />
                </div>
                <span class="badge badge-success badge-indicator"></span>
            </div>
            <div class="media-body">
                @if(Auth::user()->role_id == 1)
                <span> Admin <i class="fa fa-angle-down"></i></span>
                @else
                <span> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <i class="fa fa-angle-down"></i></span>
                @endif
            </div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
        <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->id) }}">
            <i class="fa fa-user-o"></i> <span>Profile</span>
        </a>
        <div class="dropdown-divider"></div>
        @auth
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">Logout</button>
        </form>
        @endauth
    </div>
</li>