@extends('adminpanel::master')

@section('title', 'Edit State: ' . $state->name)

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 font-weight-bold text-primary">Edit State: {{ $state->name }} ({{ $state->state_code }})</h6>
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

          <form action="{{ route('states.update', $state->state_code) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label for="state_code" class="font-weight-bold">State Code:</label>
              <input type="text" class="form-control" id="state_code" value="{{ $state->state_code }}" disabled>
              <small class="form-text text-muted">State Code cannot be changed.</small>
            </div>

            <div class="form-group">
              <label for="name" class="font-weight-bold">State Name:</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $state->name) }}" required>
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn btn-success btn-lg shadow-sm mr-2">
                Update State
              </button>
              <a href="{{ route('states.index') }}" class="btn btn-secondary btn-lg shadow-sm">
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