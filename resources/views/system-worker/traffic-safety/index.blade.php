@extends('layouts.system-worker')

@section('content')
  <router-view :statuses="{{ $statuses }}" :parks="{{ $parks }}"></router-view>
@endsection
