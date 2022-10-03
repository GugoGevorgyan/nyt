@extends('layouts.system-worker')

@section('content')
  <router-view
    :car-obj="{{ $car }}"
    :classes="{{ $classes }}"
    :statuses="{{ $statuses }}"
    :entities="{{ $entities }}"
  ></router-view>
@endsection
