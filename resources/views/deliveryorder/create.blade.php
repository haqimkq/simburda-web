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
												<option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
										@endforeach
								</select>
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
										<label for="kendaraan" class="mb-2 block text-sm font-normal text-gray-700">Driver</label>
										<select name="kendaraan_id" id="kendaraan" required
												class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
												<option value="" default selected>Pilih Kendaraan</option>
												@foreach ($kendaraans as $kendaraan)
														<option value="{{ $kendaraan->id }}">{{ $kendaraan->merk }} [{{ $kendaraan->plat_nomor }}]</option>
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
												<option value="{{ $perusahaan->id }}">{{ $perusahaan->nama }}</option>
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
										value="Delivery Order" required/>
										@error('perihal')
												@include('shared.errorText')
										@enderror
						</div>
						<div class="flex w-full flex-col">
								<label for="untuk_perhatian" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Untuk
										Perhatian</label>
								<input type="text" name="untuk_perhatian" placeholder="Ibu / Bapak"
										class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
										required/>
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
		{{-- <script>
				$('#searchKodePeminjaman').select2({
						width: null,
						placeholder: 'Pilih Kode Delivery Order',
						language: {
								inputTooShort: function() {
										return 'Masukkan 1 atau lebih karakter';
								},
								formatNoMatches: function() {
										return "Tidak ditemukan";
								},
								noResults: function() {
										return "Kode Delivery Order tidak ditemukan";
								},
								searching: function() {
										return "Sedang mencari...";
								}
						},
						ajax: {
								url: '/selectKodePeminjaman',
								dataType: 'json',
								delay: 100,
								data: function(data) {
										return {
												gudang_id: $('#proyekAsal').val(),
												q: data.term
										};
								},
								processResults: function(data) {
										return {
												results: $.map(data, function(item) {
														return {
																text: item.kode_peminjaman,
																id: item.id,
														}
												})
										};
								},
								cache: true
						}
				});
				$("#tipe").change(function() {
						var tipe = $("#tipe option:selected").val();
						var gudang = document.getElementById("gudang-field");
						var proyekLain = document.getElementById("proyek-asal-field");
						var kodePeminjaman = document.getElementById("kode-delivery-order-field");
						if (tipe == "GUDANG_PROYEK") {
								gudang.classList.remove("hidden");
								if (!proyekLain.classList.contains('hidden')) {
										gudang.classList.add("hidden");
								}
								if (!kodePeminjaman.classList.contains('hidden')) {
										gudang.classList.add("hidden");
								}

						} else if (tipe == "PROYEK_PROYEK") {
								proyekLain.classList.remove("hidden");
								if (!gudang.classList.contains('hidden')) {
										gudang.classList.add("hidden");
								}
						}
				});
				$("#gudang").change(function() {
						$.getJSON('http://127.0.0.1:8000/' + "delivery-order/tambah/barangByGudang/" + $("#gudang option:selected")
						.val(),
								function(data) {
										var temp = [];
										//CONVERT INTO ARRAY
										$.each(data, function(key, value) {
												temp.push({
														v: value,
														k: key
												});
										});
										//SORT THE ARRAY
										temp.sort(function(a, b) {
												if (a.v > b.v) {
														return 1
												}
												if (a.v < b.v) {
														return -1
												}
												return 0;
										});
										//APPEND INTO SELECT BOX
										$('#barang').empty();
										$.each(temp, function(key, obj) {
												$('#barang').append(`
												<div class="relative group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
                        <div class="flex p-2 align-items-center">
														<input id="default-checkbox" type="checkbox" name="barang[]" value="${obj.v.id}" class=" text-blue-600 bg-gray-100 rounded dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-3 m-3 checked:bg-green mr-2  border-green border w-5 h-5 focus:ring-green">
                                <div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
                                    style="background-image: url('{{ asset('') }}${obj.v.gambar}')"></div>
                            <div class="flex flex-col">
                                <span
                                    class="bg-green-200 text-green-600 border-green-600 mb-2 self-start rounded-full border px-1.5 text-xs">
                                    ${obj.v.kondisi}
                                </span>
                                <p class="mb-2 font-medium line-clamp-1">${obj.v.nama}</p>
                                <p class="mb-2 text-xs w-[15em] font-normal">${obj.v.detail}</p>
                            </div>
                        </div>
                    </div>
										`);
										});
								});
				});
				$("#proyekAsal").change(function() {
						var kodePeminjaman = document.getElementById("kode-delivery-order-field");
						kodePeminjaman.classList.remove("hidden");
				});
				$("#searchKodePeminjaman").change(function() {
						$.getJSON('http://127.0.0.1:8000/' + "delivery-order/tambah/barangByKodePeminjaman/" + $(
								"#searchKodePeminjaman option:selected").val(), function(data) {
								var temp = [];
								//CONVERT INTO ARRAY
								$.each(data, function(key, value) {
										temp.push({
												v: value,
												k: key
										});
								});
								//APPEND INTO SELECT BOX
								$('#barang').empty();
								$.each(temp, function(key, obj) {
										$('#barang').append(`
												<div class="relative group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
                        <div class="flex p-2 align-items-center">
														<input id="default-checkbox" type="checkbox" name="barang[]" value="${obj.v.id}" class=" text-blue-600 bg-gray-100 rounded dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-3 m-3 checked:bg-green mr-2  border-green border w-5 h-5 focus:ring-green">
                                <div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
                                    style="background-image: url('{{ asset('') }}${obj.v.gambar}')"></div>
                            <div class="flex flex-col">
                                <span
                                    class="bg-gray-200 text-gray-600 border-gray-600 mb-2 self-start rounded-full border px-1.5 text-xs">
                                    ${obj.v.kondisi}
                                </span>
                                <p class="mb-1 font-medium line-clamp-1">#${obj.v.nomor_seri} ${obj.v.nama}</p>
                                <p class="mb-2 font-small line-clamp-1">${obj.v.merk}</p>
                                <p class="mb-2 text-xs w-[15em] font-normal">${obj.v.detail}</p>
                            </div>
                        </div>
                    </div>
										`);
								});
						});
				});
		</script> --}}
@endsection

@push('prepend-script')
		@include('includes.jquery')
		@include('includes.moment-date-range')
		<script>
				$(function() {
						$('input[name="tgl_pengambilan"]').daterangepicker({
								singleDatePicker: true,
								showDropdowns: true,
						}, function(start, end, label) {
						});
				});
				$("#logistic").change(function() {
						$.getJSON("{{ env('APP_URL') }}/delivery-order/tambah/barangByKodePeminjaman/" + $(
								"#searchKodePeminjaman option:selected").val(), function(data) {
								var temp = [];
								//CONVERT INTO ARRAY
								$.each(data, function(key, value) {
										temp.push({
												v: value,
												k: key
										});
								});
								//APPEND INTO SELECT BOX
								$('#barang').empty();
								$.each(temp, function(key, obj) {
										$('#barang').append(`
												<div class="relative group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
                        <div class="flex p-2 align-items-center">
														<input id="default-checkbox" type="checkbox" name="barang[]" value="${obj.v.id}" class=" text-blue-600 bg-gray-100 rounded dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-3 m-3 checked:bg-green mr-2  border-green border w-5 h-5 focus:ring-green">
                                <div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
                                    style="background-image: url('{{ asset('') }}${obj.v.gambar}')"></div>
                            <div class="flex flex-col">
                                <span
                                    class="bg-gray-200 text-gray-600 border-gray-600 mb-2 self-start rounded-full border px-1.5 text-xs">
                                    ${obj.v.kondisi}
                                </span>
                                <p class="mb-1 font-medium line-clamp-1">#${obj.v.nomor_seri} ${obj.v.nama}</p>
                                <p class="mb-2 font-small line-clamp-1">${obj.v.merk}</p>
                                <p class="mb-2 text-xs w-[15em] font-normal">${obj.v.detail}</p>
                            </div>
                        </div>
                    </div>
										`);
								});
						});
				});
		</script>
@endpush
