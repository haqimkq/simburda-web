<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="description"
      content=""
    />
    <meta
      name="keywords"
      content=""
    />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" href="/images/favico.png" />

    <style>
      body{
        font-family: 'Poppins', sans-serif;
				overflow-x: hidden;
      }
      .p-5 {
        padding: 1.25rem/* 20px */ !important;
      }
      @media (min-width: 768px) {
        .md\:p-8 {
          padding: 2rem/* 32px */ !important;
        }
      }
      .w-full {
        width: 100% !important;
      }
      .items-center {
        align-items: center !important;
      }
      .justify-center {
        justify-content: center !important;
      }
		@media print {
			.print\:hidden {
				display: none !important;
			}
			.print\:text-black {
				color: rgb(0 0 0) !important;
			}
			.print\:p-0 {
				padding: 0px !important;
			}
		}
		.inline-flex {
			display: inline-flex !important;
		}
		.space-x-1 > :not([hidden]) ~ :not([hidden]) {
			margin-right: calc(0.25rem * var(--tw-space-x-reverse)) !important;
			margin-left: calc(0.25rem * calc(1 - var(--tw-space-x-reverse))) !important;
		}
		@media (min-width: 768px) {
			.md\:space-x-3 > :not([hidden]) ~ :not([hidden]) {
				margin-right: calc(0.75rem * var(--tw-space-x-reverse)) !important;
				margin-left: calc(0.75rem * calc(1 - var(--tw-space-x-reverse))) !important;
			}
		}
		.text-sm {
			font-size: 0.875rem/* 14px */ !important;
			line-height: 1.25rem/* 20px */ !important;
		}
		.text-xsm {
			font-size: 0.6rem/* 14px */ !important;
			line-height: 1rem/* 20px */ !important;
		}
		.text-white {
			color: rgb(255 255 255) !important;
		}
		.self-center {
			align-self: center !important;
		}
		.font-medium {
			font-weight: 500 !important;
		}
		.text-gray-700 {
			color: rgb(55 65 81) !important;
		}
		.text-gray-400 {
			color: rgb(156 163 175) !important;
		}
		.text-base {
			font-size: 1rem/* 16px */ !important;
			line-height: 1.5rem/* 24px */ !important;
		}
		.hover\:text-gray-900:hover {
			color: rgb(17 24 39) !important;
		}
		.h-6 {
			height: 1.5rem/* 24px */ !important;
		}
		.w-6 {
			width: 1.5rem/* 24px */ !important;
		}
		.ml-1 {
			margin-left: 0.25rem/* 4px */ !important;
		}
		.text-gray-500 {
			color: rgb(107 114 128) !important;
		}
		@media (min-width: 768px) {
			.md\:ml-2 {
				margin-left: 0.5rem/* 8px */ !important;
			}
		}
		.float-right {
  float: right !important;
}
		.text-center {
			text-align: center !important;
		}
		.max-w-xl {
			max-width: 36rem/* 576px */ !important;
		}
		.self-start {
			align-self: flex-start !important;
		}
		.h-10 {
			height: 2.5rem/* 40px */ !important;
		}
		.h-3 {
			height: 0.75rem/* 12px */ !important;
		}
		.my-5 {
			margin-top: 1.25rem/* 20px */ !important;
			margin-bottom: 1.25rem/* 20px */ !important;
		}
		.justify-end {
			justify-content: flex-end !important;
		}
		.mb-3 {
			margin-bottom: 0.75rem/* 12px */ !important;
		}
		.text-white {
			color: rgb(255 255 255) !important;
		}
		.bg-primary {
			background-color: rgb(31 72 68) !important;
		}
		.text-red-600 {
			color: rgb(224 36 36) !important;
		}
		.p-1 {
			padding: 0.25rem/* 4px */ !important;
		}
		.border {
			border:1px solid rgb(75 85 99);
		}
		.mb-5 {
			margin-bottom: 1.25rem/* 20px */ !important;
		}
		.border-gray-600 {
			border-color: rgb(75 85 99) !important;
		}
		.table-auto {
			table-layout: auto !important;
			border-collapse: collapse;
		}
		.bg-green-200 {
			background-color: rgb(188 240 218) !important;
		}
		.w-56 {
			width: 14rem/* 224px */ !important;
		}
		.bg-green-200 {
			background-color: rgb(188 240 218) !important;
		}
		.border-gray-600 {
			border-color: rgb(75 85 99) !important;
		}
		.flex {
			display: flex !important;
		}
		.w-40 {
			width: 10rem/* 160px */ !important;
		}
		.mb-1 {
			margin-bottom: 0.25rem/* 4px */ !important;
		}
		.flex-col {
			flex-direction: column !important;
		}
		.items-end {
			align-items: flex-end !important;
		}
		p,h1	{
			margin:0;
		}
		.bg-center {
			background-position: center !important;
		}
		.bg-no-repeat {
			background-repeat: no-repeat !important;
		}
		.bg-contain {
			background-size: contain !important;
		}
		.my-3 {
			margin-top: 0.75rem/* 12px */ !important;
			margin-bottom: 0.75rem/* 12px */ !important;
		}
		.ml-2 {
			margin-left: 0.5rem/* 8px */ !important;
		}
		.text-right {
			text-align: right !important;
		}
		.font-bold {
			font-weight: 700 !important;
		}
		.h-full {
			height: 100% !important;
		}
		.h-auto {
			height: auto !important;
		}
		/* .overflow-hidden{
			overflow: hidden;
		} */
		.h-24 {
			height: 6rem/* 96px */ !important;
		}
	</style>
  </head>
  <body class="w-full">
		<div class="text-sm">
			<div class="w-full overflow-hidden mb-1" style="height: 6.5em">
				<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo-burda.png'))) }}" alt="" class="h-10 w-auto">
				<p class="ml-2 text-xsm text-right float-right"><span class="text-base font-bold">PT.BURDA CONTRACO </span>
					<br>Wisma NH Jl. Raya Pasar Minggu Blok B-C No. 2, RT 002 RW 002, Pancoran – Jakarta Selatan
					<br>JL. Pengadegan Selatan II No. 1 Pancoran – Jakarta Selatan
					<br>Telp. (62-21) 7988968 – 70  Fax. (62-21) 79195987
					<br>website: www.burdacontraco.co.id, email: info@burdacontraco.co.id / admin@burdacontraco.co.id
				</p>
			</div>
			<div class="bg-primary w-full h-auto mb-1 text-center">
				<p class="text-white text-xsm w-full">
					Civil Works | Steel Construction | Infrastructure
				</p>
			</div>
			<div class="bg-primary w-full">
				<h1 class="p-1 text-base w-auto print:p-0 text-white text-center print:text-black">FORM DELIVERY ORDER</h1>
			</div>
			<table class="table-auto w-full my-5 border border-gray-600 ">
				<tbody>
					<tr>
						<td class="p-1 border border-gray-600 bg-green-200 w-56">Tanggal Pengambilan</td>
						<td class="p-1 border border-gray-600">{{$deliveryOrder->created_at}}</td>
					</tr>
					<tr>
						<td class="p-1 border border-gray-600 bg-green-200 w-56">No.</td>
						<td class="p-1 border border-gray-600">{{$deliveryOrder->kode_delivery}}</td>
					</tr>
					<tr>
						<td class="p-1 border border-gray-600 bg-green-200 w-56">Perihal</td>
						<td class="p-1 border border-gray-600">{{$deliveryOrder->perihal}}</td>
					</tr>
					<tr>
						<td class="p-1 border border-gray-600 bg-green-200 w-56">UP</td>
						<td class="p-1 border border-gray-600">{{$deliveryOrder->untuk_perhatian}}</td>
					</tr>
				</tbody>
			</table>
			<div class="message mb-5 w-full">
				<p>Kepada Yth,<br>{{$deliveryOrder->untuk_perusahaan}}<br>
					Melalui memo ini dari PT. Burda Contraco menyampaikan untuk memohon<br>
					di berikan kepada pembawa memo material berupa:</p>
			</div>
			<table class="table-auto w-full mb-5 text-center ">
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
						<td class="p-1 border border-gray-600">{{$po->kode_preorder}}</td>
						<td class="p-1 border border-gray-600">{{$po->nama_material}}</td>
						<td class="p-1 border border-gray-600">{{$po->ukuran}}</td>
						<td class="p-1 border border-gray-600">{{$po->jumlah}}</td>
						<td class="p-1 border border-gray-600">{{$po->satuan}}</td>
						<td class="p-1 border border-gray-600">{{$po->keterangan}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<table class="table-auto mb-5 w-full">
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
						@if ($deliveryOrder->user)
							<td>: {{$deliveryOrder->user->nama}}</td>
						@else
							<td class="text-red-600">: Admin Gudang belum memilih supir</td>
						@endif
					</tr>
				</tbody>
			</table>
			<p class="mb-5">Atas perhatiannya kami ucapkan terimakasih <br>Mengetahui,</p>
			<div class="w-full ">
					<div class="float-right">
						<p class="text-center mb-1">Hormat Kami, <br><span class="font-bold">PT. BURDA CONTRACO</span></p>
						@if ($ttdPath)
						<div class="bg-center bg-no-repeat bg-contain" style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
							<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($ttdPath)))}}" alt="" class=" self-center w-40 my-3">
						</div>
						@else
						<div class="bg-center bg-no-repeat bg-contain w-40 h-24" style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
						</div>
						@endif
						<p class="text-center">{{$deliveryOrder->purchasing->nama}}<br><span>Purchasing</span><br>{{$deliveryOrder->purchasing->no_hp}}</p>
					</div>
			</div>
		</div>
  </body>
</html>
