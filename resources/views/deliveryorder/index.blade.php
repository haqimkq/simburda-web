@extends('layouts.app')
@push('prepend-style')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
	
@endpush
@section('content')
		<div class="w-full md:ml-[16em]">
				<div class="w-full pl-4">
						@if (session('createProyekSuccess'))
								@section('alertMessage', session('createProyekSuccess'))
								@include('shared.alerts.success')
						@endif
						@if (session('deleteProyekSuccess'))
								@section('alertMessage', session('deleteProyekSuccess'))
								@include('shared.alerts.success')
						@endif
						@section('headerName', 'Delivery Order')
				@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
				@if ($authUser->foto)
						@section('foto', asset($authUser->foto))
				@endif
				@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
				@include('includes.header')
				<div class="my-5 flex w-full items-center justify-items-center">
						<div class="flex w-full">
								@section('last-search')
										<a href="{{ route('delivery-order.create') }}" class="button-custom !h-auto !w-auto px-5">
												+ Tambah DO
										</a>
								@endsection
								@section('placeholderSearch', 'Cari Kode Delivery Order') @section('action', '/delivery-order')
								@section('middle-search')
										@if (request('page'))
												<input type="hidden" name="page" id="" value="{{ request('page') }}">
										@endif
										<div class="flex w-full flex-col">
												<label for="orderBy" class="mb-1 block text-sm font-normal text-gray-700">Urutkan Berdasarkan</label>
												<select name="orderBy" id="orderBy" onchange="this.form.submit()"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
														<option value="terbaru" @if (request('orderBy') == 'terbaru') selected @endif>Terbaru</option>
														<option value="terlama" @if (request('orderBy') == 'terlama') selected @endif>Terlama</option>
												</select>
										</div>
										<div class="flex w-full flex-col">
												<label for="filterStatus" class="mb-1 block text-sm font-normal text-gray-700">Filter Status</label>
												<select name="filter" id="filterStatus" onchange="this.form.submit()"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
														<option value="semua status" @if (request('filter') == 'semua status') selected @endif>Semua Status</option>
														<option value='menunggu konfirmasi admin gudang' @if (request('filter') == 'menunggu konfirmasi admin gudang') selected @endif>Menunggu
																Konfirmasi Admin Gudang</option>
														<option value='driver dalam perjalanan' @if (request('filter') == 'driver dalam perjalanan') selected @endif>Dalam Perjalanan
														</option>
														<option value='selesai' @if (request('filter') == 'selesai') selected @endif>Sudah Diambil</option>
														<option value='menunggu konfirmasi driver' @if (request('filter') == 'menunggu konfirmasi driver') selected @endif>Belum Diambil
														</option>
												</select>
										</div>
										<div id="reportrange" class="flex w-full flex-col">
												<label for="filterDate" class="mb-1 block text-sm font-normal text-gray-700">Filter Tanggal</label>
												<div
														class="flex dark:block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
														<span class="datevalue"></span> <i class="fa fa-caret-down"></i>
														<button type="submit" class="p-2.5 ml-2 text-sm font-medium text-white bg-green rounded-lg border border-green focus:ring-4 focus:outline-none focus:ring-green-light">
															<span class="material-symbols-outlined">calendar_month</span>	
														</button>
												</div>
												<input type="hidden" name="datestart" id="datestart" value="{{ request('datestart') }}">
												<input type="hidden" name="dateend" id="dateend" value="{{ request('dateend') }}">
										</div>
								@endsection
								@include('shared.search')
						</div>
				</div>
				@if (!$deliveryOrders->isEmpty())
						@if (request('search'))
								<div class="flex items-center">
										<button
												class="delete_search mb-2 mr-2 rounded-md bg-red-600 py-1 px-2 text-center text-sm font-normal text-white"
												onclick="">Hapus pencarian</button>
										<h1 class="text-md mb-2 text-center font-medium">Hasil Pencarian Delivery Order {{ request('search') }}</h1>
								</div>
						@endif
						<div class="mb-2 flex items-center">
								<div class="all-status flex items-center">
										<div class="mr-1 h-5 w-5 rounded-full border border-green"></div>
										<p class="text-sm">Banyak Jenis Barang Preorder</p>
								</div>
								<div class="borrow-status ml-2 flex items-center">
										<div class="mr-1 h-5 w-5 rounded-full border border-gray-500"></div>
										<p class="text-sm">Total Kuantitas Barang Preorder</p>
								</div>
						</div>
						<div class="grid grid-cols-2 gap-5 md:grid-cols-2 xl:grid-cols-4">
								@foreach ($deliveryOrders as $deliveryOrder)
										<div
												class="@if ($deliveryOrder->user)  @endif group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
												<a href="{{ route('delivery-order.show', $deliveryOrder->id) }}" class="p-2">
														<div class="flex w-full flex-col">
																{{-- @if (isset($deliveryOrder->diambil)) --}}
																<span
																		class="@if ($deliveryOrder->status == 'SELESAI') bg-green-200 text-green-600 border-green-600
										@elseif ($deliveryOrder->status == 'DRIVER_DALAM_PERJALANAN')
											bg-yellow-200 text-yellow-600 border-yellow-600
										@else 
											bg-red-200 text-red-600 border-red-600 @endif my-1 self-start rounded-full border px-1.5 text-xs">
																		@if ($deliveryOrder->status == 'SELESAI')
																				Sudah Diambil
																		@elseif ($deliveryOrder->status == 'DRIVER_DALAM_PERJALANAN')
																				Dalam Perjalanan
																		@else
																				Belum Diambil
																		@endif
																</span>
																<p class="my-2 font-bold line-clamp-2">{{ ucfirst($deliveryOrder->kode_do) }} </p>
																<div class="flex items-center">
																		<p class="mb-2 self-start rounded-full border border-green px-2 py-1 text-xs text-black">
																				{{ count($deliveryOrder->preOrder) }}
																		</p>
																		<p class="mb-2 ml-2 self-start rounded-full border border-gray-500 px-2 py-1 text-xs text-black">
																				{{ $deliveryOrder->preOrder->sum('jumlah') }}
																		</p>
																</div>
																@if ($deliveryOrder->logistic)
																		<div class="flex">
																				<svg class="mr-1 h-4 w-4 fill-blue-600" width="25" height="26" viewBox="0 0 25 26"
																						fill="none" xmlns="http://www.w3.org/2000/svg">
																						<path fill-rule="evenodd" clip-rule="evenodd"
																								d="M18.5226 5.99648C18.5226 9.30824 15.8668 11.993 12.5906 11.993C9.31437 11.993 6.65849 9.30824 6.65849 5.99648C6.65849 2.68471 9.31437 0 12.5906 0C15.8668 0 18.5226 2.68471 18.5226 5.99648ZM9.23962 13C7.65472 13.1678 3.97774 14.4282 1.94906 18.1268C-0.0796226 21.8254 0.560377 24.642 1.13396 25.588C1.73801 25.8436 3.28857 26.1151 4.93153 25.325L4.92983 25.3134C5.81477 24.7808 6.19517 24.2146 6.27966 23.6828C6.29545 23.0126 5.90048 22.3357 5.50843 21.8764C5.42352 21.7971 5.33781 21.7259 5.25472 21.664C6.28627 18.5953 9.1607 16.3873 12.5453 16.3873C15.9299 16.3873 18.8043 18.5953 19.8358 21.664C19.1631 22.2792 18.3066 23.6501 19.4534 24.7909C19.5857 24.9102 19.7424 25.024 19.9264 25.1303C21.6247 26.1113 23.0991 25.7599 23.7391 25.6074C23.768 25.6005 23.7953 25.594 23.8208 25.588C24.3943 24.642 25.0343 21.8254 23.0057 18.1268C20.977 14.4282 17.3 13.1678 15.7151 13C15.7151 13 13.7693 14.3621 12.3642 14.3275C11.0409 14.2949 9.23962 13 9.23962 13ZM17.0529 20.7839C17.7487 21.729 18.1604 22.9004 18.1604 24.169C18.1604 24.9495 16.7788 25.4533 14.8006 25.6617L14.3554 25.2116C14.5291 24.9042 14.6283 24.5483 14.6283 24.169C14.6283 23.8921 14.5754 23.6276 14.4793 23.3854L17.0529 20.7839ZM13.4702 22.2818L16.0169 19.7075C15.0615 18.9468 13.8558 18.493 12.5453 18.493C11.193 18.493 9.95241 18.9762 8.9832 19.7811L11.5139 22.3392C11.818 22.1637 12.17 22.0634 12.5453 22.0634C12.8775 22.0634 13.1915 22.142 13.4702 22.2818ZM6.93019 24.169C6.93019 22.9428 7.31485 21.8074 7.96885 20.8794L10.5675 23.5063C10.4992 23.7147 10.4623 23.9375 10.4623 24.169C10.4623 24.5048 10.54 24.8223 10.6784 25.104L10.1427 25.6455C8.24353 25.4279 6.93019 24.93 6.93019 24.169ZM12.5453 25.1761C13.0955 25.1761 13.5415 24.7252 13.5415 24.169C13.5415 23.6128 13.0955 23.162 12.5453 23.162C11.9951 23.162 11.5491 23.6128 11.5491 24.169C11.5491 24.7252 11.9951 25.1761 12.5453 25.1761Z" />
																				</svg>
																				<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-1">
																						{{ ucfirst($deliveryOrder->logistic->nama) }}</p>
																		</div>
																		<div class="flex">
																				<svg class="mr-1 h-4 w-4 fill-blue-600" width="18" height="13" viewBox="0 0 18 13"
																						fill="none" xmlns="http://www.w3.org/2000/svg">
																						<path
																								d="M13.8536 4.5708L13.6813 4.50909L12.222 1.77309C11.9479 1.25892 11.5392 0.828921 11.0396 0.52908C10.5401 0.229238 9.96837 0.0708318 9.38571 0.0708008H5.71114C5.03637 0.0707732 4.37869 0.283106 3.83133 0.67771C3.28396 1.07231 2.87466 1.62917 2.66143 2.26937L1.96586 4.35609C1.38311 4.60171 0.885769 5.01377 0.536089 5.54069C0.186409 6.06761 -6.89867e-05 6.68598 1.91447e-08 7.31837V8.74937C1.91447e-08 9.66223 0.543857 10.4465 1.32429 10.8001C1.42535 11.3791 1.72176 11.9061 2.16413 12.2932C2.6065 12.6802 3.16814 12.9041 3.75549 12.9274C4.34284 12.9507 4.92044 12.772 5.39209 12.4212C5.86373 12.0704 6.20095 11.5686 6.34757 10.9994H11.6524C11.799 11.5686 12.1363 12.0704 12.6079 12.4212C13.0796 12.772 13.6572 12.9507 14.2445 12.9274C14.8319 12.9041 15.3935 12.6802 15.8359 12.2932C16.2782 11.9061 16.5746 11.3791 16.6757 10.8001C17.0704 10.6219 17.4052 10.3337 17.6401 9.96994C17.875 9.60618 18 9.18238 18 8.74937V8.31352C17.9999 7.64999 17.7945 7.00275 17.4119 6.46063C17.0293 5.91851 16.4883 5.50807 15.8631 5.28566L13.9217 4.59523V4.5708H13.8536ZM3.88029 2.67566C4.00828 2.29137 4.25402 1.95714 4.58264 1.72036C4.91127 1.48358 5.3061 1.35628 5.71114 1.35652H7.07143V4.5708H3.249L3.88029 2.67566ZM12.2567 4.5708H8.35714V1.35652H9.38571C9.73534 1.35639 10.0784 1.45131 10.3783 1.63113C10.6781 1.81095 10.9234 2.06889 11.088 2.37737L12.258 4.5708H12.2567ZM2.57143 10.3565C2.57143 10.0155 2.70689 9.6885 2.94801 9.44738C3.18912 9.20626 3.51615 9.0708 3.85714 9.0708C4.19814 9.0708 4.52516 9.20626 4.76628 9.44738C5.0074 9.6885 5.14286 10.0155 5.14286 10.3565C5.14286 10.6975 5.0074 11.0245 4.76628 11.2657C4.52516 11.5068 4.19814 11.6422 3.85714 11.6422C3.51615 11.6422 3.18912 11.5068 2.94801 11.2657C2.70689 11.0245 2.57143 10.6975 2.57143 10.3565ZM14.1429 9.0708C14.4838 9.0708 14.8109 9.20626 15.052 9.44738C15.2931 9.6885 15.4286 10.0155 15.4286 10.3565C15.4286 10.6975 15.2931 11.0245 15.052 11.2657C14.8109 11.5068 14.4838 11.6422 14.1429 11.6422C13.8019 11.6422 13.4748 11.5068 13.2337 11.2657C12.9926 11.0245 12.8571 10.6975 12.8571 10.3565C12.8571 10.0155 12.9926 9.6885 13.2337 9.44738C13.4748 9.20626 13.8019 9.0708 14.1429 9.0708Z" />
																				</svg>
																				<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-1">
																						[{{ ucfirst($deliveryOrder->kendaraan->plat_nomor) }}]
																						{{ ucfirst($deliveryOrder->kendaraan->merk) }}</p>
																		</div>
																		<p class="mb-2 text-xs font-normal text-gray-500">
																				{{ \App\Helpers\Date::parseMilliseconds($deliveryOrder->created_at) }}</p>
																@else
																		<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-2">Driver belum dipilih admin gudang</p>
																@endif
												</a>
												@can('ADMIN')
														<div class="relative hidden h-full w-full items-center justify-center group-hover:flex">
																<div
																		class="@if ($deliveryOrder->user) shadow-sm @endif absolute top-0 left-0 z-10 flex h-auto w-full rounded-b-xl bg-white px-2 pt-0 pb-2">
																		<form action="{{ route('delivery-order.destroy', $deliveryOrder->id) }}" method="POST">
																				@csrf
																				<button type="submit"
																						class="show_confirm rounded-md border border-red-500 bg-white py-1 px-3 text-sm text-red-500"
																						data-name="{{ $deliveryOrder->kode_delivery }}">Hapus</button>
																		</form>
																		<a href="{{ route('delivery-order.edit', $deliveryOrder->id) }}"
																				class="ml-2 self-start rounded-md bg-primary py-1 px-3 text-sm text-white">Edit</a>
																</div>
														</div>
												@endcan
										</div>
						</div>
				@endforeach
		</div>
@else
		@if (request('search'))
				<h1 class="text-md mb-2 text-center font-medium text-red-600">Tidak ada Delivery Order {{ request('search') }}</h1>
		@else
				<h1 class="text-md mb-2 text-center font-medium text-red-600">Belum ada Delivery Order</h1>
		@endif
		@endif
		<div class="mt-5">
				{{ $deliveryOrders->links() }}
		</div>
</div>
@endsection

@push('prepend-script')
@include('includes.sweetalert')
@include('includes.jquery')
@include('includes.moment-date-range')
<script>
		$(function() {

				var start = moment().subtract(29, 'days');
				var end = moment();

				function cb(start, end) {
						$("#datestart").val(start.format('Y-m-d'))
						$("#dateend").val(end.format('Y-m-d'))
						$('#reportrange .datevalue').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
						
						if(start==null)$('#reportrange .datevalue').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
						// $("#form").submit();
				}
				$('#reportrange').daterangepicker({
						startDate: start,
						endDate: end,
						ranges: {
								'Today': [moment(), moment()],
								'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
								'Last 7 Days': [moment().subtract(6, 'days'), moment()],
								'Last 30 Days': [moment().subtract(29, 'days'), moment()],
								'This Month': [moment().startOf('month'), moment().endOf('month')],
								'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
								'month')]
						}
				}, cb);

				cb(start, end);

		});
		$('.show_confirm').click(function(event) {
				var form = $(this).closest("form");
				var nama = $(this).data("name");
				event.preventDefault();
				Swal.fire({
						title: "Apakah kamu yakin?",
						html: `Delivery Order yang dihapus tidak dapat dikembalikan, ingin menghapus delivery-order <b>${nama}</b>`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Hapus Delivery Order',
						cancelButtonText: 'Batalkan',
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33'
				}).then((result) => {
						if (result.isConfirmed) {
								form.submit();
						}
				})
		});

		$('.delete_search').click(function(e) {
				$("#searchbox").val('');
				$("#form").submit();
		})
		
</script>
@endpush
