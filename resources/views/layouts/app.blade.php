<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <title>{{ config('app.name', 'Laravel') }}</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#ffc107">

  {!! SEOMeta::generate() !!}
  {!! OpenGraph::generate() !!}
  {!! Twitter::generate() !!}
  {!! JsonLd::generate() !!}
  {!! SEO::generate() !!}
  {!! SEO::generate(true) !!}
  {!! app('seotools')->generate() !!}

  <link rel="shortcut icon" href="{{ asset('storage/img/favicon.svg') }}">
  <link rel="preconnect" href="//api-maps.yandex.ru">
  <link rel="dns-prefetch" href="//api-maps.yandex.ru">
  <link href="{{ app()->environment('production') ? asset(mix('app/css/app.css')) : asset('app/css/app.css') }}" rel="stylesheet">

  <script defer src="{{ $geocode }}lang=ru_RU&load=package.standard,package.geoObjects,package.search" type="text/javascript"></script>
  <script defer src="{{ app()->environment('production') ? asset(mix('app/js/app.js')) : asset('app/js/app.js') }}"></script>
  <script defer src="{{ asset('commons/map_theme.js') }}"></script>
</head>
<body>

<div id="app" style='height: 100%;'>
  <v-app>
    <notify-snack></notify-snack>

    <v-main>
      @yield('base-app')
    </v-main>

  </v-app>
</div>

<script type="text/javascript">
  let raw = navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./);
  parseInt(raw[2], 10);

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
