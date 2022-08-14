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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    {{-- Style --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')
    
  </head>

  <body>
    {{-- Sidebar --}}
    <div class="md:flex flex-col md:flex-row md:min-h-screen p-4">
      @include('includes.sidebar')
      {{-- Page Content --}}
      @yield('content')
    </div>
    {{-- Footer --}}
    @include('includes.footer')
    {{-- Script --}}
    @stack('prepend-script')
    @include('includes.script')
    @stack('addon-script')
  </body>
</html>
