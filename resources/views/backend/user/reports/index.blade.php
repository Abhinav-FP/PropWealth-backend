@extends('adminpanel::master')

@section('title', 'Downloaded Reports')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center mt-5">
    <div class="col-xl-12">
      <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center p-4">
          <h5 class="card-title fw-bold mb-0 text-muted">Downloaded Reports</h5>
          <a href="{{ route('report.export', $user->id) }}" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
            <i class="fas fa-file-excel me-2"></i> Export to Excel
          </a>
        </div>
        <div class="card-body p-4">
          <table class="table table-hover w-100" id="reportsTable">
            <thead class="bg-light">
              <tr>
                <th class="border-top-0">ID</th>
                <th class="border-top-0">File Name</th>
                <th class="border-top-0">Location</th>
                <th class="border-top-0">Downloaded By</th>
                <th class="border-top-0">Email</th>
                <th class="border-top-0">Downloaded At</th>
                <!-- <th class="border-top-0">Actions</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach($reports as $report)
              <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->file_name }}</td>
                <td>{{ $report->suburb_name }}</td>
                <td>{{ $report->user->first_name }} {{ $report->user->last_name }}</td>
                <td>{{ $report->user->email }}</td>
                <td data-sort="{{ $report->created_at->timestamp }}">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                <!-- <td>
                  {{-- <a href="{{ route('report.download', $report->id) }}" class="btn btn-sm btn-primary rounded-pill">
                    <i class="fas fa-download"></i>
                  </a> --}}
                </td> -->
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
    /* Pagination Container */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1rem;
    }

    /* Common Button Styles */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        /* padding: 0.5rem 1rem; */
        /* margin: 0 0.25rem; */
        /* border-radius: 9999px; Pill shape */
        /* border: 1px solid #dee2e6; */
        /* background-color: #f8f9fa; */
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
        background-color: #0d6efd; /* Your brand primary color */
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
      padding: 5px 10px !important/*  */;
      background: #fff;
      border: 1px solid #ddd;
  }
</style>
@endpush

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
$(document).ready(function() {
    $('#reportsTable').DataTable({
        "order": [[ 5, "desc" ]] // Default sort by the Date column (index 5) in descending order
    });
});
</script>
@endpush