@extends('layouts.system-worker')

@section('content')
  <router-view
    :parks="{{ json_encode($parks) }}"
    :driver-types="{{ json_encode($types) }}"
    :schedule-types="{{ json_encode($graphics) }}"
  ></router-view>
@endsection
