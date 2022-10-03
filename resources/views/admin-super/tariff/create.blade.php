@extends('layouts.admin-super')

@section('navigation')
  <router-view name="navigation"></router-view>
@endsection

@section('toolbar')
  <router-view
    name="toolbar"
    :logout="'{{route('admin.super.logout')}}'"
  ></router-view>
@endsection


@section('content')
  <router-view
    :rounds="{{ $rounds }}"
    :car-classes="{{ $carClasses }}"
    :payment-types="{{ $paymentTypes }}"
    :tariff-types="{{ $tariffTypes }}"
  ></router-view>
@endsection
