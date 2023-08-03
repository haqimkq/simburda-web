@extends('layouts.app')
@push('prepend-style')
		<link rel="stylesheet"
				href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
@endpush
@section('content')
		<div class="w-full md:ml-[16em]">
				<div class="w-full">
						@if (session('createPeminjamanSuccess'))
								@section('alertMessage', session('createPeminjamanSuccess'))
								@include('shared.alerts.success')
						@endif
						@if (session('deletePeminjamanSuccess'))
								@section('alertMessage', session('deletePeminjamanSuccess'))
								@include('shared.alerts.success')
						@endif
						@section('headerName', 'Peminjaman')
				@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
				@if ($authUser->foto)
						@section('foto', asset($authUser->foto))
				@endif
				@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
				@include('includes.header')
				<div class="my-5 flex w-full items-center justify-items-center">
						<div class="flex w-full">
								@section('last-search')
										<a href="{{ route('peminjaman.create') }}"
												class="button-custom !h-auto !w-auto px-5 md:col-span-2 xl:col-span-4">
												+ Tambah Peminjaman
										</a>
								@endsection
								@section('placeholderSearch', 'Cari Kode Peminjaman') @section('action', '/peminjaman')
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
														<option value='menunggu akses' @if (request('filter') == 'menunggu akses') selected @endif>Menunggu 	Akses</option>
														<option value='menunggu surat jalan' @if (request('filter') == 'menunggu surat jalan') selected @endif>Menunggu Surat Jalan</option>
														<option value='menunggu pengiriman' @if (request('filter') == 'menunggu pengiriman') selected @endif>Menunggu Pengiriman</option>
														<option value='sedang dikirim' @if (request('filter') == 'sedang dikirim') selected @endif>Sedang Dikirim</option>
														<option value='dipinjam' @if (request('filter') == 'dipinjam') selected @endif>Dipinjam</option>
														<option value='selesai' @if (request('filter') == 'selesai') selected @endif>Selesai</option>
														<option value='menunggu konfirmasi driver' @if (request('filter') == 'menunggu konfirmasi driver') selected @endif>Belum Diambil
														</option>
												</select>
										</div>
										<div id="reportrange" class="flex w-full flex-col">
												<label for="filterDate" class="mb-1 block text-sm font-normal text-gray-700">Filter Tanggal Dibuat</label>
												<div class="flex">
														<div
																class="align-items-center flex w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 align-middle text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
																<span class="datevalue self-center">Tanggal</span>
														</div>
														<button type="submit"
																class="ml-2 flex rounded-lg border border-green bg-green p-2 text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-green-light">
																<span class="material-symbols-outlined self-center">calendar_month</span>
														</button>
												</div>
												<input type="hidden" name="datestart" id="datestart" value="{{ request('datestart') }}">
												<input type="hidden" name="dateend" id="dateend" value="{{ request('dateend') }}">
										</div>
								@endsection
								@include('shared.search')
						</div>
				</div>
				@if (!$peminjamans->isEmpty())
						@if (request('search'))
								<div class="flex items-center">
										<button
												class="delete_search mb-2 mr-2 rounded-md bg-red-600 py-1 px-2 text-center text-sm font-normal text-white"
												onclick="">Hapus pencarian</button>
										<h1 class="text-md mb-2 text-center font-medium">Hasil Pencarian Peminjaman {{ request('search') }}</h1>
								</div>
						@endif
						<div class="grid grid-cols-2 gap-5 md:grid-cols-2 xl:grid-cols-4">
								@foreach ($peminjamans as $peminjaman)
										<div
												class="@if ($peminjaman->user)  @endif group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
												<a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="p-2">
														<div class="flex w-full flex-col">
															<div class="flex justify-between">
																<span
																		class="@if ($peminjaman->status=='SELESAI')
																		text-green-600
																		@elseif ($peminjaman->status=='DIPINJAM')
																		text-blue-600
																		@elseif ($peminjaman->status=='MENUNGGU_AKSES')
																		text-gray-600
																		@elseif ($peminjaman->status=='SEDANG_DIKIRIM')
																		text-blue-800
																		@elseif ($peminjaman->status=='MENUNGGU_SURAT_JALAN')
																		text-orange-600
																		@elseif ($peminjaman->status=='MENUNGGU_PENGIRIMAN')
																		text-purple-600
																		@endif my-1 self-start text-xs">
																		{{ App\Helpers\Utils::underscoreToNormal($peminjaman->status) }}
																</span>
																<span
																		class="text-gray-600 
																		my-1 self-end rounded-full px-1.5 text-xs">
																		{{ \App\Helpers\Date::parseMilliseconds($peminjaman->created_at) }}
																</span>
																
															</div>
																<p class="my-2 font-bold break-words">{{ ucfirst($peminjaman->kode_peminjaman) }} </p>
																<div class="mb-2 flex flex-wrap">
																		@if (!$peminjaman->peminjamanDetail->isEmpty())
																				@foreach ($peminjaman->peminjamanDetail as $key => $row)
																				<div class="flex flex-col">
																					<img src="{{asset($row->barang->barang->gambar)}}" alt="" class="w-[3em] h-[3em] rounded-md">
																				<p
																						class="mr-1 w-[8em] overflow-hidden break-words mt-1 text-[0.5em] text-gray-500">
																						(#{{ $row->barang->nomor_seri }}) {{ $row->barang->barang->nama }} {{ $row->barang->barang->merk }}</p>
																				</div>
																				@php if (++$key == 3) break; @endphp
																				@endforeach
																		@endif
																</div>
																<div class="flex mb-2">
																	<svg class="w-4 h-4 mr-1 fill-green" width="25" height="29" viewBox="0 0 25 29" xmlns="http://www.w3.org/2000/svg">
																		<path d="M14.5966 0.394986L14.2487 3.19271C14.4126 3.24282 14.5735 3.2999 14.7311 3.36362L15.4068 0.796254C17.0362 1.77434 18.1337 3.5574 18.1583 5.60052C18.5889 5.79392 18.8315 6.0286 18.8315 6.30606C18.8315 6.70076 18.6063 6.97699 18.2046 7.1702C18.3763 7.73198 18.4687 8.32896 18.4687 8.94779C18.4687 12.2676 15.809 14.9589 12.528 14.9589C9.24711 14.9589 6.58739 12.2676 6.58739 8.94779C6.58739 8.32896 6.6798 7.73198 6.85143 7.1702C6.4498 6.97699 6.2246 6.70076 6.2246 6.30606C6.2246 6.02877 6.46681 5.79383 6.89697 5.60052C6.89748 5.55803 6.89847 5.51567 6.8999 5.47344C6.90282 5.38682 6.90768 5.30074 6.91443 5.21516C7.06139 3.3528 8.10189 1.74384 9.60414 0.823115L10.2778 3.38292C10.4343 3.31804 10.5942 3.25973 10.7571 3.20832L10.4097 0.41474C10.4747 0.388126 10.5404 0.362711 10.6066 0.338536C11.2061 0.119471 11.853 0 12.5276 0C13.1305 0 13.7111 0.0953786 14.2557 0.272002C14.371 0.309402 14.4847 0.350438 14.5966 0.394986ZM1.9479 21.1076C3.97951 17.4 7.66181 16.1366 9.249 15.9684V19.584C9.249 19.8619 9.31138 20.1362 9.43142 20.3862L10.8135 23.2642L11.3601 23.6169L11.0753 21.5579L12.1075 19.6181L11.4492 18.9996C11.2367 18.7999 11.2367 18.4596 11.4492 18.2599L12.392 17.3741L13.3347 18.2599C13.5473 18.4596 13.5473 18.7999 13.3347 18.9996L12.6765 19.6181L13.7087 21.5579L13.4156 23.6764L13.9722 23.356L15.3687 20.5825C15.4986 20.3244 15.5664 20.0389 15.5664 19.7493V15.9684C17.1536 16.1366 21.0033 17.4 23.0349 21.1076C23.9079 22.7007 24.307 24.382 24.4782 25.7815C24.7023 27.6128 23.1664 29 21.3427 29H3.65101C1.84221 29 0.312714 27.634 0.518643 25.8156C0.677901 24.4094 1.06781 22.7137 1.9479 21.1076Z"/>
																	</svg>
																	<p class="font-normal line-clamp-1 text-sm">{{ucfirst($peminjaman->menangani->user->nama)}} </p>
																</div>
																<p class="mb-2 text-xs font-normal text-gray-500">{{ $peminjaman->menangani->proyek->nama_proyek }}</p>
																<p class="mb-2 text-xs font-normal text-gray-500"> Tgl Pinjam:
																		{{ \App\Helpers\Date::parseMilliseconds($peminjaman->tgl_peminjaman) }}</p>
																<p class="mb-2 text-xs font-normal text-gray-500"> Tgl Berakhir:
																		{{ \App\Helpers\Date::parseMilliseconds($peminjaman->tgl_berakhir) }}</p>
																		<p class="mb-2 text-xs font-normal text-gray-500">Durasi: {{ \App\Helpers\Date::diffInDaysMillis($peminjaman->tgl_peminjaman,$peminjaman->tgl_berakhir)}} Hari</p>
												</a>
												@can('ADMIN')
														<div class="relative hidden h-full w-full items-center justify-center group-hover:flex">
																<div
																		class="@if ($peminjaman->user) shadow-sm @endif absolute top-0 left-0 z-10 flex h-auto w-full rounded-b-xl bg-white px-2 pt-0 pb-2">
																		<form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST">
																				@csrf
																				<button type="submit"
																						class="show_confirm rounded-md border border-red-500 bg-white py-1 px-3 text-sm text-red-500"
																						data-name="{{ $peminjaman->kode_delivery }}">Hapus</button>
																		</form>
																		<a href="{{ route('peminjaman.edit', $peminjaman->id) }}"
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
				<h1 class="text-md mb-2 text-center font-medium text-red-600">Tidak ada Peminjaman {{ request('search') }}
				</h1>
		@else
				<h1 class="text-md mb-2 text-center font-medium text-red-600">Belum ada Peminjaman</h1>
		@endif
		@endif
		<div class="mt-5">
				{{ $peminjamans->links() }}
		</div>
</div>
@endsection

@push('prepend-script')
@include('includes.sweetalert')
@include('includes.jquery')
@include('includes.moment-date-range')
<script>
		$(function() {
				const pathname = window.location.href;
				console.log(pathname);
				var start = (pathname.includes("datestart")) ? moment('{{ request('datestart') }}') : moment().subtract(3,
						'years');
				var end = (pathname.includes("dateend")) ? moment('{{ request('dateend') }}') : moment();

				function cb(start, end) {
						$("#datestart").val(start.format('Y-M-D'))
						$("#dateend").val(end.format('Y-M-D'))
						$('#reportrange .datevalue').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
				}
				$('#reportrange').daterangepicker({
						startDate: start,
						endDate: end,
						ranges: {
								'Hari ini': [moment(), moment()],
								'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
								'7 Hari lalu': [moment().subtract(6, 'days'), moment()],
								'30 Hari lalu': [moment().subtract(29, 'days'), moment()],
								'1 Tahun lalu': [moment().subtract(1, 'year'), moment()],
								'2 Tahun lalu': [moment().subtract(2, 'years'), moment()],
								'3 Tahun lalu': [moment().subtract(3, 'years'), moment()],
								'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
								'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
										'month')],
								'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf(
										'year')]
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
						html: `Peminjaman yang dihapus tidak dapat dikembalikan, ingin menghapus peminjaman <b>${nama}</b>`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Hapus Peminjaman',
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