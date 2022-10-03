@extends('layouts.system-worker')

@section('content')
  <router-view :company="{{ $company }}"></router-view>
@endsection
