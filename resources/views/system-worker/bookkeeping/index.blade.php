@extends('layouts.system-worker')


@auth('system_workers_web')
@section('content')
  <router-view
    :driver-types="{{ $driver_types }}"
    :companies="{{ $companies }}"
    :payment-types="{{ $payment_types }}"
    :drivers="{{ $drivers }}"
    :parks="{{ $parks }}"
    :transaction-types="{{ $transaction_types }}"
  >
  </router-view>
@endsection
@endauth
