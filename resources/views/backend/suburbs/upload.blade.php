@extends('adminpanel::master')

@section('title', 'Upload Suburb Data')

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 font-weight-bold text-primary">Upload Suburb Data (Excel) 📊</h6>
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
            <strong>Whoops!</strong> There were some problems with your upload:
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

          <form id="uploadForm" action="{{ route('suburbs.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="excel_file" class="font-weight-bold">Choose Excel/CSV File:</label>
              <input type="file" class="form-control-file rounded" id="excel_file" name="excel_file" required>
              <small class="form-text text-muted">Supported formats: .xlsx, .xls, .csv. Max 2MB.</small>
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" id="uploadButton" class="btn btn-primary btn-lg shadow-sm">
                <span id="buttonText">Upload & Import</span>
                <span id="loadingSpinner" class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" style="display: none;"></span>
              </button>
            </div>
          </form>

          <hr class="mt-5 mb-4">
          <h6 class="m-0 font-weight-bold text-secondary text-center">Expected Excel Format:</h6>
          <p class="text-center text-muted">
            Your Excel file should have the following column headers in the **first row**:
            <br> <code>id</code>, <code>name</code>, <code>state</code>, <code>min_lng</code>, <code>min_lat</code>, <code>max_lng</code>, <code>max_lat</code>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('uploadForm').addEventListener('submit', function() {
    const uploadButton = document.getElementById('uploadButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Disable the button to prevent multiple submissions
    uploadButton.disabled = true;
    loadingSpinner.style.display = 'inline-block';
  });

  window.addEventListener('load', function() {
    const uploadButton = document.getElementById('uploadButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Check if the button was left disabled from a previous submission attempt
    if (uploadButton.disabled && loadingSpinner.style.display === 'inline-block') {
      uploadButton.disabled = false;
      // Ensure the text is visible
      buttonText.style.display = 'inline';
      // Hide the spinner
      loadingSpinner.style.display = 'none';
    }
  });
</script>
@endpush
@endsection