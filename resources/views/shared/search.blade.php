<form id="form" class="grid md:grid-cols-2 xl:grid-cols-4 gap-2 items-end w-full mb-2" action="@yield('action')">
    @yield('before-search')
    <div class="inline-flex">
        <label for="searchbox" class="sr-only">Search</label>
        <div class="relative w-full">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
            </div>
            {{-- @if (request('search'))
                <div class="flex absolute inset-y-0 right-0 items-center pr-3 cursor-pointer" onclick="">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="24px" height="24px"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/></svg>
                </div>
            @endif --}}
            <input name="search" type="search" id="searchbox" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green focus:border-greenring-green block w-full pl-10 p-2.5" placeholder="@yield('placeholderSearch')" value="{{request('search')}}" autofocus>
        </div>
        <button type="submit" class="p-2.5 ml-2 text-sm font-medium text-white bg-green rounded-lg border border-green focus:ring-4 focus:outline-none focus:ring-green-light">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <span class="sr-only">Search</span>
        </button>
    </div>
    @yield('middle-search')
    @yield('last-search')
</form>
