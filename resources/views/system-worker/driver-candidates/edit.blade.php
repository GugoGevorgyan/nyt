@extends('layouts.system-worker')

@section('content')
  <router-view
    :candidate="{{ $candidate }}"
    :tutors="{{ $tutors }}"
    :license-types="{{ $licenseTypes }}"
    :learn-statuses="{{ $learnStatuses }}"
  >
  </router-view>
@endsection
