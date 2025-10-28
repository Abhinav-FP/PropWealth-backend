@extends('adminpanel::master')

@section('title', 'Add New Suburb')

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 font-weight-bold text-primary">Add New Suburb Manually</h6>
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

          @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops!</strong> There were some problems with your input:
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          <form action="{{ route('suburbs.store') }}" method="POST">
            @csrf

            <div class="form-group">
              <label for="suburb_id" class="font-weight-bold">Suburb ID:</label>
              <input type="number" class="form-control" id="suburb_id" name="suburb_id" value="{{ old('suburb_id') }}" required>
            </div>

            <div class="form-group">
              <label for="name" class="font-weight-bold">Suburb Name:</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
              <label for="state" class="font-weight-bold">State Code:</label>
              <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required placeholder="e.g., 1 for NSW, 8 for ACT">
              <small class="form-text text-muted">Use the numeric state code (e.g., 1 for NSW, 8 for ACT).</small>
            </div>

            <div class="form-group">
              <label class="font-weight-bold">Bounding Box (min_lng, min_lat, max_lng, max_lat):</label>
              <div class="form-row">
                <div class="col">
                  <input type="number" step="any" class="form-control" name="min_lng" placeholder="Min Longitude" value="{{ old('min_lng') }}" required>
                </div>
                <div class="col">
                  <input type="number" step="any" class="form-control" name="min_lat" placeholder="Min Latitude" value="{{ old('min_lat') }}" required>
                </div>
                <div class="col">
                  <input type="number" step="any" class="form-control" name="max_lng" placeholder="Max Longitude" value="{{ old('max_lng') }}" required>
                </div>
                <div class="col">
                  <input type="number" step="any" class="form-control" name="max_lat" placeholder="Max Latitude" value="{{ old('max_lat') }}" required>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn btn-primary btn-lg shadow-sm mr-2">
                Add Suburb
              </button>
              <a href="{{ route('suburbs.adminIndex') }}" class="btn btn-secondary btn-lg shadow-sm">
                Cancel
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection