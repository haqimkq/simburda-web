<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" href="images/favico.png" />
    
    {{-- Style --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')
    
  </head>

  <body class="h-screen flex flex-col justify-center items-center align-content-center">
    {{-- Navbar --}}
    <a href="/">
      <img class="w-[10em]" src="images/logo-burda.png" alt="">
    </a>

    {{-- Page Content --}}
    @yield('content')

    {{-- Script --}}
    @stack('prepend-script')
    @include('includes.script')
    @stack('addon-script')
  </body>
</html>
