<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="description"
      content=""
    />
    <meta
      name="keywords"
      content=""
    />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" href="/images/favico.png" />

    {{-- Style --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')
    
  </head>
  
  <body class="container-lg w-full mx-auto grid place-items-center my-10">
    {{-- Sidebar --}}
    <div >
      @yield('content')
    </div>
    {{-- Script --}}
    @stack('prepend-script')
    @include('includes.script')
    @stack('addon-script')
  </body>
</html>
