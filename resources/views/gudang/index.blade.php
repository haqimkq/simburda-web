@extends('layouts.app')
@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
@section('content')
		<div class="w-full md:ml-[16em]">
				<div class="w-full pl-4">
						@if (session('createGudangSuccess'))
								@section('alertMessage', session('createGudangSuccess'))
								@include('shared.alerts.success')
						@endif
						@if (session('deleteGudangSuccess'))
								@section('alertMessage', session('deleteGudangSuccess'))
								@include('shared.alerts.success')
						@endif
						@section('headerName', 'Gudang')
						@if ($authUser->foto)
								@section('foto', asset($authUser->foto))
						@endif
						@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
						@include('includes.header')
						<div class="my-5 flex w-full items-center justify-items-center">
								<div class="flex w-full">
								@section('last-search')
										<a href="{{ route('gudang.create') }}" class="button-custom !h-auto !w-auto px-5">
												+ Tambah Gudang
										</a>
								@endsection
								@section('placeholderSearch', 'Cari Gudang') @section('action', '/gudang')
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
												<label for="filterProvinsi" class="mb-1 block text-sm font-normal text-gray-700">Filter Provinsi</label>
												<select name="filter" id="filterProvinsi" onchange="this.form.submit()"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
														<option value="semua provinsi" @if (request('filter') == 'semua provinsi') selected @endif>Semua Provinsi</option>
														@foreach ($provinsis as $provinsi)
																<option value="{{ $provinsi->provinsi }}" @if (request('filter') == $provinsi->provinsi) selected @endif>
																		{{ $provinsi->provinsi }}</option>
														@endforeach
												</select>
										</div>
								@endsection
								@include('shared.search')
						</div>
				</div>
				@if (!$gudangs->isEmpty())
						@if (request('search'))
								<div class="mb-5 flex items-center">
										<button class="delete_search mr-2 rounded-md bg-red-600 px-2 py-1 text-center text-sm font-normal text-white"
												onclick="">Hapus pencarian</button>
										<h1 class="text-md text-center font-normal">Hasil Pencarian Gudang "{{ request('search') }}"</h1>
								</div>
						@endif
						<div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
								@foreach ($gudangs as $gudang)
										<div class="group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
												<a href="" class="flex flex-col">
														<div class="mr-2 h-[15em] w-full rounded-xl bg-cover md:w-full lg:h-[10em] lg:w-full"
																style="background-image: url('{{ asset($gudang->gambar) }}')"></div>
														<div class="flex flex-col">
																<span
																		class="my-2 self-start rounded-full border border-gray-600 bg-gray-200 px-1.5 text-xs text-gray-600">
																		{{ App\Helpers\Utils::underscoreToNormal($gudang->provinsi) }}
																</span>
																<p class="mb-2 font-medium line-clamp-1 md:line-clamp-2 xl:line-clamp-3">{{ $gudang->nama }}</p>
																<p class="mb-2 text-sm font-normal text-gray-700 line-clamp-1 md:line-clamp-2 xl:line-clamp-3">
																		{{ ucfirst($gudang->alamat) }}</p>
																<div class="flex flex-wrap">
																		@if (\App\Models\Gudang::getActiveDeliveryOrder($gudang->id) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																								class="mb-2 self-start rounded-full border border-green-600 bg-green-200 px-2 py-1 text-xs text-gray-600">
																								{{ \App\Models\Gudang::getActiveDeliveryOrder($gudang->id) }}
																								DO Aktif
																						</p>
																				</div>
																		@endif
																		@if (\App\Models\Gudang::getActiveSjGp($gudang->id) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																								class="mb-2 self-start rounded-full border border-green-600 bg-green-200 px-2 py-1 text-xs text-gray-600">
																								{{ \App\Models\Gudang::getActiveSjGp($gudang->id) }}
																								SJGP Aktif
																						</p>
																				</div>
																		@endif
																		@if (\App\Models\Gudang::getActiveSjPg($gudang->id) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																								class="mb-2 self-start rounded-full border border-green-600 bg-green-200 px-2 py-1 text-xs text-gray-600">
																								{{ \App\Models\Gudang::getActiveSjPg($gudang->id) }}
																								SJPG Aktif
																						</p>
																				</div>
																		@endif
																</div>
														</div>
												</a>
												<div class="relative hidden h-full w-full items-center justify-center group-hover:flex">
														<div class="absolute left-0 top-0 z-10 flex h-auto w-full rounded-b-xl bg-white px-2 pb-2 pt-0 shadow-md">
																<form action="{{ route('gudang.destroy', $gudang->id) }}" method="POST">
																		@csrf
																		<button type="submit"
																				class="show_confirm rounded-md border border-red-500 bg-white px-3 py-1 text-sm text-red-500"
																				data-name="{{ $gudang->nama }}">Hapus</button>
																</form>
																<a href="{{ route('gudang.edit', $gudang->id) }}"
																		class="ml-2 self-start rounded-md bg-primary px-3 py-1 text-sm text-white">Edit</a>
														</div>
												</div>
										</div>
								@endforeach
						</div>
		</div>
@else
		@if (request('search'))
				<h1 class="text-md mb-2 text-center font-medium text-red-600">Tidak ada Gudang {{ request('search') }}</h1>
		@else
				<h1 class="text-md mb-2 text-center font-medium text-red-600">Belum ada Gudang</h1>
		@endif
		@endif
</div>
@endsection

@push('prepend-script')
@include('includes.sweetalert')
@include('includes.jquery')
<script>
		$('.show_confirm').click(function(event) {
				var form = $(this).closest("form");
				var nama = $(this).data("name");
				event.preventDefault();
				Swal.fire({
						title: "Apakah kamu yakin?",
						html: `Gudang yang dihapus tidak dapat dikembalikan, ingin menghapus gudang <b>${nama}</b>`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Hapus Gudang',
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
