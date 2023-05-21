@extends('layouts.cetak')
@push('addon-style')
	<style>
		.text-xsm {
			font-size: 0.6rem/* 14px */ !important;
			line-height: 1rem/* 20px */ !important;
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

<div class="flex justify-end flex-col">
	<a href="{{route('delivery-order.downloadPDF', $deliveryOrder->id)}}" target="_blank" class="rounded-full py-1 px-2 text-white self-end bg-primary" >
		Download PDF
	</a>
</div>
<div class="flex flex-col text-sm">
	<div class="w-full mb-1 flex mt-5">
		<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo-burda.png'))) }}" alt="" class="h-10 w-auto my-5 self-start">
		<p class="float-right ml-2 text-xsm text-right">
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
	<table class="table-auto w-full my-5 border border-gray-600 ">
		<tbody>
			<tr>
				<td class="p-1 border border-gray-600 bg-green-200 w-56">Tanggal Pengambilan</td>
				<td class="p-1 border border-gray-600">{{\App\Helpers\Date::parseMilliseconds($deliveryOrder->created_at,'dddd, D MMM YYYY')}}</td>
			</tr>
			<tr>
				<td class="p-1 border border-gray-600 bg-green-200 w-56">No.</td>
				<td class="p-1 border border-gray-600">{{$deliveryOrder->kode_do}}</td>
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
		<p>Kepada Yth,<br>{{$deliveryOrder->perusahaan->nama}}<br>
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
@endsection
