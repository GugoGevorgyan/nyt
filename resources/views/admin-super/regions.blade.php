@extends('layouts.admin-super')

@section('scripts')
  <script src="{{ $geocode }}lang=ru_RU" type="text/javascript" defer></script>
  <script src="https://yastatic.net/s3/mapsapi-jslibs/area/0.0.1/util.calculateArea.min.js"
          type="text/javascript"
          defer></script>
@endsection

@section('navigation')
  <router-view name="navigation"></router-view>
@endsection

@section('toolbar')
  <router-view
    name="toolbar"
    :logout="'{{route('admin.super.logout')}}'"
  ></router-view>
@endsection

@section('content')
  <router-view
    :countries="{{ $countries }}"
  ></router-view>
@endsection
