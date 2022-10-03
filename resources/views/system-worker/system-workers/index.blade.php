@extends('layouts.system-worker')

@section('content')
  <router-view :roles="{{ $roles }}"></router-view>
@endsection
