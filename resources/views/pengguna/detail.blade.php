@extends('layouts.detail')

@if (!$barangPinjaman->isEmpty())
	@push('prepend-script')
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
		integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
		crossorigin="" />
		<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
		integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
		crossorigin=""></script>
		<script>
			@foreach ($barangPinjaman as $bm)
				var lat =
					// -6.2501422;
					{{ $bm->latitude }};
				var lon =
					// 106.8564921;
					{{ $bm->longitude }};
				var map = L.map('{{ $bm->id }}', {
					zoomControl: false
				}).setView([lat, lon], 19);
				var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					maxZoom: 19,
					attribution: '© OpenStreetMap'
				}).addTo(map);

				var marker = L.marker([{{ $bm->latitude }}, {{ $bm->longitude }}]).addTo(map);
				marker.bindPopup(
					"<a target='_blank' href='https://maps.google.com/?q={{ $bm->latitude }},{{ $bm->longitude }}&z=20'><b>Proyek {{ $bm->nama_proyek }}</b><br>{{ $bm->alamat }}</a>"
				);
			@endforeach
			@foreach ($historyPeminjamanBarang as $hpb)
			var idExist = document.getElementById('history-{{$hpb->id}}');
			if(idExist){
				var lat_h =
					// -6.2501422;
					{{ $hpb->latitude }};
				var lon_h =
					// 106.8564921;
					{{ $hpb->longitude }};
					var map_h = L.map('history-{{ $hpb->id }}', {
					zoomControl: false
					}).setView([lat_h, lon_h], 19);
					var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
						maxZoom: 19,
						attribution: '© OpenStreetMap'
					}).addTo(map_h);
					var marker_h = L.marker([{{ $hpb->latitude }}, {{ $hpb->longitude }}]).addTo(map_h);
					marker_h.bindPopup(
						"<a target='_blank' href='https://maps.google.com/?q={{ $hpb->latitude }},{{ $hpb->longitude }}&z=20'><b>Proyek {{ $hpb->nama_proyek }}</b><br>{{ $hpb->alamat }}</a>"
					);
				}
			@endforeach
		</script>
	@endpush
@endif
@section('content')
<nav class="flex" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    <li class="inline-flex items-center">
      <a href="{{ route('home')}}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        Home
      </a>
    </li>
    <li>
      <div class="flex items-center">
        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        <a href="{{ route('barang')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">Barang</a>
      </div>
    </li>
    <li aria-current="page">
      <div class="flex items-center">
        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Detail</span>
      </div>
    </li>
  </ol>
</nav>
	<div class="flex flex-col mt-5">
		<h1 class="mb-2 text-[1.5em] font-bold">
			Barang
		</h1>
		<div class="mb-2 flex">
			<img class='h-[8em] max-w-[8em] rounded-3xl border-2 drop-shadow-sm md:h-[10em] md:max-w-[10em]'
				src="{{ asset($barang->gambar) }}" alt="">
				@if ($barang->qrcode)
				<img
					class="ml-5 h-[8em] max-w-[8em] origin-top-left bg-white p-2 hover:z-10 md:h-[10em] md:max-w-[10em] md:hover:scale-125"
					src="{{ asset($barang->qrcode) }}" alt="">
				@endif
		</div>
		<div class="info-barang mb-2 ">
			<p class="text-lg font-semibold uppercase">{{ $barang->nama }}</p>
			<p>
				{{$barang->detail}}
			</p>
		</div>
	@if (!$barangPinjaman->isEmpty())

		<div class="grid h-auto w-[85vw] grid-cols-3 gap-2 md:w-[30em]">
			<div class="bg-green flex flex-col justify-center rounded-md p-2 text-center text-white">
				<p class="text-[1.5em] font-bold">{{ $barang->jumlah }}</p>
				<p class="text-md font-normal">Total Barang</p>
			</div>
			<div
				class="bg-green {{ $barang->jumlah - count($barangPinjaman) == 0 ? 'bg-gray-600' : '' }} flex flex-col justify-center rounded-md p-2 text-center text-white">
				<p class="text-[1.5em] font-bold">{{ $barang->jumlah - count($barangPinjaman) }}</p>
				<p class="text-md font-normal">Barang tersedia</p>
			</div>
			<div
				class="{{ count($barangPinjaman) == 0 ? 'bg-gray-600' : ' bg-orange-500' }} flex flex-col justify-center rounded-md p-2 text-center text-white">
				<p class="text-[1.5em] font-bold">{{ count($barangPinjaman) }}</p>
				<p class="text-md font-normal">Barang dipinjam</p>
			</div>
		</div>
	</div>
		<h1 class="mt-5 mb-2 text-[1.5em] font-medium">
			Barang dalam pinjaman
		</h1>
		<div class="grid gap-2 md:grid-cols-2">
			@foreach ($barangPinjaman as $bm)
				<div class="border-orange-500 rounded-md border p-2">
					{{-- <div class="mb-2 h-[20em] rounded-md" id="history-{{ $bm->id }}"></div> --}}
					<div class="mb-2 h-[20em] rounded-md" id="{{ $bm->id }}"></div>
					<a href="">
						<div class="mb-2 font-bold uppercase flex items-center">
							<p class="text-sm text-gray-600 mr-2">Proyek</p>
							<p class="text-lg">{{ $bm->nama_proyek }}</p>
						</div>
						<div class="mb-2 flex items-center">
							<img class="mr-1 h-4 w-4 fill-gray-600" src="../images/ic-location.svg" alt="">
							{{ $bm->alamat }}
						</div>
						@if ($bm->tgl_peminjaman && $bm->tgl_berakhir)
							<div class="mb-2 flex">
								<div class="">
									<p class="flex text-sm text-gray-600 items-center">
										<img class="mr-1 h-4 w-4 fill-gray-600" src="../images/ic-date.svg" alt="">
										Tanggal Peminjaman
									</p>
									<p class="text-sm text-black">{{ $bm->tgl_peminjaman }}</p>
								</div>
								<div class="ml-4">
									<p class="flex text-sm text-gray-600 items-center">
										<img class="mr-1 h-4 w-4 fill-gray-600" src="../images/ic-date.svg" alt="">
										Tanggal Berakhir
									</p>
									<p class="text-sm text-black">{{ $bm->tgl_berakhir }}</p>
								</div>
							</div>
						@endif
						{{-- <img src="{{mapsUrl($bm->longitude,$bm->latitude,$tokenMaps)}}" alt="Lokasi {{$barang->nama}}"> --}}
					</a>
					<a class="supervisor mb-2 flex items-center" href="">
						<img class="mr-2 w-[3em]" src="{{ $bm->foto }}" alt="">
						<div> {{ $bm->nama_supervisor }} <span
								class="rounded-full border border-gray-600 px-2 text-sm text-gray-600">Supervisor</span></div>
					</a>
				</div>
			@endforeach
		</div>
	@else
		@if ($barang->jenis == 'tidak habis pakai')
			<h1 class="mt-5 text-[1.5em] font-medium text-red-500">
				Tidak ada barang yang sedang dipinjam
			</h1>
		@endif
	@endif
	@if (!$historyPeminjamanBarang->isEmpty())
	<div class="flex items-center align-content-center my-5">
		<h1 class="text-[1.5em] font-medium">
			Riwayat Penggunaan Barang
		</h1>
	</div>
		<div class="grid gap-2 md:grid-cols-2">
		@foreach ($historyPeminjamanBarang as $hbp)
			<div class="{{ $hbp->dipinjam ? 'border-orange-500' : 'border-gray-500' }} rounded-md border p-2">
				{{-- <div class="mb-2 h-[20em] rounded-md" id="history-{{ $hbp->id }}"></div> --}}
				@if ($hbp->dipinjam)
				<div class="mb-2 h-[20em] rounded-md" id="history-{{$hbp->id}}"></div>
				@endif
				<a href="">
					<div class="mb-2 font-bold uppercase flex items-center">
						<p class="text-sm text-gray-600 mr-2">Proyek</p>
						<p class="text-lg">{{ $hbp->nama_proyek }}</p>
					</div>
					<div class="mb-2 flex items-center">
						<img class="mr-1 h-4 w-4 fill-gray-600" src="../../images/ic-location.svg" alt="">
						{{ $hbp->alamat }}
					</div>
				</a>
					@if ($hbp->tgl_peminjaman && $hbp->tgl_berakhir)
						<div class="mb-2 flex">
							<div class="">
								<p class="flex text-sm text-gray-600 items-center">
									<img class="mr-1 h-4 w-4 fill-gray-600" src="../../images/ic-date.svg" alt="">
									Tanggal Peminjaman
								</p>
								<p class="text-sm text-black">{{ $hbp->tgl_peminjaman }}</p>
							</div>
							<div class="ml-4">
								<p class="flex text-sm text-gray-600 items-center">
									<img class="mr-1 h-4 w-4 fill-gray-600" src="../../images/ic-date.svg" alt="">
									Tanggal Berakhir
								</p>
								<p class="text-sm text-black">{{ $hbp->tgl_berakhir }}</p>
							</div>
						</div>
					@endif
					{{-- <img src="{{mapsUrl($hbp->longitude,$hbp->latitude,$tokenMaps)}}" alt="Lokasi {{$barang->nama}}"> --}}
				<a class="supervisor mb-2 flex items-center" href="">
					<img class="mr-2 w-[3em]" src="{{ $hbp->foto }}" alt="">
					<div> {{ $hbp->nama_supervisor }} <span
							class="rounded-full border border-gray-600 px-2 text-sm text-gray-600">Supervisor</span></div>
				</a>
			</div>
		@endforeach
	</div>
	<div class="mt-5">
		{{ $historyPeminjamanBarang->links() }}
	</div>
	@endif
@endsection
