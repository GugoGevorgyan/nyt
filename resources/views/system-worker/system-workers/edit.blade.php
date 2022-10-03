@extends('layouts.system-worker')


@section('content')
  <router-view :worker-obj="{{ $worker }}" :module-roles="{{ $module_roles }}" :sub-phones="{{ $sub_phones }}"></router-view>
@endsection
