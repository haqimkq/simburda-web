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
		var map,barangIcon,driverIcon,markers,group = undefined

		import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-app.js";
    import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-database.js";
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
    const database  = getDatabase(app);

		var firstTime = true;
		const logisticRef = ref(database, "logistic/{{ $deliveryOrder->logistic_id }}");
		// console.log(logisticRef)
		onValue(logisticRef, (snapshot) => {
			const data = snapshot.val();
			updateCoordinateMap(data.latitude,data.longitude, firstTime)
			var coordinateAddress = document.getElementById("coordinate-address");
			coordinateAddress.setAttribute("href", `https://www.google.com/maps?saddr=${data.latitude},${data.longitude}&daddr={{ $deliveryOrder->perusahaan->latitude }},{{ $deliveryOrder->perusahaan->longitude }}`)
			firstTime = false;
		});

		function updateCoordinateMap(latitude, longitude, firstTime) {
			if(!firstTime) {
				map.remove();
			}
			map = L.map('map',{
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
					L.marker([latitude,longitude], {
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
					L.latLng(latitude,longitude),
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
			font-size: 0.6rem/* 14px */ !important;
			line-height: 1rem/* 20px */ !important;
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
      <a href="{{ route('home')}}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 ">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        Home
      </a>
    </li>
    <li>
      <div class="flex items-center">
        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        <a href="{{ route('delivery-order')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 ">Delivery Order</a>
      </div>
    </li>
    <li aria-current="page">
      <div class="flex items-center">
        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail</span>
      </div>
    </li>
  </ol>
</nav>

<div class="flex justify-end items-center">
	<div class="flex flex-col w-full my-2">
		<h1 class="text-[1.5em] font-bold ">
			Delivery Order 
		</h1>
		<p class="mt-1 text-sm font-medium {{ ($deliveryOrder->status == "SELESAI") ? "text-green" : "text-orange-500"  }}">{{ \App\Helpers\Utils::underscoreToNormal($deliveryOrder->status) }}</p>
	</div>
	<a href="{{route('signature.verifiedTTDDeliveryOrder', $deliveryOrder->ttd)}}" target="_blank" class="rounded-md py-1 px-3 mr-5 text-white bg-green-400" >
		Verifikasi TTD
	</a>
	<a href="{{route('delivery-order.downloadPDF', $deliveryOrder->id)}}" target="_blank" class="rounded-md py-1 px-3 text-white bg-primary" >
		Download PDF
	</a>
</div>
<div class="grid gap-2 md:grid-cols-2 my-3">
	<div class="flex flex-col text-sm page p-5 overflow-scroll w-full row-span-2">
		<div class="w-full mb-1 flex mt-5 justify-between">
			<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo-burda.png'))) }}" alt="" class="self-start h-10 w-auto my-5">
			<p class=" ml-2 text-xsm text-right">
			<span class="text-base font-bold">
				PT.BURDA CONTRACO
			</span>
			<br>Wisma NH Jl. Raya Pasar Minggu Blok B-C No. 2, RT 002 RW 002, Pancoran – Jakarta Selatan
			<br>JL. Pengadegan Selatan II No. 1 Pancoran – Jakarta Selatan
			<br>Telp. (62-21) 7988968 – 70  Fax. (62-21) 79195987
			<br>website: www.burdacontraco.co.id, email: info@burdacontraco.co.id / admin@burdacontraco.co.id</p>
		</div>
		<div class="bg-primary w-full h-auto mb-1 text-center">
			<p class="text-white text-xsm">
				Civil Works | Steel Construction | Infrastructure
			</p>
		</div>
		<div class="bg-primary w-full">
			<h1 class="p-1 text-base w-auto print:p-0 text-white text-center print:text-black">FORM DELIVERY ORDER</h1>
		</div>
		<table class="table-auto w-full my-5 border border-gray-600 text-sm">
			<tbody>
				<tr>
					<td class="p-1 border border-gray-600 bg-green-200 ">Tanggal Pengambilan</td>
					<td class="p-1 border border-gray-600">{{\App\Helpers\Date::parseMilliseconds($deliveryOrder->created_at,'dddd, D MMM YYYY')}}</td>
				</tr>
				<tr>
					<td class="p-1 border border-gray-600 bg-green-200 ">No.</td>
					<td class="p-1 border border-gray-600">{{$deliveryOrder->kode_do}}</td>
				</tr>
				<tr>
					<td class="p-1 border border-gray-600 bg-green-200 ">Perihal</td>
					<td class="p-1 border border-gray-600">{{$deliveryOrder->perihal}}</td>
				</tr>
				<tr>
					<td class="p-1 border border-gray-600 bg-green-200 ">UP</td>
					<td class="p-1 border border-gray-600">{{$deliveryOrder->untuk_perhatian}}</td>
				</tr>
			</tbody>
		</table>
		<div class="message mb-5 w-full">
			<p>Kepada Yth,<br>{{$deliveryOrder->perusahaan->nama}}<br>
				Melalui memo ini dari PT. Burda Contraco menyampaikan untuk memohon<br>
				di berikan kepada pembawa memo material berupa:</p>
		</div>
		<table class="table-auto w-full mb-5 text-center text-sm">
			<thead>
				<tr>
					<th class="p-1 border border-gray-600 bg-green-200">No.</th>
					<th class="p-1 border border-gray-600 bg-green-200">Nomor PO</th>
					<th class="p-1 border border-gray-600 bg-green-200">Grade</th>
					<th class="p-1 border border-gray-600 bg-green-200">Size</th>
					<th class="p-1 border border-gray-600 bg-green-200">Qty</th>
					<th class="p-1 border border-gray-600 bg-green-200">Sat</th>
					<th class="p-1 border border-gray-600 bg-green-200">Keterangan</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($deliveryOrder->preOrder as $po)
				<tr>
					<td class="p-1 border border-gray-600">{{$loop->iteration}}</td>
					<td class="p-1 border border-gray-600">{{$po->kode_po}}</td>
					<td class="p-1 border border-gray-600">{{$po->nama_material}}</td>
					<td class="p-1 border border-gray-600">{{$po->ukuran}}</td>
					<td class="p-1 border border-gray-600">{{$po->jumlah}}</td>
					<td class="p-1 border border-gray-600">{{$po->satuan}}</td>
					<td class="p-1 border border-gray-600">{{$po->keterangan}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<table class="table-auto mb-5">
			<tbody>
				<tr>
					<td>No. Kendaraan</td>
					@if ($deliveryOrder->kendaraan)
						<td>: {{$deliveryOrder->kendaraan->plat_nomor}}</td>
					@else
						<td class="text-red-600">: Admin Gudang belum memilih kendaraan</td>
					@endif
				</tr>
				<tr>
					<td>Supir</td>
					@if ($deliveryOrder->logistic)
						<td>: {{$deliveryOrder->logistic->nama}}</td>
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
					<div class="bg-center bg-no-repeat bg-contain" style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
						<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/'.$deliveryOrder->purchasing->ttd)))}}" alt="" class=" self-center w-40 my-3">
					</div>
					<div class="bg-center bg-no-repeat bg-contain">
						<img src="{{ asset("assets/ttd-verification/$deliveryOrder->ttd.png") }}" alt="" class=" self-center w-28 my-3">
					</div>
					@else
					<div class="bg-center bg-no-repeat bg-contain w-40 h-24" style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
					</div>
					@endif
				</div>
				<p class="text-center">{{$deliveryOrder->purchasing->nama}}<br><span>Purchasing</span><br>{{$deliveryOrder->purchasing->no_hp}}</p>
			</div>
		</div>
	</div>
	<div class="flex flex-wrap h-max">
		<div id="driver-preview" class="flex flex-col rounded-xl p-3 shadow-md shadow-gray-100 mr-2  h-max">
			<p class="text-md mt-2 font-bold">Driver</p>
				<div class="flex flex-col p-2">
					<div class="mb-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
							style="background-image: url('{{ asset($deliveryOrder->logistic->foto) }}')"></div>
					<div class="flex w-full flex-col">
							<div class="mt-1 flex flex-col md:flex-row md:items-center">
									<p class="font-medium line-clamp-2">{{ $deliveryOrder->logistic->nama }}</p>
							</div>
							<p class="my-1 text-sm font-normal uppercase line-clamp-1">{{ $deliveryOrder->logistic->no_hp }}</p>
							<div class="flex flex-wrap">
								<div class="mr-2 flex items-center md:flex-col lg:flex-row">
										<p class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
												{{ count($deliveryOrder->logistic->activeDeliveryOrderLogistic) }} DO Aktif
										</p>
								</div>
								<div class="mr-2 flex items-center md:flex-col lg:flex-row">
										<p class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
												{{ count($deliveryOrder->logistic->activeSJGPLogistic) }} SJGP Aktif
										</p>
								</div>
								<div class="mr-2 flex items-center md:flex-col lg:flex-row">
										<p class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
												{{ count($deliveryOrder->logistic->activeSJPGLogistic) }} SJPG Aktif
										</p>
								</div>
								<div class="mr-2 flex items-center md:flex-col lg:flex-row">
										<p class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
												{{ count($deliveryOrder->logistic->activeSJPPLogistic) }} SJPP Aktif
										</p>
								</div>
						</div>
					</div>
			</div>
		</div>
		<div id="kendaraan-preview" class="flex flex-col rounded-xl p-3 shadow-md shadow-gray-100 mr-2  h-max">
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
		<div id="gudang-preview" class="flex flex-col rounded-xl p-3 shadow-md shadow-gray-100 mr-2  h-max">
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
							<p class="my-1 text-sm font-normal line-clamp-1 max-w-[20ch]">{{ $deliveryOrder->gudang->alamat }}</p>
					</div>
				</div>
		</div>
		<div id="perusahaan-preview" class="flex flex-col rounded-xl p-3 shadow-md shadow-gray-100 mr-2  h-max">
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
							<p class="my-1 text-sm font-normal line-clamp-1 max-w-[20ch]">{{ $deliveryOrder->perusahaan->alamat }}</p>
					</div>
				</div>
		</div>
		
	</div>
	@if ($deliveryOrder->status != 'SELESAI')
	<div class="relative rounded-md border border-green p-2 h-[70vh]">
		@if ($deliveryOrder->logistic)
			<a target="_blank" id="coordinate-address"
				class="absolute right-5 top-5 z-50 mb-2 self-start rounded-lg border border-gray-700 bg-green py-1 px-2 text-white"
				href="https://www.google.com/maps?saddr={{ $deliveryOrder->logistic->logistic->latitude }},{{ $deliveryOrder->logistic->logistic->longitude }}&daddr={{ $deliveryOrder->perusahaan->latitude }},{{ $deliveryOrder->perusahaan->longitude }}">
				Arahkan ke rute antara driver dengan perusahaan
			</a>
		@endif
		<div class="z-0 mb-2 h-full rounded-md" id="map"></div>
	</div>
	@endif
</div>
@endsection
