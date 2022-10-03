@extends('layouts.system-worker')

@section('content')
  <router-view :user="{{ $user }}"></router-view>
@endsection
