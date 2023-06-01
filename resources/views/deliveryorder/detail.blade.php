@extends('layouts.detail')

{{-- @if ($deliveryorder->logistic) --}}
@push('prepend-script')
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
		integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
		crossorigin="" />
		<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
		crossorigin=""></script>
		<script>
			var map = L.map('map').setView([-6.2501422, 106.8564921], 14);
			barangIcon = L.icon({
							iconUrl: '/images/ic_barang_location.png',
							iconSize:     [24, 29.33], // size of the icon
					});
			driverIcon = L.icon({
							iconUrl: '/images/ic_driver_location.png',
							iconSize:     [24, 29.33], // size of the icon
					});
			var markers = [
				@if ($deliveryorder->logistic)
					L.marker([{{$deliveryorder->logistic->logistic->latitude}}, {{$deliveryorder->logistic->logistic->longitude}}], {icon:driverIcon}).bindPopup('{{$deliveryorder->logistic->nama}}'),
				@endif
					L.marker([{{$deliveryorder->perusahaan->latitude}}, {{$deliveryorder->perusahaan->longitude}}], {icon:barangIcon}).bindPopup('{{$deliveryorder->untuk_perusahaan}}'),
			];
			var group = L.featureGroup(markers).addTo(map);
			setTimeout(function () {
				map.fitBounds(group.getBounds());
			}, 0);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
		</script>
	@endpush
{{-- @endif --}}
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
        <a href="{{ route('delivery-order')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">Delivery Order</a>
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
			Delivery Order
		</h1>
		<div class="info-barang mb-2">
			<p class="text-lg font-semibold uppercase"><span class="text-base normal-case font-normal">Kode Delivery: </span> {{ $deliveryorder->kode_do }}</p>
			<p class="text-lg font-semibold uppercase"><span class="text-base normal-case font-normal">Untuk Perusahaan: </span> {{ $deliveryorder->perusahaan->nama }}</p>
		</div>
		<a class="bg-green py-1 px-2 text-white rounded-lg self-start mb-2" href="{{route('delivery-order.cetak', $deliveryorder->id)}}">File Delivery Order</a>
		<h1 class="mt-2 mb-2 text-[1.5em] font-medium">
			Delivery Order dalam perjalanan
		</h1>
		<div class="grid gap-2 md:grid-cols-2 h-[70vh]">
			<div class="border-green rounded-md border p-2 relative">
				@if ($deliveryorder->logistic)
					<a target="_blank" 
					class="right-5 top-5 absolute border border-gray-700 bg-green py-1 px-2 text-white rounded-lg self-start mb-2 z-50"
					href="https://www.google.com/maps?saddr={{$deliveryorder->logistic->logistic->latitude}},{{$deliveryorder->logistic->logistic->longitude}}&daddr={{$deliveryorder->perusahaan->latitude}},{{$deliveryorder->perusahaan->longitude}}">
						Arahkan ke rute antara driver dengan perusahaan
					</a>
				@endif
				<div class="mb-2 h-full rounded-md z-0" id="map"></div>
			</div>
		</div>
@endsection
