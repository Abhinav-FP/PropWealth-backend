<nav class="encoder-nav encoder-nav-light">
    <a href="javascript:void(0);" id="encoder_nav_close" class="encoder-nav-close">
        <span class="feather-icon"><i data-feather="x"></i></span>
    </a>
    <div class="nicescroll-bar">
        <div class="navbar-nav-wrap">
            <ul class="navbar-nav flex-column">
                <!-- <li class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="ion ion-ios-keypad"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li> -->

                @if (auth()->user()->role_id === 1)
                <li class="nav-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('report.all-reports') }}">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>

                        <span class="nav-link-text">Downloaded Reports</span>
                    </a>
                </li>
                @endif

                @if (auth()->user()->role_id === 1)
                <!-- <li class="nav-item {{request()->routeIs('user.*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="ion ion-ios-person"></i>
                        <span class="nav-link-text">user</span>
                    </a>
                </li> -->

                <li class="nav-item @if(request()->routeIs('download-limit.*')) active @endif">
                    <a class="nav-link" href="{{ route('downloadLimit.index') }}">
                        <i class="ion ion-ios-download"></i>
                        <span class="nav-link-text">Downloads Limit</span>
                    </a>
                </li>


                <li class="d-none nav-item {{ request()->routeIs('suburbs.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('suburbs.adminIndex') }}">
                        <i class="ion ion-ios-list"></i>
                        <span class="nav-link-text">Manage Suburbs</span>
                    </a>
                </li>

                <li class="d-none nav-item {{ request()->routeIs('states.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('states.index') }}">
                        <i class="ion ion-ios-map"></i>
                        <span class="nav-link-text">Manage States</span>
                    </a>
                </li>
                @endif

            </ul>

            <hr class="nav-separator" />
            <div class="nav-header">
                <span>Getting Started</span>
                <span>GS</span>
            </div>

            <ul class="navbar-nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="https://propwealth.com.au/">
                        <i class="ion ion-ios-book"></i>
                        <span class="nav-link-text">View Site</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>