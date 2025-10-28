@extends('adminpanel::master')

@section('title', 'State Data Management')

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-xl-12">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">State Data List</h6>
          <div>
            <a href="{{ route('states.create') }}" class="btn btn-success btn-sm shadow-sm mr-2">
              <i class="ion ion-ios-add-circle-outline"></i> Add New
            </a>
            <a href="{{ route('states.upload') }}" class="btn btn-primary btn-sm shadow-sm mr-2">
              <i class="ion ion-ios-cloud-upload"></i> Upload New Data
            </a>
            <button type="button" class="btn btn-danger btn-sm shadow-sm" onclick="confirmDeleteAllStates()">
              <i class="ion ion-ios-trash"></i> Delete All
            </button>
          </div>
        </div>
        <div class="card-body">
          @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="statesDataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Created At</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($states as $state)
                <tr>
                  <td>{{ $state->state_code }}</td>
                  <td>{{ $state->name }}</td>
                  <td>{{ $state->created_at->format('Y-m-d H:i:s') }}</td>
                  <td class="text-center">
                    <a href="{{ route('states.edit', $state->state_code) }}" class="btn btn-info btn-sm">
                      <i class="ion ion-ios-create"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm ml-2" onclick="confirmDeleteState('{{ $state->state_code }}', '{{ $state->name }}')">
                      <i class="ion ion-ios-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center">No state data found. Please upload data.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-center mt-4">
            {{ $states->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Hidden form for deleting all states --}}
<form id="deleteAllStatesForm" action="{{ route('states.destroyAll') }}" method="POST" style="display: none;">
  @csrf
</form>

{{-- Hidden form for deleting a single state --}}
<form id="deleteStateForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('scripts')
<script>
  function confirmDeleteAllStates() {
    if (confirm('Are you absolutely sure you want to delete ALL state data? This action cannot be undone.')) {
      document.getElementById('deleteAllStatesForm').submit();
    }
  }

  function confirmDeleteState(stateCode, stateName) {
    if (confirm(`Are you sure you want to delete the state "${stateName}" (${stateCode})? This action cannot be undone.`)) {
      const form = document.getElementById('deleteStateForm');
      form.action = "{{ url('admin/states') }}/" + stateCode;
      form.submit();
    }
  }
</script>
@endpush
@endsection