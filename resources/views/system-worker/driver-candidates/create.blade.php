@extends('layouts.system-worker')

@section('content')
  <router-view
    :tutors="{{ $tutors }}"
    :license-types="{{ $licenseTypes }}"
    :learn-statuses="{{ $learnStatuses }}"
  ></router-view>
@endsection
