@extends('adminpanel::master')

@section('title', 'Downloaded Reports')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center mt-5">
    <div class="col-xl-12">
      <div class="card shadow-sm rounded-lg border-0">
        
        <div class="card-header bg-white d-flex justify-content-between align-items-center p-4">
          <h5 class="card-title fw-bold mb-0 text-muted">Downloaded Reports</h5>
          <div class="d-flex align-items-center gap-2">
            <input type="text" id="quickSearch" class="form-control form-control-sm" placeholder="Quick search..." style="width: 200px;">
            <button id="showFilterModal" class="ml-3 btn btn-secondary btn-sm rounded-pill px-4 shadow-sm">
              <i class="fas fa-filter me-2"></i> Filters
            </button>
            <button id="clearFilters" class="ml-3 btn btn-outline-secondary btn-sm rounded-pill px-4 shadow-sm" style="display:none;">
              <i class="fas fa-times me-2"></i> Clear Filters
            </button>
            <button id="exportToExcel" class="ml-3 btn btn-success btn-sm rounded-pill px-4 shadow-sm">
              <i class="fas fa-file-excel me-2"></i> Export to Excel
            </button>
          </div>
        </div>

        <!-- Active Filters Display -->
        <div id="activeFiltersDisplay" class="px-4 pt-3" style="display:none;">
          <div class="alert alert-info mb-0 py-2">
            <strong><i class="fas fa-filter me-2"></i>Active Filters:</strong>
            <span id="filterSummary"></span>
          </div>
        </div>

        <div class="card-body p-4">
          <table class="table table-hover w-100" id="reportsTable">
            <thead class="bg-light">
              <tr>
                <th class="border-top-0 sortable" data-sort="id">
                  ID 
                  <i class="fas fa-sort sort-icon" id="sort-id"></i>
                </th>
                <th class="border-top-0 sortable" data-sort="user.first_name">
                  Name 
                  <i class="fas fa-sort sort-icon" id="sort-user.first_name"></i>
                </th>
                <th class="border-top-0 sortable" data-sort="user.email">
                  Email 
                  <i class="fas fa-sort sort-icon" id="sort-user.email"></i>
                </th>
                <th class="border-top-0 sortable" data-sort="user.mobile_number">
                  Mobile 
                  <i class="fas fa-sort sort-icon" id="sort-user.mobile_number"></i>
                </th>
                <th class="border-top-0 sortable" data-sort="suburb_name">
                  Location 
                  <i class="fas fa-sort sort-icon" id="sort-suburb_name"></i>
                </th>
                <th class="border-top-0 sortable" data-sort="created_at">
                  Downloaded At 
                  <i class="fas fa-sort sort-icon" id="sort-created_at"></i>
                </th>
                <!-- <th class="border-top-0">Actions</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach($reports as $report)
              <tr data-state="{{ $report->state ?? '' }}" data-postcode="{{ $report->post_code ?? '' }}">
                <td>{{ $report->id }}</td>
                <td>{{ $report->user ? $report->user->first_name . ' ' . $report->user->last_name : 'N/A' }}</td>
                <td>{{ $report->user->email ?? 'N/A' }}</td>
                <td>{{ $report->user->mobile_number ?? 'N/A' }}</td>
                <td>{{ $report->suburb_name ?? 'N/A' }}</td>
                <td data-sort="{{ $report->created_at->timestamp }}">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                <!-- <td>
                  <a href="{{ route('report.download', $report->id) }}" class="btn btn-sm btn-primary rounded-pill" title="Download Report">
                    <i class="fas fa-download"></i>
                  </a>
                </td> -->
              </tr>
              @endforeach
            </tbody>
          </table>
          
          <!-- Pagination -->
          @if ($reports->hasPages())
          <div class="d-flex justify-content-center mt-4">
            {{ $reports->appends(request()->query())->links() }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!--- Filter Modal --->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterModalLabel">Filter Reports</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
      </div>
      
      <div class="modal-body">  
        <form id="filterForm">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate" name="start_date">
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate" name="end_date">
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="emailFilter" class="form-label">Email</label>
              <input type="email" class="form-control" id="emailFilter" name="email" placeholder="user@example.com">
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="mobileFilter" class="form-label">Mobile Number</label>
              <input type="text" class="form-control" id="mobileFilter" name="mobile" placeholder="0400000000">
            </div>
          </div>
          
          
          <div class="mb-3">
            <label for="suburbFilter" class="form-label">Suburb Name</label>
            <input type="text" class="form-control" id="suburbFilter" name="location" placeholder="e.g., Sydney">
          </div>
          
          <div class="mb-3">
            <label for="generalSearch" class="form-label">General Search</label>
            <input type="text" class="form-control" id="generalSearch" name="search" placeholder="Search across all fields...">
            <div class="form-text">Searches across name, email, mobile, and suburb fields</div>
          </div>
        </form>  
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="applyFilters">Apply Filters</button>
      </div>
    </div>
  </div>
</div>

<!--- Search History Modal --->
<div class="modal fade" id="searchHistoryModal" tabindex="-1" aria-labelledby="searchHistoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchHistoryModalLabel">User Search History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div id="searchHistoryContent">
          <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Loading search history...</p>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Hidden form for export -->
<form id="exportForm" action="{{ route('reports.export-filtered') }}" method="GET" style="display: none;">
    <input type="hidden" name="search" id="exportSearch">
    <input type="hidden" name="start_date" id="exportStartDate">
    <input type="hidden" name="end_date" id="exportEndDate">
    <input type="hidden" name="location" id="exportLocation">
    <input type="hidden" name="email" id="exportEmail">
    <input type="hidden" name="mobile" id="exportMobile">
    <input type="hidden" name="sort_by" id="exportSortBy">
    <input type="hidden" name="sort_dir" id="exportSortDir">
</form>
@endsection

@push('styles')
<style>
    /* Pagination Container */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1rem;
    }

    /* Common Button Styles */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #495057;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    /* Hover and Focus States */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
        background-color: #e9ecef;
        border-color: #ced4da;
        color: #212529;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        cursor: pointer;
    }

    /* Active State */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
    .dataTables_wrapper { 
      overflow: auto;
    }

    /* Disabled State */
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #adb5bd;
        background-color: #e9ecef;
        border-color: #e9ecef;
        cursor: not-allowed;
    }

    /* Info and Length Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-bottom: 1rem;
    }

    .dataTables_paginate ul li {
      padding: 5px 10px !important;
      background: #fff;
      border: 1px solid #ddd;
    }

    /* Loading state for export button */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: button-loading-spinner 1s ease infinite;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
    
    @keyframes button-loading-spinner {
        from {
            transform: rotate(0turn);
        }
        to {
            transform: rotate(1turn);
        }
    }

    /* Search History Styles */
    .search-history-item {
        border-left: 3px solid #0d6efd;
        transition: all 0.2s;
    }
    
    .search-history-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0a58ca;
    }
    
    /* Sortable header styles */
    .sortable {
        cursor: pointer;
        user-select: none;
        position: relative;
        transition: background-color 0.2s;
    }
    
    .sortable:hover {
        background-color: #e9ecef;
    }
    
    .sort-icon {
        margin-left: 5px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    
    .sortable:hover .sort-icon {
        opacity: 0.8;
    }
    
    .sortable.sorted .sort-icon {
        opacity: 1;
        color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
$(document).ready(function() {
    // URL parameter management
    const urlParams = new URLSearchParams(window.location.search);
    
    // Initialize DataTable without pagination since we use server-side pagination
    var table = $('#reportsTable').DataTable({
        "paging": false, // Disable DataTables pagination
        "info": false, // Disable DataTables info display
        "lengthChange": false, // Disable page length dropdown
        "searching": false, // Disable default search since we'll use server-side filtering
        "ordering": false, // Disable DataTables sorting since we handle it server-side
        "dom": 't' // Only show table, no other controls
    });
    
    // Initialize Bootstrap Modals
    const filterModal = new bootstrap.Modal(document.getElementById('filterModal'));
    const searchHistoryModal = new bootstrap.Modal(document.getElementById('searchHistoryModal'));

    // Global filter and sort storage
    let activeFilters = {
        search: urlParams.get('search') || '',
        start_date: urlParams.get('start_date') || '',
        end_date: urlParams.get('end_date') || '',
        location: urlParams.get('location') || '',
        email: urlParams.get('email') || '',
        mobile: urlParams.get('mobile') || ''
    };
    
    let currentSort = {
        by: urlParams.get('sort_by') || 'created_at',
        dir: urlParams.get('sort_dir') || 'desc'
    };
    
    // Load filter values into form on page load
    if (activeFilters.search) $('#generalSearch').val(activeFilters.search);
    if (activeFilters.start_date) $('#startDate').val(activeFilters.start_date);
    if (activeFilters.end_date) $('#endDate').val(activeFilters.end_date);
    if (activeFilters.location) $('#suburbFilter').val(activeFilters.location);
    if (activeFilters.email) $('#emailFilter').val(activeFilters.email);
    if (activeFilters.mobile) $('#mobileFilter').val(activeFilters.mobile);
    
    // Update sort indicators
    updateSortIndicators();
    
    // Update filter display on page load
    updateFilterDisplay();

    // Update sort indicators based on current sort
    function updateSortIndicators() {
        $('.sortable').removeClass('sorted');
        $('.sort-icon').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        
        if (currentSort.by) {
            const sortedHeader = $(`.sortable[data-sort="${currentSort.by}"]`);
            sortedHeader.addClass('sorted');
            const icon = sortedHeader.find('.sort-icon');
            icon.removeClass('fa-sort');
            icon.addClass(currentSort.dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
        }
    }
    
    // Update URL with current filters and sort
    function updateURL() {
        const params = new URLSearchParams();
        
        // Add filters
        Object.keys(activeFilters).forEach(key => {
            if (activeFilters[key]) {
                params.set(key, activeFilters[key]);
            }
        });
        
        // Add sort
        if (currentSort.by !== 'created_at' || currentSort.dir !== 'desc') {
            params.set('sort_by', currentSort.by);
            params.set('sort_dir', currentSort.dir);
        }
        
        // Update URL without page refresh
        const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newURL);
    }
    
    // Reload page with current parameters
    function reloadWithFilters() {
        updateURL();
        window.location.reload();
    }
    
    // Handle sortable header clicks
    $('.sortable').on('click', function() {
        const sortField = $(this).data('sort');
        
        if (currentSort.by === sortField) {
            // Toggle direction
            currentSort.dir = currentSort.dir === 'asc' ? 'desc' : 'asc';
        } else {
            // New field
            currentSort.by = sortField;
            currentSort.dir = 'asc';
        }
        
        updateSortIndicators();
        reloadWithFilters();
    });
    
    // Quick search functionality
    let searchTimeout;
    $('#quickSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();
        
        searchTimeout = setTimeout(function() {
            activeFilters.search = searchValue;
            $('#generalSearch').val(searchValue);
            reloadWithFilters();
        }, 500); // 500ms delay to avoid too many requests
    });
    
    // Load quick search value from current filters
    if (activeFilters.search) {
        $('#quickSearch').val(activeFilters.search);
    }
    
    // Show the filter modal
    $('#showFilterModal').on('click', function() {
        filterModal.show();
    });


    // Update filter display
    function updateFilterDisplay() {
        var filterCount = 0;
        var filterText = [];
        
        if (activeFilters.search) {
            filterText.push('Search: ' + activeFilters.search);
            filterCount++;
        }
        if (activeFilters.start_date) {
            filterText.push('From: ' + activeFilters.start_date);
            filterCount++;
        }
        if (activeFilters.end_date) {
            filterText.push('To: ' + activeFilters.end_date);
            filterCount++;
        }
        if (activeFilters.email) {
            filterText.push('Email: ' + activeFilters.email);
            filterCount++;
        }
        if (activeFilters.mobile) {
            filterText.push('Mobile: ' + activeFilters.mobile);
            filterCount++;
        }
        if (activeFilters.location) {
            filterText.push('Suburb: ' + activeFilters.location);
            filterCount++;
        }
        
        // Show sort info if not default
        if (currentSort.by !== 'created_at' || currentSort.dir !== 'desc') {
            filterText.push(`Sorted by: ${currentSort.by} (${currentSort.dir.toUpperCase()})`);
        }
        
        if (filterCount > 0 || (currentSort.by !== 'created_at' || currentSort.dir !== 'desc')) {
            $('#filterSummary').html(filterText.join(' | '));
            $('#activeFiltersDisplay').show();
            $('#clearFilters').show();
        } else {
            $('#activeFiltersDisplay').hide();
            $('#clearFilters').hide();
        }
    }

    // Apply filters button click
    $('#applyFilters').on('click', function() {
        // Capture all filter values from the modal form
        activeFilters.search = $('#generalSearch').val();
        activeFilters.start_date = $('#startDate').val();
        activeFilters.end_date = $('#endDate').val();
        activeFilters.location = $('#suburbFilter').val();
        activeFilters.email = $('#emailFilter').val();
        activeFilters.mobile = $('#mobileFilter').val();

        // Hide modal and reload with new filters
        filterModal.hide();
        reloadWithFilters();
    });

    // Clear filters button
    $('#clearFilters').on('click', function() {
        // Reset all filters
        activeFilters = {
            search: '',
            start_date: '',
            end_date: '',
            location: '',
            email: '',
            mobile: ''
        };
        
        // Reset sort to default
        currentSort = {
            by: 'created_at',
            dir: 'desc'
        };
        
        // Clear all form inputs
        $('#filterForm')[0].reset();
        $('#quickSearch').val('');
        
        // Reload page without any parameters
        window.location.href = window.location.pathname;
    });

    // Export to Excel functionality
    $('#exportToExcel').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var originalText = $btn.html();
        
        // Show loading state
        $btn.addClass('btn-loading').html('<i class="fas fa-spinner fa-spin me-2"></i> Exporting...');
        
        // Set all current filter values in the export form
        $('#exportSearch').val(activeFilters.search);
        $('#exportStartDate').val(activeFilters.start_date);
        $('#exportEndDate').val(activeFilters.end_date);
        $('#exportLocation').val(activeFilters.location);
        $('#exportEmail').val(activeFilters.email);
        $('#exportMobile').val(activeFilters.mobile);
        $('#exportSortBy').val(currentSort.by);
        $('#exportSortDir').val(currentSort.dir);
        
        // Submit the form
        var form = $('#exportForm');
        form.submit();
        
        // Reset button state after a short delay
        setTimeout(function() {
            $btn.removeClass('btn-loading').html(originalText);
        }, 3000); 
    });

    // View search history button
    $('.view-searches').on('click', function() {
        var userId = $(this).data('user-id');
        
        // Show modal with loading state
        searchHistoryModal.show();
        $('#searchHistoryContent').html(`
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2">Loading search history...</p>
            </div>
        `);
        
        // Fetch search history via AJAX
        $.ajax({
            url: '/admin/user/' + userId + '/search-history',
            method: 'GET',
            success: function(response) {
                if (response.searches && response.searches.length > 0) {
                    var html = '<div class="list-group">';
                    response.searches.forEach(function(search) {
                        html += `
                            <div class="list-group-item search-history-item mb-2 rounded">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        ${search.suburb_name || 'Unknown Location'}
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        ${search.searched_at}
                                    </small>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-secondary me-2">
                                        <i class="fas fa-map-pin me-1"></i>
                                        ${search.state || 'N/A'}
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-hashtag me-1"></i>
                                        ${search.post_code || 'N/A'}
                                    </span>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $('#searchHistoryContent').html(html);
                } else {
                    $('#searchHistoryContent').html(`
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No search history found for this user.
                        </div>
                    `);
                }
            },
            error: function() {
                $('#searchHistoryContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading search history. Please try again.
                    </div>
                `);
            }
        });
    });
});
</script>
@endpush