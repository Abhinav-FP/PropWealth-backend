@extends('adminpanel::master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
  <div class="dashboard-banner mt-5">
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center p-4">
          <h5 class="card-title fw-bold mb-0 text-muted">Downloaded Users</h5>
          <a href="{{ route('user.export') }}" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
            <i class="fas fa-file-excel me-2"></i> Export to Excel
          </a>
        </div>
          <div class="card-body">
            <table class="table" id="reportsTable">
              <thead>
                <tr>
                  <th>SN</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Registered At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td> {{ $user->first_name }} {{ $user->last_name }} </td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->mobile_number }}</td>
                  <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                  <td>
                    <form action="{{ url('admin/user/' . $user->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                    {{-- Add actions like view reports for this user, edit user, delete user --}}
                    {{-- <a href="{{ route('report.userReport', $user->id) }}" class="btn btn-sm btn-info">View Reports</a> --}}
                    {{-- More actions --}}
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
</div>
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
$(document).ready(function() {
    $('#reportsTable').DataTable({
      "pagingType": "simple_numbers",
      "searching": true,
      "info": true
    });
});
</script>
@endpush