@extends('layouts.create')
@push('addon-style')
		@include('includes.jquery')
		<style>
				option[default] {
						display: none;
				}
		</style>
		{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@section('content')
		<nav class="mt-5 flex" aria-label="Breadcrumb">
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
										<span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">Tambah</span>
								</div>
						</li>
				</ol>
		</nav>
		<h1 class="my-6 w-full text-center text-lg font-bold uppercase">Tambah Delivery Order</h1>
		<form method="POST" action="{{ route('delivery-order.store') }}">
				@csrf
				<div class="mb-6 grid w-[80vw] gap-6 md:grid-cols-2">
						<div class="flex w-full flex-col">
								<label for="gudang" class="mb-2 block text-sm font-normal text-gray-700">Gudang</label>
								
								<select name="gudang_id" id="gudang" required
										class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
										<option value="" default selected>Pilih Gudang</option>
										@foreach ($gudangs as $gudang)
												<option value="{{ $gudang->id }}" 
													gudang-gambar="{{ asset($gudang->gambar) }}" 
													gudang-alamat="{{ $gudang->alamat }}" 
													gudang-nama="{{ $gudang->nama }}"
													gudang-provinsi="{{ App\Helpers\Utils::underscoreToNormal($gudang->provinsi) }}"
													>{{ $gudang->nama }}</option>
										@endforeach
								</select>
								<div id="gudang-preview" class="my-2 flex flex-col rounded-xl p-2 shadow-md shadow-gray-100 hidden">
										{{-- <div class="mr-2 h-[15em] w-full rounded-xl bg-cover md:w-full lg:h-[10em] lg:w-full"
												style="background-image: url('{{ asset($gudang->gambar) }}')"></div>
										<div class="flex flex-col">
												<span class="my-2 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600">
														{{ App\Helpers\Utils::underscoreToNormal($gudang->provinsi) }}
												</span>
												<p class="mb-2 font-medium line-clamp-1 md:line-clamp-2 xl:line-clamp-3">{{ $gudang->nama }}</p>
												<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-1 md:line-clamp-2 xl:line-clamp-3">
														{{ ucfirst($gudang->alamat) }}</p>
										</div> --}}
								</div>
								@error('gudang_id')
										@include('shared.errorText')
								@enderror
						</div>
						@can('ADMIN')
								<div class="flex w-full flex-col">
										<label for="logistic" class="mb-2 block text-sm font-normal text-gray-700">Driver</label>
										<select name="logistic_id" id="logistic" required
												class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
												<option value="" default selected>Pilih Driver</option>
												@foreach ($logistics as $logistic)
														<option value="{{ $logistic->id }}">{{ $logistic->nama }}
																[{{ count($logistic->activeDeliveryOrderLogistic) }} DO]
																[{{ count($logistic->activeSJGPLogistic) }} SJGP]
																[{{ count($logistic->activeSJPPLogistic) }} SJPP]
																[{{ count($logistic->activeSJPGLogistic) }} SJPG]
														</option>
												@endforeach
										</select>
										@error('logistic_id')
												@include('shared.errorText')
										@enderror
								</div>
								<div class="flex w-full flex-col">
										<label for="kendaraan" class="mb-2 block text-sm font-normal text-gray-700">Kendaraan</label>
										<select name="kendaraan_id" id="kendaraan" required
												class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
												<option value="" default selected>Pilih Kendaraan</option>
												@foreach ($kendaraans as $kendaraan)
														<option value="{{ $kendaraan->id }}"
															kendaraan-gambar="{{ asset($kendaraan->gambar) }}"
															kendaraan-gudang="{{ $kendaraan->gudang->nama }}"
															kendaraan-nama="{{ $kendaraan->nama }}"
															kendaraan-provinsi="{{ App\Helpers\Utils::underscoreToNormal($kendaraan->provinsi) }}"
															>{{ $kendaraan->merk }} [{{ $kendaraan->plat_nomor }}]</option>
												@endforeach
										</select>
										@error('kendaraan_id')
												@include('shared.errorText')
										@enderror
								</div>
						@endcan

						<div class="flex w-full flex-col">
								<label for="perusahaan" class="mb-2 block text-sm font-normal text-gray-700">Perusahaan</label>
								<select name="perusahaan_id" id="perusaaan" required
										class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
										<option value="" default selected>Pilih Perusahaan</option>
										@foreach ($perusahaans as $perusahaan)
												<option value="{{ $perusahaan->id }}"
													perusahaan-gambar="{{ asset($perusahaan->gambar) }}" 
													perusahaan-alamat="{{ $perusahaan->alamat }}" 
													perusahaan-nama="{{ $perusahaan->nama }}"
													perusahaan-provinsi="{{ App\Helpers\Utils::underscoreToNormal($perusahaan->provinsi) }}"
												>{{ $perusahaan->nama }}</option>
										@endforeach
								</select>
								@error('perusahaan_id')
										@include('shared.errorText')
								@enderror
						</div>
						<div class="flex w-full flex-col">
								<label for="tgl_pengambilan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
										Pengambilan</label>
								<input type="text" name="tgl_pengambilan"
										class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green" />
								@error('tgl_pengambilan')
										@include('shared.errorText')
								@enderror
						</div>
						<div class="flex w-full flex-col">
								<label for="perihal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Perihal</label>
								<input type="text" name="perihal"
										class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
										value="Delivery Order" required />
								@error('perihal')
										@include('shared.errorText')
								@enderror
						</div>
						<div class="flex w-full flex-col">
								<label for="untuk_perhatian" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Untuk
										Perhatian</label>
								<input type="text" name="untuk_perhatian" placeholder="Ibu / Bapak"
										class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
										required />
								@error('untuk_perhatian')
										@include('shared.errorText')
								@enderror
						</div>
				</div>
				<div class="flex justify-center">
						<button type="submit"
								class="mt-5 w-full content-center self-center rounded-lg bg-green px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-green dark:hover:bg-green dark:focus:ring-green sm:w-auto">Submit</button>
				</div>
		</form>
@endsection

@push('prepend-script')
		@include('includes.jquery')
		@include('includes.moment-date-range')
		<script>
				$(function() {
						$('input[name="tgl_pengambilan"]').daterangepicker({
								singleDatePicker: true,
								showDropdowns: true,
						}, function(start, end, label) {});
				});
				$("#gudang").change(function(){
					$("#gudang-preview").removeClass("hidden")
					$("#gudang-preview").empty()
					var alamatGudang = $("#gudang option:selected").attr("gudang-alamat")
					var provinsiGudang = $("#gudang option:selected").attr("gudang-provinsi")
					var namaGudang = $("#gudang option:selected").attr("gudang-nama")
					var gambarGudang = $("#gudang option:selected").attr("gudang-gambar")
					$("#gudang-preview").append(`
						<div class="mr-2 h-[18em] w-[20em] rounded-xl bg-cover"
								style="background-image: url('${gambarGudang}')"></div>
						<div class="flex flex-col">
								<span class="my-2 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600">
										${provinsiGudang}
								</span>
								<p class="mb-2 font-medium line-clamp-1 md:line-clamp-2 xl:line-clamp-3">${namaGudang}</p>
								<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-1 md:line-clamp-2 xl:line-clamp-3">
										${alamatGudang}</p>
						</div>
					`)
				});
				$("#perusahaan").change(function(){
					$("#perusahaan-preview").removeClass("hidden")
					$("#perusahaan-preview").empty()
					var alamatperusahaan = $("#perusahaan option:selected").attr("perusahaan-alamat")
					var provinsiperusahaan = $("#perusahaan option:selected").attr("perusahaan-provinsi")
					var namaperusahaan = $("#perusahaan option:selected").attr("perusahaan-nama")
					var gambarperusahaan = $("#perusahaan option:selected").attr("perusahaan-gambar")
					$("#perusahaan-preview").append(`
						<div class="mr-2 h-[18em] w-[20em] rounded-xl bg-cover"
								style="background-image: url('${gambarperusahaan}')"></div>
						<div class="flex flex-col">
								<span class="my-2 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600">
										${provinsiperusahaan}
								</span>
								<p class="mb-2 font-medium line-clamp-1 md:line-clamp-2 xl:line-clamp-3">${namaperusahaan}</p>
								<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-1 md:line-clamp-2 xl:line-clamp-3">
										${alamatperusahaan}</p>
						</div>
					`)
				});
				$("#logistic").change(function() {
						$.getJSON("{{ env('APP_URL') }}/kendaraan/logistic/" + $(
								"#logistic option:selected").val(), function(data) {
								var temp = [];
								//CONVERT INTO ARRAY
								$.each(data, function(key, value) {
										temp.push({
												v: value,
												k: key
										});
								});
								if (temp.length > 0) {
										$("#kendaraan option:selected").html(`${temp[2].v} [${temp[3].v}]`);
										$("#kendaraan option:selected").val(temp[0].v);
										$("#kendaraan").prop("default", false);
										$("#kendaraan").prop("disabled", true);
								} else {
										$("#kendaraan option:selected").html(`Pilih Kendaraan`);
										$("#kendaraan option:selected").val('');
										$("#kendaraan").prop("disabled", false);
										$("#kendaraan").prop("default", true);
								}
						});
				});
		</script>
@endpush
