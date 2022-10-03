<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="shortcut icon" href="{{ asset('storage/img/favicon.svg') }}">
  <link rel="stylesheet" href="{{ app()->environment('production') ? asset(mix('admin-workers/css/app.css')) : asset('admin-workers/css/app.css') }}">
  <link rel="preconnect" href="//api-maps.yandex.ru">
  <link rel="dns-prefetch" href="//api-maps.yandex.ru">

  <script src="{{ $geocode }}lang=ru_RU&load=package.standard,package.geoObjects,package.search" type="text/javascript" defer></script>
  <script src="{{ app()->environment('production') ? asset(mix('admin-workers/js/app.js')) : asset('admin-workers/js/app.js') }}" defer></script>
  <script src="{{ asset('commons/map_theme.js') }}" defer></script>
</head>
<body>

@php
  $roles = $roles ?? '';
  $permissions = $permissions ?? '';
  $modules = $modules ?? ''
@endphp

@if(Request::is('*worker/login'))
  <div id="app-system-worker">
    <v-app light>

      <v-main>
        @yield('content')
      </v-main>

      <notify-snack app></notify-snack>
    </v-app>
  </div>
@else
  <div id="app-system-worker"
       data-auth="{{ $auth }}"
       data-menu="{{ $menu }}"
       data-roles="{{ $roles }}"
       data-permissions="{{ $permissions }}"
       data-modules="{{ $modules }}"
       data-mask="{{ $mask }}"
  >
    <v-app light>

      <Navigation app></Navigation>
      <Toolbar app></Toolbar>

      <v-main>
        @yield('content')
      </v-main>

      <chat app></chat>
      <session-stop app></session-stop>
      <notify-snack app></notify-snack>
    </v-app>
  </div>
@endif

<script type="text/javascript">
  let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

  async function postData(url = '/refresh-csrf') {
    const response = await fetch(url, {
      method: 'GET',
      mode: 'cors',
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      headers: {
        'Content-Type': 'application/json',
      },
      redirect: 'follow', // manual, *follow, error
      referrerPolicy: 'no-referrer', // no-referrer, *client
    });

    return await response.json();
  }

  postData().then((data) => {
    csrfToken = data._payload;
  });

  setInterval(postData, 300000);
</script>
</body>
</html>

