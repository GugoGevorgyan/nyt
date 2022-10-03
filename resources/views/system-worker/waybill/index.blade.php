@extends('layouts.system-worker')

@section('content')
  <router-view
    :parks="{{ $parks }}"
    :drivers="{{ $drivers }}"
  >
  </router-view>
@endsection
