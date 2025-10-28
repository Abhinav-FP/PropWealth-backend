@extends('adminpanel::master')

@section('title', 'Dashboard')

@section('content')
<!-- Container -->
<div class="container-fluid">
  <div class="dashboard-banner">
    <div class="row">
      <div class="col-xl-12">
        <div class="dashboard-photo">
          @if(Auth::user()->role_id === 2)
          <img src="{{ asset('admin/assets/img/swabdesign_official-Oir2q4rtGY0-unsplash.jpg')}}" />
          @else
          <img src="{{ asset('admin/assets/default/image-bg.jpg')}}" />
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Container -->
@endsection