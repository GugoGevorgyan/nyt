@extends('layouts.system-worker')

@section('content')
  <router-view
    :statuses="{{ $statuses }}"
    :parks="{{ $parks }}"
    :classes="{{ $classes }}"
  ></router-view>
@endsection
