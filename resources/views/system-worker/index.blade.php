@extends('layouts.system-worker')

@auth('system_workers_web')
@section('content')
  <router-view></router-view>
@endsection
@endauth
