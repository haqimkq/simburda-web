<div class="flex items-center justify-between">
  <h1 class="text-[2em] font-bold text-primary">@yield('headerName')</h1>
  <div class="flex items-center">
    <div class="flex flex-col items-end">
      <h3 class="text-lg font-semibold text-primary inline-flex !w-full">@yield('nama')
      </h3>
      <p class="text-primary font-light">@yield('role')</p>
    </div>
    @sectionMissing('foto')
      <img class="h-16 w-auto ml-2 rounded-3xl" src="/images/manager.png">
    @endif
    @hasSection('foto')
      <img class="h-16 w-auto ml-2 rounded-3xl" src="@yield('foto')">
    @endif
  </div>
</div>
