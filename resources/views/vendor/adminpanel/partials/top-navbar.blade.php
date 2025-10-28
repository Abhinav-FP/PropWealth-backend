<nav class="navbar navbar-expand-xl navbar-light fixed-top encoder-navbar encoder-navbar">
    <a id="navbar_toggle_btn" class="navbar-toggle-btn nav-link-hover" href="javascript:void(0);">
        <i class="ion ion-ios-menu"></i>
    </a>
    <a class="navbar-brand block" href="{{ route('dashboard') }}">
        <!-- <img class="w-full adminlogo d-inline-block mr-2" src="{{ asset('vendor/authentications/assets/default/Logo.png') }}" alt="brand" /> -->
        <img class="w-full adminlogo d-inline-block mr-2" src="https://propwealth.com.au/wp-content/uploads/Propwealth-1.svg" alt="brand" />
    </a>
    <ul class="navbar-nav encoder-navbar-content">
        <li class="nav-item">
            <a id="navbar_search_btn" class="nav-link nav-link-hover" href="javascript:void(0);">
                <i class="ion ion-ios-search d-none"></i>
            </a>
        </li>
        @include('adminpanel::partials.user-dropdown')
    </ul>
</nav>