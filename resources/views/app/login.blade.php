@extends('layouts.app')

@section('base-app')
  @guest('clients_web')
    <Login
      :login-client-by-phone="{{ json_encode(route('appLoginByPhone'), JSON_THROW_ON_ERROR) }}"
      :login-client-by-name="{{ json_encode(route('appLoginByEmail'), JSON_THROW_ON_ERROR) }}"
      :send-sms-route="{{ json_encode(route('sendSmsCodeLogin'), JSON_THROW_ON_ERROR) }}"
      :corporate-admin-login="{{ json_encode(route('loginCorporateAdmin'), JSON_THROW_ON_ERROR) }}"
      :mask="{{ json_encode(session('app_system.mask'), JSON_THROW_ON_ERROR) }}"
    >
    </Login>
  @elseguest('admin_corporate_web')
    <Login
      :login-client-by-phone="{{ json_encode(route('appLoginByPhone'), JSON_THROW_ON_ERROR) }}"
      :login-client-by-name="{{ json_encode(route('appLoginByEmail'), JSON_THROW_ON_ERROR) }}"
      :send-sms-route="{{ json_encode(route('sendSmsCodeLogin'), JSON_THROW_ON_ERROR) }}"
      :corporate-admin-login="{{ json_encode(route('loginCorporateAdmin'), JSON_THROW_ON_ERROR) }}"
    >
    </Login>
  @endguest

@endsection
