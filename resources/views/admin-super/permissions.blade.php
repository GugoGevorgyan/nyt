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
    :roles="{{$roles}}"
    :laravel-guards="{{$guards}}"
  ></router-view>
@endsection
