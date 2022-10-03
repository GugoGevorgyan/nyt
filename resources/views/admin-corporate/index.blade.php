@extends('layouts.admin-corporate')

@auth('admin_corporate_web')
@section('content')
  <admin-corporate :initial-data="{{ json_encode($staticInfo, JSON_THROW_ON_ERROR, 512) }}"></admin-corporate>
@endsection
@endauth
