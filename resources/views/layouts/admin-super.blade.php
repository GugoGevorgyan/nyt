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
  <link href="{{ 'production' === app()->environment() ? asset(mix('admin-super/css/app.css')) : asset('admin-super/css/app.css') }}" rel="stylesheet">

  <script src="{{ $geocode }}lang=ru_RU&load=package.standard,package.geoObjects,package.search" type="text/javascript" defer></script>
  <script src="{{ 'production' === app()->environment() ? asset(mix('admin-super/js/app.js')) : asset('admin-super/js/app.js') }}" defer></script>
  <script defer src="{{ asset('commons/map_theme.js') }}"></script>

  @yield('scripts')
</head>
<body>

<div id="admin-super" data-auth="{{$auth}}">
  <div class="root" style="display: none;"></div>

  <v-app light>
    @yield('navigation')

    @yield('toolbar')

    <v-main>
      @yield('content')
    </v-main>

    <snackbar></snackbar>
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
