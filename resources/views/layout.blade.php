<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Flashcards</title>
  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
  <link rel="manifest" href="/favicons/site.webmanifest">
  <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#718096">
  <meta name="msapplication-TileColor" content="#2b5797">
  <meta name="theme-color" content="#e2e8f0">
  @livewireAssets
</head>

<body class="bg-gray-400 w-full h-full">
  @yield('content')
</body>

<script src="{{ mix('/js/app.js') }}"></script>
@stack('scripts')
</html>
