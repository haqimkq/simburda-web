<div class="flex items-center justify-between">
  <h1 class="text-[2em] font-bold text-primary">@yield('headerName')</h1>
  <div class="flex items-center">
    <div class="flex flex-col items-end">
      <h3 class="text-lg font-semibold text-primary inline-flex !w-full">{{$user->nama}}</h3>
      <p class="text-primary font-light">{{ucfirst($user->role)}}</p>
    </div>
    <img class="h-16 w-auto mr-2 rounded-full" src="{{($user->foto) ? $user->foto : 'images/manager.png'}}" alt="">
  </div>
</div>
