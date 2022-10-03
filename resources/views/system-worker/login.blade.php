@extends('layouts.system-worker')

@guest('system_workers_web')
@section('content')
  <router-view></router-view>
@endsection
@endguest
