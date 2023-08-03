@extends('layouts.detail')

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
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Seri</span>
      </div>
    </li>
  </ol>
</nav>
	<div class="flex flex-col mt-5">
		@if (session('createSeriBaruSuccess'))
			@section('alertMessage', session('createSeriBaruSuccess'))
			@include('shared.alerts.success')
		@endif
		<h1 class="mb-2 text-[1.5em] font-bold">
			Barang
		</h1>
		<div class="mb-2 flex">
			@if ($barang->gambar)
			<img class='h-[8em] max-w-[8em] rounded-3xl border-2 drop-shadow-sm md:h-[10em] md:max-w-[10em]'
				src="{{ asset($barang->gambar) }}" alt="">
					
			@endif
				@if ($barang->has('barangTidakHabisPakai'))
				{{-- <img
					class="ml-5 h-[8em] max-w-[8em] origin-top-left bg-white p-2 hover:z-10 md:h-[10em] md:max-w-[10em] md:hover:scale-125"
					src="{{ asset($barang->qrcode) }}" alt=""> --}}
				<img
					class="ml-5 h-[8em] max-w-[8em] origin-top-left bg-white p-2 hover:z-10 md:h-[10em] md:max-w-[10em] md:hover:scale-125"
					src="data:image/png;base64,{{base64_encode(QrCode::format('png')->size(1080)->generate($barang->barangTidakHabisPakai->id))}}" alt="">

				@endif
		</div>
		<div class="info-barang mb-2 ">
			<p class="text-lg font-semibold uppercase">#{{$barang->nomor_seri}} {{ $barang->nama }}</p>
			<p>
				{{$barang->detail}}
			</p>
		</div>
		<div class="grid h-auto w-[85vw] grid-cols-3 gap-2 md:w-[30em]">
			<div class="bg-green flex flex-col justify-center rounded-md p-2 text-center text-white">
				<p class="text-[1.5em] font-bold">{{ $jumlahBarang }}</p>
				<p class="text-md font-normal">Total Barang</p>
			</div>
			<div
				class="bg-green {{ $barangTersedia == 0 ? 'bg-gray-600' : '' }} flex flex-col justify-center rounded-md p-2 text-center text-white">
				<p class="text-[1.5em] font-bold">{{ $barangTersedia }}</p>
				<p class="text-md font-normal">Barang tersedia</p>
			</div>
			<div
				class="{{ $jumlahBarang - $barangTersedia == 0 ? 'bg-gray-600' : ' bg-orange-500' }} flex flex-col justify-center rounded-md p-2 text-center text-white">
				<p class="text-[1.5em] font-bold">{{ $jumlahBarang - $barangTersedia }}</p>
				<p class="text-md font-normal">Barang dipinjam</p>
			</div>
		</div>
	</div>
	<div class="flex w-full items-center h-full"> 
		<h1 class="mt-5 mb-2 text-[1.5em] font-medium w-full">
			Seri Barang {{$barang->nama}}
		</h1>
		<form action="{{route('barang.tambahSeriBaru', $barang->nama)}}" method="POST">
			@csrf
			<button type="submit" class="bg-primary py-2 px-2 w-[12em] h-auto self-center text-white rounded-md">Tambah Seri Baru</button>

		</form>
	</div>
		{{-- <div class="grid gap-2 md:grid-cols-2"> --}}
			<div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
				@foreach ($allBarang as $barang)
				  @php
						$jumlahBarang = \App\Models\Barang::where('nama', $barang->nama)->count();
						$barangTersedia = \App\Models\Barang::where('nama',$barang->nama)->where('tersedia',1)->count();
						$barangDipinjam = $jumlahBarang - $barangTersedia ;
					@endphp
					<div class="relative group flex flex-col rounded-xl {{(!$barang->tersedia) ? 'border border-orange-500' : 'border border-green'}}">
						<a href="{{ route('barang.show', $barang->id) }}" class="flex p-2">
							<div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
								style="background-image: url('{{ asset($barang->gambar) }}')"></div>
							<div class="flex flex-col">
								<span
									class="{{ $barang->jenis == 'tidak habis pakai' ? 'bg-green-200 text-green-600 border-green-600' : 'text-primary border-primary bg-primary-30' }} mb-2 self-start rounded-full border px-1.5 text-xs">
									{{ ucfirst($barang->jenis) }}
								</span>
								<p class="mb-2 font-medium line-clamp-1">#{{$barang->nomor_seri}} {{ $barang->nama }}</p>
								<p class="mb-2 text-xs font-normal">{{ $barang->created_at }}</p>
								
								<div class="flex items-center md:flex-col lg:flex-row">
									@if(!$barang->tersedia)
									  <p class="mb-2 self-start rounded-full border border-orange-500 px-2 py-1 text-xs text-black">{{$barangDipinjam}} {{$barang->satuan}}</p>
									@endif
								</div>
							</div>
						</a>
						<div class="relative flex justify-center items-center w-full h-full">
							<div class="w-full bg-white flex justify-end items-end px-2 rounded-b-xl pb-2">
								<form action="{{ route('barang.destroy', $barang->id) }}" method="POST">
									@csrf
									<button type="submit"
										class="show_confirm  bg-white rounded-md border border-red-500 py-1 px-3 text-sm text-red-500"
										data-name="{{ $barang->nama }}">Hapus</button>
								</form>
								<a href="{{ route('barang.edit', $barang->id) }}"
									class="bg-primary ml-2 rounded-md py-1 px-3 text-sm text-white self-start">Edit</a>
							</div>
						</div>
				</div>
			@endforeach
		{{-- </div> --}}
@endsection
