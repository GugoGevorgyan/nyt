@extends('layouts.system-worker')

@section('content')
  <router-view
    :parks="{{ $parks }}"
    :graphics="{{ $graphics }}"
    :types="{{ $types }}"
  ></router-view>
@endsection
