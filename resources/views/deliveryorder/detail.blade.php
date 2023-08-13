@extends('layouts.detail')
@if ($deliveryOrder->status != 'SELESAI')
		@push('prepend-script')
				<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
						integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
						crossorigin="" />
				<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
				<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
						integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
						crossorigin=""></script>
				<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
				<script type="module">
						var map, barangIcon, driverIcon, markers, group = undefined

						import {
								initializeApp
						} from "https://www.gstatic.com/firebasejs/9.22.1/firebase-app.js";
						import {
								getDatabase,
								ref,
								onValue
						} from "https://www.gstatic.com/firebasejs/9.22.1/firebase-database.js";
						const firebaseConfig = {
								apiKey: "{{ env('FIREBASE_SERVER_KEY') }}",
								authDomain: "burda-contraco.firebaseapp.com",
								databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
								projectId: "burda-contraco",
								storageBucket: "burda-contraco.appspot.com",
								messagingSenderId: "522699406443",
								appId: "1:522699406443:web:13e1437c0473a52530c52e",
								measurementId: "G-9S4HPEYXVM"
						};

						// Initialize Firebase
						const app = initializeApp(firebaseConfig);
						const database = getDatabase(app);

						var firstTime = true;
						const logisticRef = ref(database, "logistic/{{ $deliveryOrder->logistic_id }}");
						// console.log(logisticRef)
						onValue(logisticRef, (snapshot) => {
								const data = snapshot.val();
								updateCoordinateMap(data.latitude, data.longitude, firstTime)
								var coordinateAddress = document.getElementById("coordinate-address");
								coordinateAddress.setAttribute("href",
										`https://www.google.com/maps?saddr=${data.latitude},${data.longitude}&daddr={{ $deliveryOrder->perusahaan->latitude }},{{ $deliveryOrder->perusahaan->longitude }}`
								)
								firstTime = false;
						});

						function updateCoordinateMap(latitude, longitude, firstTime) {
								if (!firstTime) {
										map.remove();
								}
								map = L.map('map', {
										doubleClickZoom: false,
										closePopupOnClick: false,
										dragging: false,
										zoomSnap: false,
										zoomDelta: false,
										trackResize: false,
										touchZoom: false,
										scrollWheelZoom: false,
										zoomControl: false,
								}).setView([-6.2501422, 106.8564921], 14);
								barangIcon = L.icon({
										iconUrl: '/images/ic_barang_location.png',
										iconSize: [24, 29.33], // size of the icon
								});
								driverIcon = L.icon({
										iconUrl: '/images/ic_driver_location.png',
										iconSize: [24, 29.33], // size of the icon
								});
								markers = [
										@if ($deliveryOrder->logistic)
												L.marker([latitude, longitude], {
																icon: driverIcon
														}).bindPopup('{{ $deliveryOrder->logistic->nama }}'),
										@endif
										L.marker([{{ $deliveryOrder->perusahaan->latitude }}, {{ $deliveryOrder->perusahaan->longitude }}], {
												icon: barangIcon
										}).bindPopup('{{ $deliveryOrder->perusahaan->nama }}'),
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
												L.latLng(latitude, longitude),
												L.latLng({{ $deliveryOrder->perusahaan->latitude }},
														{{ $deliveryOrder->perusahaan->longitude }})
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
				</script>
		@endpush
@endif
@push('addon-style')
		<style>
				.text-xsm {
						font-size: 0.6rem
								/* 14px */
								!important;
						line-height: 1rem
								/* 20px */
								!important;
				}

				.page {
						border: 1px #D3D3D3 solid;
						border-radius: 5px;
						background: white;
						box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
				}
		</style>
@endpush
@section('content')
		<nav class="flex print:hidden" aria-label="Breadcrumb">
				<ol class="inline-flex items-center space-x-1 md:space-x-3">
						<li class="inline-flex items-center">
								<a href="{{ route('home') }}"
										class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
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
												class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2">Delivery Order</a>
								</div>
						</li>
						<li aria-current="page">
								<div class="flex items-center">
										<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd"
														d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
														clip-rule="evenodd"></path>
										</svg>
										<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail</span>
								</div>
						</li>
				</ol>
		</nav>

		<div class="flex items-center justify-end">
				<div class="my-2 mr-2 flex w-full flex-col">
						<h1 class="text-[1.5em] font-bold">
								Delivery Order
						</h1>
						<p class="{{ $deliveryOrder->status == 'SELESAI' ? 'text-green' : 'text-orange-500' }} mt-1 text-sm font-medium">
								{{ \App\Helpers\Utils::underscoreToNormal($deliveryOrder->status) }}</p>
						<p class="mt-1 text-sm font-medium"><span class="font-normal">Tanggal Dibuat:
								</span>{{ \App\Helpers\Date::parseMilliseconds($deliveryOrder->created_at) }}</p>
						<p class="mt-1 text-sm font-medium"><span class="font-normal">Terakhir diperbarui:
								</span>{{ \App\Helpers\Date::parseMilliseconds($deliveryOrder->updated_at) }}</p>
				</div>
				<div class="flex flex-wrap">
						@if (
								$deliveryOrder->status == 'MENUNGGU_KONFIRMASI_DRIVER' &&
										$authUser->role == 'LOGISTIC' &&
										$authUser->id == $deliveryOrder->logistic_id)
								<a href="{{ route('delivery-order.edit', $deliveryOrder->ttd) }}" target="_blank"
										class="mb-2 mr-5 rounded-md bg-green-400 px-3 py-1 text-white">
										Ambil Barang
								</a>
						@endif
						@if (
								$deliveryOrder->status != 'MENUNGGU_KONFIRMASI_DRIVER' &&
										$authUser->role == 'LOGISTIC' &&
										$authUser->id == $deliveryOrder->logistic_id)
								<a href="{{ route('delivery-order.edit', $deliveryOrder->ttd) }}" target="_blank"
										class="mb-2 mr-5 rounded-md bg-green-400 px-3 py-1 text-white">
										Upload Foto Bukti
								</a>
						@endif
						@if ($deliveryOrder->status == 'DRIVER_DALAM_PERJALANAN' && $authUser->role != 'LOGISTIC')
								<a href="{{ route('delivery-order.edit', $deliveryOrder->ttd) }}" target="_blank"
										class="mb-2 mr-5 rounded-md bg-green-400 px-3 py-1 text-white">
										Tandai Selesai
								</a>
						@endif
						@if ($deliveryOrder->status == 'MENUNGGU_KONFIRMASI_DRIVER' && $deliveryOrder->purchasing_id == $authUser->id)
								<a href="{{ route('delivery-order.edit', $deliveryOrder->ttd) }}" target="_blank"
										class="mb-2 mr-5 rounded-md bg-green-400 px-3 py-1 text-white">
										Ubah DO
								</a>
						@endif
						<a href="{{ route('signature.verifiedTTDDeliveryOrder', $deliveryOrder->ttd) }}" target="_blank"
								class="mb-2 mr-5 rounded-md bg-green-400 px-3 py-1 text-white">
								Verifikasi TTD
						</a>
						<a href="{{ route('delivery-order.downloadPDF', $deliveryOrder->id) }}" target="_blank"
								class="mb-2 mr-5 rounded-md bg-green-400 px-3 py-1 text-white">
								Download PDF
						</a>
				</div>
		</div>
		<div class="my-3 grid gap-2 md:grid-cols-2">
				<div class="page row-span-2 flex min-h-[29cm] w-full max-w-[21cm] flex-col overflow-scroll p-5 text-sm">
						<div class="mb-1 mt-5 flex w-full justify-between">
								<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo-burda.png'))) }}"
										alt="" class="my-5 h-10 w-auto self-start">
								<p class="text-xsm ml-2 text-right">
										<span class="text-base font-bold">
												PT.BURDA CONTRACO
										</span>
										<br>Wisma NH Jl. Raya Pasar Minggu Blok B-C No. 2, RT 002 RW 002, Pancoran – Jakarta Selatan
										<br>JL. Pengadegan Selatan II No. 1 Pancoran – Jakarta Selatan
										<br>Telp. (62-21) 7988968 – 70 Fax. (62-21) 79195987
										<br>website: www.burdacontraco.co.id, email: info@burdacontraco.co.id / admin@burdacontraco.co.id
								</p>
						</div>
						<div class="mb-1 h-auto w-full bg-primary text-center">
								<p class="text-xsm text-white">
										Civil Works | Steel Construction | Infrastructure
								</p>
						</div>
						<div class="w-full bg-primary">
								<h1 class="w-auto p-1 text-center text-base text-white print:p-0 print:text-black">FORM DELIVERY ORDER</h1>
						</div>
						<table class="my-5 w-full table-auto border border-gray-600 text-sm">
								<tbody>
										<tr>
												<td class="border border-gray-600 bg-green-200 p-1">Tanggal Pengambilan</td>
												<td class="border border-gray-600 p-1">
														{{ \App\Helpers\Date::parseMilliseconds($deliveryOrder->created_at, 'dddd, D MMM YYYY') }}</td>
										</tr>
										<tr>
												<td class="border border-gray-600 bg-green-200 p-1">No.</td>
												<td class="border border-gray-600 p-1">{{ $deliveryOrder->kode_do }}</td>
										</tr>
										<tr>
												<td class="border border-gray-600 bg-green-200 p-1">Perihal</td>
												<td class="border border-gray-600 p-1">{{ $deliveryOrder->perihal }}</td>
										</tr>
										<tr>
												<td class="border border-gray-600 bg-green-200 p-1">UP</td>
												<td class="border border-gray-600 p-1">{{ $deliveryOrder->untuk_perhatian }}</td>
										</tr>
								</tbody>
						</table>
						<div class="message mb-5 w-full">
								<p>Kepada Yth,<br>{{ $deliveryOrder->perusahaan->nama }}<br>
										Melalui memo ini dari PT. Burda Contraco menyampaikan untuk memohon<br>
										di berikan kepada pembawa memo material berupa:</p>
						</div>
						<table class="mb-5 w-full table-auto text-center text-sm">
								<thead>
										<tr>
												<th class="border border-gray-600 bg-green-200 p-1">No.</th>
												<th class="border border-gray-600 bg-green-200 p-1">Nomor PO</th>
												<th class="border border-gray-600 bg-green-200 p-1">Grade</th>
												<th class="border border-gray-600 bg-green-200 p-1">Size</th>
												<th class="border border-gray-600 bg-green-200 p-1">Qty</th>
												<th class="border border-gray-600 bg-green-200 p-1">Sat</th>
												<th class="border border-gray-600 bg-green-200 p-1">Keterangan</th>
										</tr>
								</thead>
								<tbody>
										@foreach ($deliveryOrder->preOrder as $po)
												<tr>
														<td class="border border-gray-600 p-1">{{ $loop->iteration }}</td>
														<td class="border border-gray-600 p-1">{{ $po->kode_po }}</td>
														<td class="border border-gray-600 p-1">{{ $po->nama_material }}</td>
														<td class="border border-gray-600 p-1">{{ $po->ukuran }}</td>
														<td class="border border-gray-600 p-1">{{ $po->jumlah }}</td>
														<td class="border border-gray-600 p-1">{{ $po->satuan }}</td>
														<td class="border border-gray-600 p-1">{{ $po->keterangan }}</td>
												</tr>
										@endforeach
								</tbody>
						</table>
						<table class="mb-5 table-auto">
								<tbody>
										<tr>
												<td>No. Kendaraan</td>
												@if ($deliveryOrder->kendaraan)
														<td>: {{ $deliveryOrder->kendaraan->plat_nomor }}</td>
												@else
														<td class="text-red-600">: Admin Gudang belum memilih kendaraan</td>
												@endif
										</tr>
										<tr>
												<td>Supir</td>
												@if ($deliveryOrder->logistic)
														<td>: {{ $deliveryOrder->logistic->nama }}</td>
												@else
														<td class="text-red-600">: Admin Gudang belum memilih supir</td>
												@endif
										</tr>
								</tbody>
						</table>
						<p class="mb-5">Atas perhatiannya kami ucapkan terimakasih <br>Mengetahui,</p>
						<div class="flex justify-end">
								<div class="flex flex-col">
										<p class="text-center">Hormat Kami, <br><span class="font-bold">PT. BURDA CONTRACO</span></p>
										<div class="flex">
												@if ($ttdPath)
														<div class="bg-contain bg-center bg-no-repeat"
																style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
																<img
																		src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $deliveryOrder->purchasing->ttd))) }}"
																		alt="" class="my-3 w-40 self-center">
														</div>
														<div class="bg-contain bg-center bg-no-repeat">
																<img src="{{ $ttdPath }}" alt="" class="my-3 w-28 self-center">
														</div>
												@else
														<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
																style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
														</div>
												@endif
										</div>
										<p class="text-center">
												{{ $deliveryOrder->purchasing->nama }}<br><span>Purchasing</span><br>{{ $deliveryOrder->purchasing->no_hp }}
										</p>
								</div>
						</div>
				</div>
				<div class="flex h-max flex-wrap">
						<div id="driver-preview" class="mr-2 flex h-max flex-col rounded-xl p-3 shadow-md shadow-gray-100">
								<p class="text-md mt-2 font-bold">Driver</p>
								<div class="flex flex-col p-2">
										<div class="mb-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
												style="background-image: url('{{ asset($deliveryOrder->logistic->foto) }}')"></div>
										<div class="flex w-full flex-col">
												<div class="mt-1 flex flex-col md:flex-row md:items-center">
														<p class="font-medium line-clamp-2">{{ $deliveryOrder->logistic->nama }}</p>
												</div>
												<p class="my-1 text-sm font-normal uppercase line-clamp-1">{{ $deliveryOrder->logistic->no_hp }}</p>
										</div>
								</div>
						</div>
						<div id="kendaraan-preview" class="mr-2 flex h-max flex-col rounded-xl p-3 shadow-md shadow-gray-100">
								<p class="text-md mt-2 font-bold">Kendaraan</p>
								<div class="flex flex-col p-2">
										<div class="mb-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
												style="background-image: url('{{ asset($deliveryOrder->kendaraan->gambar) }}')"></div>
										<div class="flex w-full flex-col">
												<span
														class="mb-1 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600 md:mb-0 md:mr-1">
														{{ $deliveryOrder->kendaraan->jenis }}
												</span>
												<div class="mt-1 flex flex-col md:flex-row md:items-center">
														<p class="font-medium line-clamp-2">{{ $deliveryOrder->kendaraan->merk }}</p>
												</div>
												<p class="my-1 text-sm font-normal uppercase line-clamp-1">{{ $deliveryOrder->kendaraan->plat_nomor }}</p>
												<div class="flex items-center overflow-x-auto">
														<img src="/images/ic_gudang.png" alt="" class="mr-1 h-[1.1em] w-auto">
														<p class="text-sm font-normal line-clamp-2">{{ $deliveryOrder->gudang->nama }}</p>
												</div>
										</div>
								</div>
						</div>
						<div class="flex flex-wrap">
								<div id="gudang-preview" class="mr-2 flex h-max flex-col rounded-xl p-3 shadow-md shadow-gray-100">
										<p class="text-md mt-2 font-bold">Gudang</p>
										<div class="flex flex-col p-2">
												<div class="mb-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
														style="background-image: url('{{ asset($deliveryOrder->gudang->gambar) }}')"></div>
												<div class="flex w-full flex-col">
														<span
																class="mb-1 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600 md:mb-0 md:mr-1">
																{{ $deliveryOrder->gudang->provinsi }}
														</span>
														<div class="mt-1 flex flex-col md:flex-row md:items-center">
																<p class="font-medium line-clamp-2">{{ $deliveryOrder->gudang->nama }}</p>
														</div>
														<p class="text-xsm my-1 max-w-[27ch] font-normal">{{ $deliveryOrder->gudang->alamat }}</p>
														<a target="_blank" class="mb-2 self-start rounded-lg border bg-green px-2 py-1 text-sm text-white"
																href="https://www.google.com/maps/search/?api=1&query={{ $deliveryOrder->gudang->latitude }},{{ $deliveryOrder->gudang->longitude }}">
																Telusuri gudang
														</a>
												</div>
										</div>
								</div>
								<div id="perusahaan-preview" class="mr-2 flex h-max flex-col rounded-xl p-3 shadow-md shadow-gray-100">
										<p class="text-md mt-2 font-bold">Perusahaan</p>
										<div class="flex flex-col p-2">
												<div class="mb-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
														style="background-image: url('{{ asset($deliveryOrder->perusahaan->gambar) }}')"></div>
												<div class="flex w-full flex-col">
														<span
																class="mb-1 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600 md:mb-0 md:mr-1">
																{{ $deliveryOrder->perusahaan->provinsi }}
														</span>
														<div class="mt-1 flex flex-col md:flex-row md:items-center">
																<p class="font-medium line-clamp-2">{{ $deliveryOrder->perusahaan->nama }}</p>
														</div>
														<p class="text-xsm my-1 max-w-[27ch] font-normal">{{ $deliveryOrder->perusahaan->alamat }}</p>
														<a target="_blank" class="mb-2 self-start rounded-lg border bg-green px-2 py-1 text-sm text-white"
																href="https://www.google.com/maps/search/?api=1&query={{ $deliveryOrder->perusahaan->latitude }},{{ $deliveryOrder->perusahaan->longitude }}">
																Telusuri perusahaan
														</a>
												</div>
										</div>
								</div>
						</div>
						<div id="foto-bukti-preview" class="mr-2 flex h-max flex-col rounded-xl p-3 shadow-md shadow-gray-100">
								<p class="text-md mt-2 font-bold">Foto Bukti</p>
								@if ($deliveryOrder->foto_bukti)
										<div class="flex flex-col p-2">
												<div class="mb-2 h-[10em] w-[8em] rounded-md bg-cover bg-center"
														style="background-image: url('{{ asset($deliveryOrder->foto_bukti) }}')"></div>
										</div>
								@else
										<p class="mt-2 text-sm text-red-600">Tidak tersedia</p>
								@endif
						</div>
						@if ($deliveryOrder->adminGudang)
						<div id="tandai-selesai-preview" class="mr-2 flex h-max flex-col rounded-xl p-3 shadow-md shadow-gray-100">
							<p class="text-md mt-2 font-bold">Yang Menandai Selesai</p>
								<div class="flex flex-col p-2">
										<div class="mb-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
												style="background-image: url('{{ asset($deliveryOrder->adminGudang->foto) }}')"></div>
										<div class="flex w-full flex-col">
												<div class="mt-1 flex flex-col md:flex-row md:items-center">
														<p class="font-medium line-clamp-2">{{ $deliveryOrder->adminGudang->nama }}</p>
												</div>
												<p class="my-1 text-sm font-normal uppercase line-clamp-1">{{ $deliveryOrder->adminGudang->no_hp }}</p>
										</div>
								</div>
						</div>
						@endif
				</div>
				@if ($deliveryOrder->status != 'SELESAI')
						<div class="relative h-[70vh] rounded-md border border-green p-2">
								@if ($deliveryOrder->logistic)
										<a target="_blank" id="coordinate-address"
												class="absolute right-5 top-5 z-50 mb-2 self-start rounded-lg border border-gray-700 bg-green px-2 py-1 text-white"
												href="https://www.google.com/maps?saddr={{ $deliveryOrder->logistic->logistic->latitude }},{{ $deliveryOrder->logistic->logistic->longitude }}&daddr={{ $deliveryOrder->perusahaan->latitude }},{{ $deliveryOrder->perusahaan->longitude }}">
												Arahkan ke rute antara driver dengan perusahaan
										</a>
								@endif
								<div class="z-0 mb-2 h-full rounded-md" id="map"></div>
						</div>
				@endif
		</div>
@endsection
