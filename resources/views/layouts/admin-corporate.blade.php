<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="shortcut icon" href="{{ asset('storage/img/favicon.svg') }}">
  <link rel="preconnect" href="//api-maps.yandex.ru">
  <link rel="dns-prefetch" href="//api-maps.yandex.ru">
  <link href="{{ 'production' === app()->environment() ? asset(mix('admin-corporate/css/app.css')) : asset('admin-corporate/css/app.css') }}" rel="stylesheet">

  <script src="{{ $geocode }}lang=ru_RU" type="text/javascript" defer></script>
  <script src="{{ 'production' === app()->environment() ? asset(mix('admin-corporate/js/app.js')) : asset('admin-corporate/js/app.js') }}" defer></script>
  <script src="{{ asset('commons/map_theme.js') }}" defer></script>

  @yield('header-assets')
</head>
<body>
<div id="personal-admin-app">
  <v-app style="background-color: rgb(255,253,244);">
    <v-main>
      @yield('content')
    </v-main>

    <notify-snack app></notify-snack>
  </v-app>
</div>

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
