@extends('layouts.app')

@section('base-app')
  <router-view
    :initial-client="{{ json_encode($client, JSON_THROW_ON_ERROR) }}"
    :client-companies="{{ json_encode($companies, JSON_THROW_ON_ERROR) }}"
    :location="{{ $location }}"
    :logout-route= {{ json_encode(route('logoutClient'), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK) }}
  ></router-view>
@endsection
