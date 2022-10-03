@extends('layouts.system-worker')

@section('content')
  <router-view
    :workers="{{ json_encode($workers) }}"
  ></router-view>
@endsection
