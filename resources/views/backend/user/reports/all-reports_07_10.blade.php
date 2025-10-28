@extends('adminpanel::master')

@section('title', 'Downloaded Reports')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center mt-5">
    <div class="col-xl-12">
      <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center p-4">
          <h5 class="card-title fw-bold mb-0 text-muted">Downloaded Reports</h5>
          <button id="exportToExcel" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
            <i class="fas fa-file-excel me-2"></i> Export to Excel
          </button>
        </div>
        <div class="card-body p-4">
          <table class="table table-hover w-100" id="reportsTable">
            <thead class="bg-light">
              <tr>
                <th class="border-top-0">ID</th>
                <th class="border-top-0">Name</th>
                <th class="border-top-0">Email</th>
                <th class="border-top-0">Mobile</th>
                <th class="border-top-0">Location</th>
                <th class="border-top-0">Downloaded At</th>
                <th class="border-top-0">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($reports as $report)
              <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->user->first_name }} {{ $report->user->last_name }}</td>
                <td>{{ $report->user->email }}</td>
                <td>{{ $report->user->mobile_number }}</td>
                <td>{{ $report->suburb_name }}</td>
                <td data-sort="{{ $report->created_at->timestamp }}">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                <td>
                  <a href="{{ route('report.download', $report->id) }}" class="btn btn-sm btn-primary rounded-pill">
                    <i class="fas fa-download"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Hidden form for export -->
<form id="exportForm" action="{{ route('reports.export-filtered') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="search" id="exportSearch">
    <input type="hidden" name="start_date" id="exportStartDate">
    <input type="hidden" name="end_date" id="exportEndDate">
    <input type="hidden" name="location" id="exportLocation">
    <input type="hidden" name="email" id="exportEmail">
    <input type="hidden" name="mobile" id="exportMobile">
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
</style>
@endpush

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#reportsTable').DataTable({
        "order": [[ 4, "desc" ]], // Default sort by the Date column (index 4) in descending order
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        "language": {
            "search": "Search reports:",
            "lengthMenu": "Show _MENU_ reports per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ reports",
            "infoEmpty": "No reports found",
            "infoFiltered": "(filtered from _MAX_ total reports)"
        }
    });
    
    // Export to Excel functionality
    $('#exportToExcel').click(function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var originalText = $btn.html();
        
        // Show loading state
        $btn.addClass('btn-loading').html('<i class="fas fa-spinner fa-spin me-2"></i> Exporting...');
        
        // Get current search term
        var searchValue = table.search();
        
        // Set the hidden form values
        $('#exportSearch').val(searchValue);
        
        // You can add more filters here if needed
        // $('#exportStartDate').val(someStartDateValue);
        // $('#exportEndDate').val(someEndDateValue);
        // $('#exportLocation').val(someLocationValue);
        // $('#exportEmail').val(someEmailValue);
        
        // Create a temporary form and submit it
        var form = $('#exportForm');
        
        // Change form method to GET for simplicity (or keep POST if you prefer)
        form.attr('method', 'GET');
        
        // Submit the form
        form.submit();
        
        // Reset button state after a short delay
        setTimeout(function() {
            $btn.removeClass('btn-loading').html(originalText);
        }, 2000);
    });
});
</script>
@endpush