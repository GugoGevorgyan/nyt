@extends('layouts.system-worker')

@section('content')
  <router-view :types="{{ $types }}"></router-view>
@endsection
