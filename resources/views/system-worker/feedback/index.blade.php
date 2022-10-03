@extends('layouts.system-worker')


@section('content')
  <router-view :types="{{ $types }}" :order-statuses="{{ $orderStatuses }}" :writers="{{ $writers }}"></router-view>
@endsection
