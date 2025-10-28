@extends('adminpanel::master')

@section('title', 'Dashboard')

@section('content')
<!-- Container -->
<div class="container-fluid">
  <div class="dashboard-banner mt-5">
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h6 class="card-title">Downloaded Reports</h6>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>File Name</th>
                  <th>Downloaded By</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reports as $report)
                <tr>
                  <td>{{ $report->id }}</td>
                  <td>{{ $report->file_name }}</td>
                  <td>{{ $report->user->first_name }} {{ $report->user->last_name }}</td>
                  <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                  <td>
                    <a href="{{ route('report.download', $report->id) }}" class="btn btn-sm btn-primary">Download</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            {{ $reports->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Container -->
@endsection