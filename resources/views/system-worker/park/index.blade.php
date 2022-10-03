@extends('layouts.system-worker')

@section('content')
  <router-view :regions="{{ $regions }}" :cities="{{ $cities }}" :entities="{{ $entities }}" :managers="{{ $managers }}"></router-view>
@endsection
