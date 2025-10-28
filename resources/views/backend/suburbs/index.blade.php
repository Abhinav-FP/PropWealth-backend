@extends('adminpanel::master')

@section('title', 'Suburb Data Management')

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-xl-12">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Suburb Data List</h6>
          <div>
            <a href="{{ route('suburbs.create') }}" class="btn btn-success btn-sm shadow-sm mr-2">
              <i class="ion ion-ios-add-circle-outline"></i> Add New
            </a>
            <a href="{{ route('suburbs.upload') }}" class="btn btn-primary btn-sm shadow-sm mr-2">
              <i class="ion ion-ios-cloud-upload"></i> Upload Excel
            </a>
            <button type="button" class="btn btn-danger btn-sm shadow-sm" onclick="confirmDeleteAllSuburbs()">
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
            <table class="table table-bordered table-hover" id="suburbsDataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Suburb ID</th>
                  <th>Name</th>
                  <th>State</th>
                  <th>Bounding Box (bbox)</th>
                  <th>Created At</th>
                  <th class="text-center">Actions</th> {{-- New column for actions --}}
                </tr>
              </thead>
              <tbody>
                {{-- Loops through the paginated suburb data --}}
                @forelse($suburbs as $suburb)
                <tr>
                  <td>{{ $suburb->id }}</td>
                  <td>{{ $suburb->suburb_id }}</td>
                  <td>{{ $suburb->name }}</td>
                  <td>{{ $suburb->state }}</td>
                  {{-- Displays bbox as a readable array string --}}
                  <td>
                    @if(is_array($suburb->bbox))
                    [{{ implode(', ', $suburb->bbox) }}]
                    @else
                    {{ $suburb->bbox }}
                    @endif
                  </td>
                  <td>{{ $suburb->created_at->format('Y-m-d H:i:s') }}</td>
                  <td class="text-center">
                    <a href="{{ route('suburbs.edit', $suburb->id) }}" class="btn btn-info btn-sm">
                      <i class="ion ion-ios-create"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm ml-2" onclick="confirmDeleteSuburb('{{ $suburb->id }}', '{{ $suburb->name }}')">
                      <i class="ion ion-ios-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                @empty
                {{-- Message displayed if no suburb data is found --}}
                <tr>
                  <td colspan="7" class="text-center">No suburb data found. Please upload data.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-center mt-4"> {{-- Centers the pagination links --}}
            {{-- Renders Bootstrap 4 styled pagination links --}}
            {{ $suburbs->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Hidden form for deleting all suburbs --}}
<form id="deleteAllSuburbsForm" action="{{ route('suburbs.destroyAll') }}" method="POST" style="display: none;">
  @csrf
</form>

{{-- Hidden form for deleting a single suburb --}}
<form id="deleteSuburbForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('scripts') {{-- Pushes this script to a 'scripts' stack, which should be yielded in your master layout --}}
<script>
  function confirmDeleteAllSuburbs() {
    if (confirm('Are you absolutely sure you want to delete ALL suburb data? This action cannot be undone.')) {
      document.getElementById('deleteAllSuburbsForm').submit();
    }
  }

  function confirmDeleteSuburb(suburbId, suburbName) {
    if (confirm(`Are you sure you want to delete the suburb "${suburbName}" (ID: ${suburbId})? This action cannot be undone.`)) {
      const form = document.getElementById('deleteSuburbForm');
      form.action = "{{ url('admin/suburbs') }}/" + suburbId; // Set the action URL dynamically
      form.submit();
    }
  }
</script>
@endpush
@endsection