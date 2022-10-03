@extends('layouts.system-worker')

@section('content')
  <router-view :statuses="{{ $statuses }}"></router-view>
@endsection
