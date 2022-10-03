@extends('layouts.system-worker')

@section('content')
  <router-view :entity-types="{{ $entityTypes }}" :countries="{{ $countries }}"></router-view>
@endsection
