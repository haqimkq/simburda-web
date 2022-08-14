<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<div @click.away="open = false" class="flex flex-col w-full md:w-64 text-gray-700 bg-primary dark-mode:text-gray-200 dark-mode:bg-gray-800 flex-shrink-0 rounded-lg" x-data="{ open: false }">
   <div class="flex-shrink-0 px-8 py-4 flex flex-row items-center justify-between">
   <a href="/home" class="flex justify-center items-center p-2 text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline flex-wrap">
      <img class="w-20 self-center" src={{asset('images/logo-burda-white.png')}} alt="">
   </a>
   <button class="rounded-lg md:hidden rounded-lg focus:outline-none focus:shadow-outline" @click="open = !open">
      <svg fill="#fff" viewBox="0 0 20 20" class="w-6 h-6">
         <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
         <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
      </svg>
   </button>
   </div>
   <nav :class="{'block': open, 'hidden': !open}" class="flex-grow md:block px-4 pb-4 md:pb-0 md:overflow-y-auto">
      <div class="flex-column">
         <a href="#" class="sidebar-link {{ request()->is('home*') ? 'bg-primary-light' : '' }}">
         <svg aria-hidden="true" class="w-6 h-6 text-white transition duration-75 group-hover:text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
         <span class="ml-3">Dashboard</span>
      </a>
      <a href="#" class="sidebar-link">
         <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-white transition duration-75 group-hover:text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
         <span class="flex-1 ml-3 whitespace-nowrap">Barang</span>
      </a>
      <a href="#" class="sidebar-link">
         <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-white transition duration-75 group-hover:text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
         <span class="flex-1 ml-3 whitespace-nowrap">Pengguna</span>
      </a>
      
      @can('admin')
         <a href="#" class="sidebar-link">
            <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-white transition duration-75 group-hover:text-primary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
               <path d="M17.9321 10.2494L17.7216 10.1774L15.938 6.98578C15.603 6.38598 15.1035 5.88437 14.4929 5.5346C13.8823 5.18482 13.1836 5.00004 12.4714 5H7.98029C7.15556 4.99997 6.35174 5.24766 5.68273 5.70798C5.01373 6.1683 4.51347 6.81789 4.25286 7.56471L3.40271 9.99893C2.69047 10.2855 2.08261 10.7661 1.65522 11.3808C1.22783 11.9955 0.999916 12.7168 1 13.4545V15.1239C1 16.1887 1.66471 17.1036 2.61857 17.5161C2.7421 18.1916 3.10437 18.8063 3.64505 19.2578C4.18573 19.7094 4.87217 19.9705 5.59004 19.9976C6.30791 20.0248 7.01387 19.8164 7.59033 19.4072C8.16678 18.998 8.57894 18.4126 8.75814 17.7486H15.2419C15.4211 18.4126 15.8332 18.998 16.4097 19.4072C16.9861 19.8164 17.6921 20.0248 18.41 19.9976C19.1278 19.9705 19.8143 19.7094 20.355 19.2578C20.8956 18.8063 21.2579 18.1916 21.3814 17.5161C21.8638 17.3083 22.2731 16.972 22.5602 16.5477C22.8473 16.1234 23 15.629 23 15.1239V14.6154C22.9999 13.8414 22.7488 13.0864 22.2812 12.454C21.8136 11.8216 21.1523 11.3428 20.3883 11.0833L18.0154 10.2779V10.2494H17.9321ZM5.74257 8.03866C5.89901 7.59037 6.19935 7.20048 6.60101 6.92427C7.00266 6.64806 7.48523 6.49956 7.98029 6.49983H9.64286V10.2494H4.971L5.74257 8.03866ZM15.9804 10.2494H11.2143V6.49983H12.4714C12.8988 6.49969 13.3181 6.61042 13.6846 6.82018C14.051 7.02994 14.3509 7.33084 14.552 7.6907L15.982 10.2494H15.9804ZM4.14286 16.9986C4.14286 16.6009 4.30842 16.2194 4.60312 15.9381C4.89782 15.6568 5.29752 15.4988 5.71429 15.4988C6.13105 15.4988 6.53075 15.6568 6.82545 15.9381C7.12015 16.2194 7.28571 16.6009 7.28571 16.9986C7.28571 17.3964 7.12015 17.7779 6.82545 18.0592C6.53075 18.3405 6.13105 18.4985 5.71429 18.4985C5.29752 18.4985 4.89782 18.3405 4.60312 18.0592C4.30842 17.7779 4.14286 17.3964 4.14286 16.9986ZM18.2857 15.4988C18.7025 15.4988 19.1022 15.6568 19.3969 15.9381C19.6916 16.2194 19.8571 16.6009 19.8571 16.9986C19.8571 17.3964 19.6916 17.7779 19.3969 18.0592C19.1022 18.3405 18.7025 18.4985 18.2857 18.4985C17.8689 18.4985 17.4692 18.3405 17.1745 18.0592C16.8798 17.7779 16.7143 17.3964 16.7143 16.9986C16.7143 16.6009 16.8798 16.2194 17.1745 15.9381C17.4692 15.6568 17.8689 15.4988 18.2857 15.4988V15.4988Z" fill="currentColor"/>
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">Kendaraan</span>
         </a>
      @endcan
      <a href="#" class="sidebar-link">
         <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-white transition duration-75 group-hover:text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
         <span class="flex-1 ml-3 whitespace-nowrap">Akses Barang</span>
         <span class="inline-flex justify-center items-center p-3 ml-3 w-3 h-3 text-sm font-medium text-blue-600 bg-blue-200 rounded-full dark:bg-blue-900 dark:text-blue-200">3</span>
      </a>
      </div>
      
      <form action="{{route('logout')}}" method="post" class="relative">
         @csrf
         <button type="submit" class="flex w-full items-center p-2 text-base font-normal text-white rounded-lg dark:text-white hover:bg-red-300 bg-red-600 mt-2">
            <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-white transition duration-75 group-hover:text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
            <span class="ml-3 whitespace-nowrap">Log Out</span>
         </button>
      </form>
   </nav>
</div>
