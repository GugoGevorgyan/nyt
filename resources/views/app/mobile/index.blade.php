@extends('layouts.app-mobile')

@section('mobile-base-app')
  <router-view
    :initial-client="{{ json_encode($client, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK) }}"
    :location="{{ $location }}"
    :logout-route="{{ json_encode(route('logoutClient'), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK) }}"
    :mask="{{ json_encode(session('app_system.mask'), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK) }}"
  ></router-view>
@endsection
