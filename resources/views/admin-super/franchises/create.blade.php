@extends('layouts.admin-super')

@section('navigation')
  <router-view name="navigation"></router-view>
@endsection

@section('toolbar')
  <router-view
    name="toolbar"
    :logout="'{{route('admin.super.logout')}}'"
  ></router-view>
@endsection

@section('content')
  <router-view
    :modules="{{ $modules }}"
    :countries="{{ $countries }}"
    :entity-types="{{ $entityTypes }}"
    :entities="{{ $entities }}"
    :driver-types="{{ $driverTypes }}"
  ></router-view>
@endsection
