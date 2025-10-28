@extends('adminpanel::master')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 font-weight-bold text-primary">Edit User: {{ $user->name }} (ID: {{ $user->id }})</h6>
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

          <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label for="name" class="font-weight-bold">Name:</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
              <label for="email" class="font-weight-bold">Email:</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <hr class="my-4">

            <p class="text-muted">Leave password fields blank if you don't want to change the password.</p>

            <div class="form-group">
              <label for="password" class="font-weight-bold">New Password:</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
              <label for="password_confirmation" class="font-weight-bold">Confirm New Password:</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn btn-success btn-lg shadow-sm mr-2">
                Update User
              </button>
              <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg shadow-sm">
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