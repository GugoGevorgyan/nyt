@extends('layouts.system-worker')

@section('content')
  <router-view :types="{{ $types }}" :options="{{ $options }}"></router-view>
@endsection
