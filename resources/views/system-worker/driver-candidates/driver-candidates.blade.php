@extends('layouts.system-worker')

@section('content')
  <router-view :types="{{ $types }}" :graphics="{{ $graphics }}"></router-view>
@endsection
