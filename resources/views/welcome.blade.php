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

    <title>Burda Contraco</title>

    <link rel="icon" type="image/png" href="/images/favico.png" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- Style --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')
    
  </head>
  <body class="h-screen lg:block flex flex-col-reverse  md:flex-row items-center justify-center">
    <div class="lg:absolute z-10">
      <div class="flex flex-col lg:h-screen justify-center lg:px-[5em] px-10 xl:px-[10em] lg:mt-0 mt-10">
        <h1 class="uppercase italic xl:text-[3em] lg:text-[2.5em] md:text-[2em] text-[1.5em] font-black text-primary">
          Sistem Informasi <br> Gudang & Logistik
        </h1>
        <p class="md:text-[1.5em] text-[1em] text-primary"> PT Burda Contraco </p>
        <button class="button-custom !font-light !rounded-full md:!w-[60%] !w-[auto]  mt-5">
          <a href="{{ route('login') }}" class="text-white lg:text-[1.5em] text-[1em] w-full">
            <span>Masuk Sekarang</span>
          </a>
        </button>
      </div>
    </div>
    <div class="flex lg:flex-row flex-col-reverse md:flex-col-reverse">
      <div class="grid grid-flow-row grid-cols-3">
        <img class="lg:block col-span-3 w-[10vw] lg:w-[50vw] h-auto self-start sm:hidden hidden" src="images/hero-top-left.png" alt="">
        <img class="lg:block col-span-2 w-[10vw] lg:w-[50vw] h-auto self-start lg:self-end sm:hidden hidden" src="images/hero-bottom-left.png" alt="">
      </div>
      <div class="h-[40vh] w-[15em] md:w-[50vw] md:h-screen xl:w-full xl:h-screen bg-cover bg-right md:bg-left" style="background-image: url('images/hero-right.png')">
        {{-- <img class="w-[100%] h-auto" src="images/hero-right.png" alt=""> --}}
      </div>
    </div>
    {{-- Script --}}
    @stack('prepend-script')
    @include('includes.script')
    @stack('addon-script')
  </body>
</html>
