@extends('layouts.admin-super')


@guest('admin_super_web')
@section('content')
  <router-view
    :action="'{{route('admin.super.login')}}'"
    :errs="{{json_encode($errors->getMessages(), JSON_THROW_ON_ERROR)}}"
    :inputs="{{json_encode(session()->getOldInput(), JSON_THROW_ON_ERROR)}}"
  ></router-view>
@endsection
@endguest
