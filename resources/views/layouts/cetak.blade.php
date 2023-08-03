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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
    <style>
      body{
        font-family: 'Poppins', sans-serif;
      }
      .flex {
        display: flex !important;
      }
      .p-5 {
        padding: 1.25rem/* 20px */ !important;
      }
      @media (min-width: 768px) {
        .md\:p-8 {
          padding: 2rem/* 32px */ !important;
        }
      }
      .flex-col {
        flex-direction: column !important;
      }
      .w-full {
        width: 100% !important;
      }
      .items-center {
        align-items: center !important;
      }
      .justify-center {
        justify-content: center !important;
      }
    </style>
  </head>

  <body class="p-5 md:p-8 flex flex-col w-full items-center justify-center">
    {{-- Sidebar --}}
    <div >
      @yield('content')
    </div>
    {{-- Script --}}
    @stack('prepend-script')
    {{-- @include('includes.script') --}}
    @stack('addon-script')
  </body>
</html>
