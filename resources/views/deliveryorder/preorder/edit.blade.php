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
						<li>
								<div class="flex items-center">
										<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd"
														d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
														clip-rule="evenodd"></path>
										</svg>
										<a href="{{ route('delivery-order.show', $deliveryOrder->id) }}"
												class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white md:ml-2">
												Detail</a>
								</div>
						</li>
						<li>
								<div class="flex items-center">
										<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd"
														d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
														clip-rule="evenodd"></path>
										</svg>
										<a href="{{ route('delivery-order.updateStepOne', $deliveryOrder->id) }}"
												class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white md:ml-2">
												Ubah DO</a>
								</div>
						</li>
						<li aria-current="page">
								<div class="flex items-center">
										<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd"
														d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
														clip-rule="evenodd"></path>
										</svg>
										<span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">Ubah PO</span>
								</div>
						</li>
				</ol>
		</nav>
		<h1 class="my-6 w-full text-center text-lg font-bold uppercase">Ubah Pre Order</h1>
		<div class="mb-6 grid w-[80vw] grid-cols-2 gap-6 md:grid-cols-4">
				<p class="mb-2 text-sm font-medium"><span class="text-xs text-gray-500">Tanggal Pengambilan</span>
						<br>{{ App\Helpers\Date::parseMilliseconds($deliveryOrder->tgl_pengambilan) }}</p>
				<p class="mb-2 text-sm font-medium"><span class="text-xs text-gray-500">Kode Delivery Order</span>
						<br>{{ $deliveryOrder->kode_do }}</p>
				<p class="mb-2 text-sm font-medium"><span class="text-xs text-gray-500">Untuk Perhatian</span>
						<br>{{ $deliveryOrder->untuk_perhatian }}</p>
				<p class="mb-2 text-sm font-medium"><span class="text-xs text-gray-500">Perihal</span>
						<br>{{ $deliveryOrder->perihal }}</p>
				<div id="logistic-preview" class="my-2 flex flex-col items-center rounded-xl p-2 shadow-md shadow-gray-100">
						<p class="text-md mb-3 text-center font-bold">Driver</p>
						<div class="mb-2 h-[6em] w-[6em] rounded-full bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
								style="background-image: url('{{ asset($deliveryOrder->logistic->foto) }}')"></div>
						<div class="flex w-full flex-col">
								<div class="flex flex-col p-2">
										<p class="mb-1 font-medium">{{ $deliveryOrder->logistic->nama }}</p>
										<p class="mb-2 text-sm font-normal">{{ $deliveryOrder->logistic->no_hp }}</p>
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
				<div id="kendaraan-preview" class="my-2 flex flex-col items-center rounded-xl p-2 shadow-md shadow-gray-100">
						<p class="text-md mb-3 text-center font-bold">Kendaraan</p>
						<div class="flex flex-col p-2">
								<div class="m-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
										style="background-image: url('{{ asset($deliveryOrder->kendaraan->gambar) }}')"></div>
								<div class="flex w-full flex-col">
										<span
												class="mb-1 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600 md:mb-0 md:mr-1">
												{{ $deliveryOrder->kendaraan->jenis }}
										</span>
										<div class="mt-1 flex flex-col md:flex-row md:items-center">
												<p class="line-clamp-2 font-medium">{{ $deliveryOrder->kendaraan->merk }}</p>
										</div>
										<p class="my-1 line-clamp-1 text-sm font-normal uppercase">{{ $deliveryOrder->kendaraan->plat_nomor }}</p>
										<div class="flex items-center overflow-x-auto">
												<img src="/images/ic_gudang.png" alt="" class="mr-1 h-[1.1em] w-auto">
												<p class="line-clamp-2 text-sm font-normal">{{ $deliveryOrder->gudang->nama }}</p>
										</div>
								</div>
						</div>
				</div>
				<div id="gudang-preview"
						class="my-2 flex flex-col items-center justify-center rounded-xl p-2 shadow-md shadow-gray-100">
						<p class="text-md mb-3 text-center font-bold">Gudang</p>
						<div class="mr-2 h-[6em] w-[8em] rounded-xl bg-cover"
								style="background-image: url('{{ asset($deliveryOrder->gudang->gambar) }}')"></div>
						<div class="flex flex-col">
								<span class="my-2 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600">
										{{ $deliveryOrder->gudang->provinsi }} </span>
								<p class="mb-2 line-clamp-1 font-medium md:line-clamp-2 xl:line-clamp-3">{{ $deliveryOrder->gudang->nama }}</p>
								<p class="mb-2 line-clamp-1 text-sm font-normal text-gray-700 md:line-clamp-2 xl:line-clamp-3">
										{{ $deliveryOrder->gudang->alamat }}</p>
						</div>
				</div>
				<div id="perusahaan-preview"
						class="my-2 flex flex-col items-center justify-center rounded-xl p-2 shadow-md shadow-gray-100">
						<p class="text-md mb-3 text-center font-bold">Perusahaan</p>
						<div class="mr-2 h-[6em] w-[8em] rounded-xl bg-cover"
								style="background-image: url('{{ asset($deliveryOrder->perusahaan->gambar) }}')"></div>
						<div class="flex flex-col">
								<span class="my-2 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600">
										{{ $deliveryOrder->perusahaan->provinsi }} </span>
								<p class="mb-2 line-clamp-1 font-medium md:line-clamp-2 xl:line-clamp-3">{{ $deliveryOrder->perusahaan->nama }}
								</p>
								<p class="mb-2 line-clamp-1 text-sm font-normal text-gray-700 md:line-clamp-2 xl:line-clamp-3">
										{{ $deliveryOrder->perusahaan->alamat }}</p>
						</div>
				</div>
		</div>
		<form method="POST" action="{{ route('delivery-order.storeUpdateStepTwo', $deliveryOrder->id) }}"
				class="update-po-form">
				@csrf
				<div id="pre-order-container">
						<div class="flex self-end">
								<button type="button" id="btn-add-item"
										class="mb-2 w-auto self-end rounded-lg border-2 border-blue-600 px-4 py-2 text-center text-lg font-medium text-blue-600 hover:border-blue-500 hover:text-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto">
										+ Pre Order
								</button>
						</div>
						@foreach ($deliveryOrder->preOrder as $key => $po)
								<div class="pre-order-item mb-6 grid w-[80vw] gap-6 rounded-xl p-3 shadow-md shadow-gray-100 md:grid-cols-6">
										<input type="hidden" name="preorder[{{ $key }}][id]" value="{{ $po->id }}" />
										<input type="hidden" name="preorder[{{ $key }}][delivery_order_id]"
												value="{{ $po->delivery_order_id }}" />
										<div class="flex w-full flex-col">
												<label for="nama_material" class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Nama
														material</label>
												<input type="text" name="preorder[{{ $key }}][nama_material]" placeholder="Nama material"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
														required value="{{ $po->nama_material }}" />
												@error('nama_material')
														@include('shared.errorText')
												@enderror
										</div>
										<div class="flex w-full flex-col">
												<label for="ukuran"
														class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Ukuran</label>
												<input type="text" name="preorder[{{ $key }}][ukuran]" placeholder="Ukuran"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
														value="{{ $po->ukuran }}" required />
												@error('ukuran')
														@include('shared.errorText')
												@enderror
										</div>
										<div class="flex w-full flex-col">
												<label for="jumlah"
														class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Jumlah</label>
												<input type="number" min="1" name="preorder[{{ $key }}][jumlah]" placeholder="Jumlah"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
														value="{{ $po->jumlah }}" required />
												@error('jumlah')
														@include('shared.errorText')
												@enderror
										</div>
										<div class="flex w-full flex-col">
												<label for="satuan"
														class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Satuan</label>
												<input type="text" name="preorder[{{ $key }}][satuan]" placeholder="Satuan"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
														value="{{ $po->satuan }}" required />
												@error('satuan')
														@include('shared.errorText')
												@enderror
										</div>
										<div class="flex w-full flex-col">
												<label for="keterangan"
														class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Keterangan</label>
												<input type="text" name="preorder[{{ $key }}][keterangan]" placeholder="Keterangan"
														value="{{ $po->keterangan }}"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green" />
												@error('keterangan')
														@include('shared.errorText')
												@enderror
										</div>
										<div class="flex self-end">
												@if($key==0) <form></form> @endif
												<form action="{{ route('delivery-order.destroyPreOrder', $po->id) }}" method="POST"
														class="destroy-po-form">
														@csrf
														<button type="button" data-name="{{ $po->nama_material }} ({{ $po->ukuran }}) {{ $po->jumlah }} {{ $po->satuan }}"
																class="show_delete_confirm w-auto self-end rounded-lg border-2 border-red-600 px-3 py-2.5 text-center text-sm font-medium text-red-600 hover:border-red-500 hover:text-red-500 focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto">
																<img src="/images/ic_trash.png" class="w-[1.5em]">
														</button>
												</form>
										</div>
								</div>
						@endforeach
				</div>
				<div class="flex justify-center">
						<a id="btn-back" href="{{ route('delivery-order.updateStepOne', $deliveryOrder->id) }}"
								class="mr-5 mt-5 w-full content-center self-center rounded-lg bg-gray-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gray-500 focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto">Kembali</a>
						<button type="button" id="btn-submit"
								class="mt-5 w-full content-center self-center rounded-lg bg-green px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto">Submit</button>
				</div>
		</form>
@endsection

@push('prepend-script')
		@include('includes.sweetalert')
		@include('includes.jquery')
		<script>
				var i = $('.pre-order-item').length - 1;
				$("#btn-add-item").click(function() {
						++i;
						$("#pre-order-container").append(`
								<div class="pre-order-item mb-6 grid w-[80vw] gap-6 md:grid-cols-6 rounded-xl p-3 shadow-md shadow-gray-100">
												<input type="hidden" name="preorder[${i}][delivery_order_id]"/>
												<div class="flex w-full flex-col">
																<label for="nama_material" class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Nama
																				material</label>
																<input type="text" name="preorder[${i}][nama_material]" placeholder="Nama material"
																				class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
																				required />
												</div>
												<div class="flex w-full flex-col">
																<label for="ukuran"
																				class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Ukuran</label>
																<input type="text" name="preorder[${i}][ukuran]" placeholder="Ukuran"
																				class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
																				required />
												</div>
												<div class="flex w-full flex-col">
																<label for="jumlah"
																				class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Jumlah</label>
																<input type="number" min="1" name="preorder[${i}][jumlah]" placeholder="Jumlah"
																				class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
																				required />
												</div>
												<div class="flex w-full flex-col">
																<label for="satuan" class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Satuan</label>
																<input type="text" name="preorder[${i}][satuan]" placeholder="Satuan"
																				class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green"
																				required />
												</div>
												<div class="flex w-full flex-col">
																<label for="keterangan"
																				class="mb-2 block text-[0.7em] font-medium text-gray-900 dark:text-gray-300">Keterangan</label>
																<input type="text" name="preorder[${i}][keterangan]" placeholder="Keterangan"
																				class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-[0.7em] text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green" />
												</div>
												<div class="flex self-end">
																<button type="button" 
																class="remove-input-field self-end w-auto rounded-lg border-2 border-red-600 px-3 py-2.5 text-center text-sm font-medium text-red-600 hover:text-red-500 hover:border-red-500 focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto">
																		<img src="/images/ic_trash.png" alt="" class="w-[1.5em]">
																</button>
												</div>
								</div>
						`)
				});
				$(document).on('click', '.remove-input-field', function() {
						$(this).parents('.pre-order-item').remove();
				});
				// $(document).on('click', '.remove-pre-order', function () {
				// 				$(this).parents('.destroy-po-form').submit();
				// });
				$(document).on('click', '#btn-submit', function() {
						$('.update-po-form').submit();
				});
				$('.show_delete_confirm').click(function(event) {
						var form = $(this).parents('.destroy-po-form');
						var nama = $(this).data("name");
						event.preventDefault();
						Swal.fire({
								title: "Apakah kamu yakin?",
								html: `Pre Order yang dihapus tidak dapat dikembalikan, ingin menghapus pre-order <b>${nama}</b>`,
								icon: 'warning',
								showCancelButton: true,
								confirmButtonText: 'Ya, Hapus Pre Order',
								cancelButtonText: 'Batalkan',
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33'
						}).then((result) => {
								if (result.isConfirmed) {
										form.submit();
								}
						})
				});
		</script>
@endpush
