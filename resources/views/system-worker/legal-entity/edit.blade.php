@extends('layouts.system-worker')

@section('content')
  <router-view :entity="{{ $entity }}" :entity-types="{{ $entityTypes }}" :countries="{{ $countries }}"></router-view>
@endsection
