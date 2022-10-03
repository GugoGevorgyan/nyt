@extends('layouts.app')

@section('base-app')
  <profile :initial-data="{{ json_encode($data, JSON_THROW_ON_ERROR) }}"></profile>
@endsection
