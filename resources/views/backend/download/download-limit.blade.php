@extends('adminpanel::master')

@section('title', 'Manage Download Limit')

@section('content')
<div class="container-fluid mt-4 pt-4">
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card shadow mb-4 mt-4 ">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 font-weight-bold text-primary">Manage Download Limit 🚀</h6>
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

          @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Oops!</strong> Please fix the following errors:
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

          <form action="{{ route('downloadLimit.update') }}" method="POST">
            @csrf

            <div class="form-group">
              <label for="daily_limit" class="font-weight-bold">Daily Download Limit:</label>
              <input
                type="number"
                id="daily_limit"
                name="daily_limit"
                value="{{ old('daily_limit', $limit->daily_limit) }}"
                class="form-control rounded"
                placeholder="e.g., 5"
                min="0">
            </div>

            <div class="form-group">
              <label for="lifetime_limit" class="font-weight-bold">Lifetime Download Limit:</label>
              <input
                type="number"
                id="lifetime_limit"
                name="lifetime_limit"
                value="{{ old('lifetime_limit', $limit->lifetime_limit) }}"
                class="form-control rounded"
                placeholder="e.g., 20"
                min="0">
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button
                type="submit"
                class="btn btn-primary btn-lg shadow-sm">
                Update Limit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection