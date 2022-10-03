@extends('layouts.system-worker')

@section('content')
  <router-view
    :classes="{{ $classes }}"
    :statuses="{{ $statuses }}"
    :entities="{{ $entities }}"
  ></router-view>
@endsection
