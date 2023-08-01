@extends('layouts.detail')

{{-- @if ($deliveryorder->logistic) --}}
@if ($deliveryorder->status != 'SELESAI')
		@push('prepend-script')
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
		integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
		crossorigin="" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
	<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
		integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
		crossorigin=""></script>
	<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
	<script>
		var map = L.map('map').setView([-6.2501422, 106.8564921], 14);
		barangIcon = L.icon({
			iconUrl: '/images/ic_barang_location.png',
			iconSize: [24, 29.33], // size of the icon
		});
		driverIcon = L.icon({
			iconUrl: '/images/ic_driver_location.png',
			iconSize: [24, 29.33], // size of the icon
		});
		var markers = [
			@if ($deliveryorder->logistic)
				L.marker([{{ $deliveryorder->logistic->logistic->latitude }},
						{{ $deliveryorder->logistic->logistic->longitude }}
					], {
						icon: driverIcon
					}).bindPopup('{{ $deliveryorder->logistic->nama }}'),
			@endif
			L.marker([{{ $deliveryorder->perusahaan->latitude }}, {{ $deliveryorder->perusahaan->longitude }}], {
				icon: barangIcon
			}).bindPopup('{{ $deliveryorder->perusahaan->nama }}'),
		];
		var group = L.featureGroup(markers).addTo(map);
		setTimeout(function() {
			map.fitBounds(group.getBounds());
		}, 0);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		L.Routing.control({
			waypoints: [
				L.latLng({{ $deliveryorder->logistic->logistic->latitude }},
					{{ $deliveryorder->logistic->logistic->longitude }}),
				L.latLng({{ $deliveryorder->perusahaan->latitude }}, {{ $deliveryorder->perusahaan->longitude }})
			],
			show: false,
			createMarker: function() {
				return null;
			},
			lineOptions: {
				styles: [{
					color: '#7000FF',
					opacity: 1,
					weight: 5
				}]
			}
		}).addTo(map);
	</script>
	@extends('includes.firebase-realtime-db')
	@push('script-rtdb')
		const logisticRef = ref(database, "logistic/{{ $deliveryorder->logistic_id }}");
		// console.log(logisticRef)
		onValue(logisticRef, (snapshot) => {
			const data = snapshot.val();
			updateCoordinateMap(data.latitude,data.longitude)
		});

		function updateCoordinateMap(latitude, longitude) {
			map.remove();
			map = L.map('map').setView([-6.2501422, 106.8564921], 14);
			barangIcon = L.icon({
				iconUrl: '/images/ic_barang_location.png',
				iconSize: [24, 29.33], // size of the icon
			});
			driverIcon = L.icon({
				iconUrl: '/images/ic_driver_location.png',
				iconSize: [24, 29.33], // size of the icon
			});
			markers = [
				@if ($deliveryorder->logistic)
					L.marker([latitude,longitude], {
							icon: driverIcon
						}).bindPopup('{{ $deliveryorder->logistic->nama }}'),
				@endif
				L.marker([{{ $deliveryorder->perusahaan->latitude }}, {{ $deliveryorder->perusahaan->longitude }}], {
					icon: barangIcon
				}).bindPopup('{{ $deliveryorder->perusahaan->nama }}'),
			];
			group = L.featureGroup(markers).addTo(map);
			setTimeout(function() {
				map.fitBounds(group.getBounds());
			}, 0);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

			L.Routing.control({
				waypoints: [
					L.latLng(latitude,longitude),
					L.latLng({{ $deliveryorder->perusahaan->latitude }},
						{{ $deliveryorder->perusahaan->longitude }})
				],
				show: false,
				createMarker: function() {
					return null;
				},
				lineOptions: {
					styles: [{
						color: '#7000FF',
						opacity: 1,
						weight: 5
					}]
				}
			}).addTo(map);
		}
	@endpush

@endpush
@endif
@section('content')
	<nav class="flex" aria-label="Breadcrumb">
		<ol class="inline-flex items-center space-x-1 md:space-x-3">
			<li class="inline-flex items-center">
				<a href="{{ route('home') }}"
					class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
					<svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
						</path>
					</svg>
					Home
				</a>
			</li>
			<li>
				<div class="flex items-center">
					<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
							clip-rule="evenodd"></path>
					</svg>
					<a href="{{ route('delivery-order') }}"
						class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white md:ml-2">Delivery
						Order</a>
				</div>
			</li>
			<li aria-current="page">
				<div class="flex items-center">
					<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
							clip-rule="evenodd"></path>
					</svg>
					<span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">Detail</span>
				</div>
			</li>
		</ol>
	</nav>
	<div class="mt-5 flex flex-col">
		<h1 class="mb-2 text-[1.5em] font-bold">
			Delivery Order
		</h1>
		<div class="info-barang mb-2">
			<p class="text-lg font-semibold uppercase"><span class="text-base font-normal normal-case">Kode Delivery: </span>
				{{ $deliveryorder->kode_do }}</p>
			<p class="text-lg font-semibold uppercase"><span class="text-base font-normal normal-case">Untuk Perusahaan: </span>
				{{ $deliveryorder->perusahaan->nama }}</p>
			<p class="text-lg font-semibold uppercase"><span class="text-base font-normal normal-case">Status: </span>
				{{ \App\Helpers\Utils::underscoreToNormal($deliveryorder->status) }}
		</div>
		<a class="mb-2 self-start rounded-lg bg-green py-1 px-2 text-white"
			href="{{ route('delivery-order.cetak', $deliveryorder->id) }}">File Delivery Order</a>
		@if ($deliveryorder->status != 'SELESAI')
		<div class="grid h-[70vh] gap-2 md:grid-cols-1">
			<div class="relative rounded-md border border-green p-2">
				@if ($deliveryorder->logistic)
					<a target="_blank"
						class="absolute right-5 top-5 z-50 mb-2 self-start rounded-lg border border-gray-700 bg-green py-1 px-2 text-white"
						href="https://www.google.com/maps?saddr={{ $deliveryorder->logistic->logistic->latitude }},{{ $deliveryorder->logistic->logistic->longitude }}&daddr={{ $deliveryorder->perusahaan->latitude }},{{ $deliveryorder->perusahaan->longitude }}">
						Arahkan ke rute antara driver dengan perusahaan
					</a>
				@endif
				<div class="z-0 mb-2 h-full rounded-md" id="map"></div>
			</div>
		</div>
		@endif
	@endsection
