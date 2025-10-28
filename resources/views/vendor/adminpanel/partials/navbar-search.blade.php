<form role="search" class="navbar-search">
    <div class="position-relative">
        <a href="javascript:void(0);" class="navbar-search-icon">
            <i class="ion ion-ios-search"></i>
        </a>
        <input
            type="text"
            name="search_query"
            class="form-control"
            placeholder="Type here to Search" />
        <a id="navbar_search_close" class="navbar-search-close" href="#">
            <i class="ion ion-ios-close"></i>
        </a>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle search bar
        $('#navbar_search_btn').on('click', function() {
            $('.navbar-search').addClass('navbar-search-active');
        });

        $('#navbar_search_close').on('click', function(e) {
            e.preventDefault();
            $('.navbar-search').removeClass('navbar-search-active');
        });

        // Handle search submission
        $('.navbar-search').on('submit', function(e) {
            e.preventDefault();
            const query = $(this).find('input[name="search_query"]').val();
            // Implement your search logic here
            console.log('Searching for:', query);
        });
    });
</script>
@endpush