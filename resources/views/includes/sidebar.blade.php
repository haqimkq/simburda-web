<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<div @click.away="open = false"
	class="md:min-h-[95.5%] bg-primary dark-mode:text-gray-200 dark-mode:bg-gray-800 flex w-full flex-col rounded-lg text-gray-700 md:fixed md:w-[16em]"
	x-data="{ open: false }">
	<div class="flex flex-shrink-0 flex-row items-center justify-between px-8 py-4">
		<a href="/home"
			class="dark-mode:text-white focus:shadow-outline flex flex-wrap items-center justify-center rounded-lg p-2 text-lg font-semibold uppercase tracking-widest text-gray-900 focus:outline-none">
			<img class="w-20 self-center" src="images/logo-burda-white.png" alt="">
		</a>
		<button class="focus:shadow-outline rounded-lg focus:outline-none md:hidden" @click="open = !open">
			<svg fill="#fff" viewBox="0 0 20 20" class="h-6 w-6">
				<path x-show="!open" fill-rule="evenodd"
					d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
					clip-rule="evenodd"></path>
				<path x-show="open" fill-rule="evenodd"
					d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
					clip-rule="evenodd"></path>
			</svg>
		</button>
	</div>
	<nav :class="{ 'block': open, 'hidden': !open }" class="flex-grow px-4 md:flex md:flex-col md:h-full md:overflow-y-auto justify-between mb-4">
		<div class="flex-column">
			<a href="/home" class="sidebar-link text-sm {{ request()->is('home*') ? 'bg-primary-light' : '' }}">
				<span class="ml-3">Dashboard</span>
			</a>
			@canany(['admin','project-manager'])
				<a href="/pengguna" class="sidebar-link text-sm {{ request()->is('pengguna*') ? 'bg-primary-light' : '' }}">
					<span class="ml-3 flex-1 whitespace-nowrap">Pengguna</span>
				</a>
			@endcanany
			@canany(['admin','admin-gudang'])
			<a href="/barang" class="sidebar-link text-sm {{ request()->is('barang*') ? 'bg-primary-light' : '' }}">
				<span class="ml-3 flex-1 whitespace-nowrap">Barang</span>
			</a>
			@canany(['admin','admin-gudang'])
				<a href="/kendaraan" class="sidebar-link text-sm {{ request()->is('kendaraan*') ? 'bg-primary-light' : '' }}">
					<span class="ml-3 flex-1 whitespace-nowrap">Kendaraan</span>
				</a>
			@endcanany
			@endcanany
			<a href="/proyek" class="sidebar-link text-sm {{ request()->is('proyek*') ? 'bg-primary-light' : '' }}">
				<span class="ml-3 flex-1 whitespace-nowrap">Proyek</span>
			</a>
			@canany(['admin','purchasing'])
				<a href="/delivery-order" class="sidebar-link text-sm {{ request()->is('delivery-order*') ? 'bg-primary-light' : '' }}">
					<span class="ml-3 flex-1 whitespace-nowrap">Delivery Order</span>
				</a>
			@endcanany
			@canany(['admin','logistic'])
				<a href="/surat-jalan" class="sidebar-link text-sm {{ request()->is('surat-jalan*') ? 'bg-primary-light' : '' }}">
					<span class="ml-3 flex-1 whitespace-nowrap">Surat Jalan</span>
				</a>
			@endcanany
			@canany(['admin','admin-gudang'])
				<a href="/peminjaman" class="sidebar-link text-sm {{ request()->is('peminjaman*') ? 'bg-primary-light' : '' }}">
					<span class="ml-3 flex-1 whitespace-nowrap">Peminjaman</span>
				</a>
			@endcanany
			<a href="/akses-barang" class="sidebar-link text-sm {{ request()->is('akses-barang*') ? 'bg-primary-light' : '' }}">
				<span class="ml-3 flex-1 whitespace-nowrap">Akses Barang</span>
				<span
					class="ml-3 inline-flex h-3 w-3 items-center justify-center rounded-full bg-blue-200 p-3 text-sm font-medium text-blue-600 dark:bg-blue-900 dark:text-blue-200">3</span>
			</a>
		</div>
		<form action="{{ route('logout') }}" method="post" class="relative" class="md:h-auto">
			@csrf
			<button type="submit"
				class="mt-2 flex w-full items-center rounded-lg bg-red-600 p-2 text-base font-normal text-white hover:bg-red-300 dark:text-white">
				<span class="ml-3 whitespace-nowrap">Log Out</span>
			</button>
		</form>
	</nav>
</div>
