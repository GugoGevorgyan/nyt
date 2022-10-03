@extends('layouts.system-worker')
<script src="" defer></script>

@auth(\Src\Core\Enums\ConstGuards::SYSTEM_WORKERS_WEB()->getValue())
@section('content')
  <router-view
    :sub-phone="{{ $sub_phone }}"
    :country-code="{{ $country_code }}"
    :pending-orders="{{ $pending_orders }}"
    :order-types="{{ $order_types }}"
    :order-statuses="{{ $order_statuses }}"
    :driver-statuses="{{ $driver_statuses }}"
    :car-classes="{{ $car_classes }}"
    :car-options="{{ $car_options }}"
    :payment-types="{{ $payment_types }}"
    :calls="{{ $calls}}"
    :railway-stations="{{ $railway_stations }}"
    :airports="{{ $airports }}"
    :metros="{{ $metros }}"
    :drivers="{{ $drivers }}"
  />
@endsection
@endauth
