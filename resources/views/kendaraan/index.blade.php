@extends('layouts.app')
@push('prepend-script')
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"
				integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script>
				$('.show_confirm').click(function(event) {
						var form = $(this).closest("form");
						var nama = $(this).data("name");
						event.preventDefault();
						Swal.fire({
								title: "Apakah kamu yakin?",
								html: `Kendaraan yang dihapus tidak dapat dikembalikan, ingin menghapus kendaraan <b>${nama}</b>`,
								icon: 'warning',
								showCancelButton: true,
								confirmButtonText: 'Ya, Hapus Kendaraan',
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
@section('content')
<div class="w-full md:ml-[16em]">
		<div class="w-full">
						@if (session('createKendaraanSuccess'))
								@section('alertMessage', session('createKendaraanSuccess'))
								@include('shared.alerts.success')
						@endif
						@if (session('deleteKendaraanSuccess'))
								@section('alertMessage', session('deleteKendaraanSuccess'))
								@include('shared.alerts.success')
						@endif
						@section('headerName', 'Kendaraan')
				@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
				@if ($authUser->foto)
						@section('foto', asset($authUser->foto))
				@endif
				@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
				@include('includes.header')
				<div class="my-5 flex w-full items-center justify-items-center">
						<div class="flex w-full">
								@section('last-search')
										<a href="{{ route('kendaraan.create') }}" class="button-custom !h-auto !w-auto px-5">
												+ Tambah Kendaraan
										</a>
								@endsection
								@section('placeholderSearch', 'Cari Merk Kendaraan') @section('action', '/kendaraan')
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
												<label for="filterRole" class="mb-1 block text-sm font-normal text-gray-700">Filter Jenis</label>
												<select name="filter" id="filterRole" onchange="this.form.submit()"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
														<option value="semua jenis" @if (request('filter') == 'semua jenis') selected @endif>Semua Jenis</option>
														<option value="motor" @if (request('filter') == 'motor') selected @endif>Motor</option>
														<option value="mobil" @if (request('filter') == 'mobil') selected @endif>Mobil</option>
														<option value="truck" @if (request('filter') == 'truck') selected @endif>Truck</option>
														<option value="tronton" @if (request('filter') == 'tronton') selected @endif>Tronton</option>
												</select>
										</div>
								@endsection
								@include('shared.search')
						</div>
				</div>
				<div class="grid grid-cols-2 gap-5 md:grid-cols-2 xl:grid-cols-4">
						@foreach ($allKendaraan as $kendaraan)
								<div class="group flex w-full flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
										<a href="{{ route('kendaraan.show', $kendaraan->id) }}" class="pl-0">
												<div class="flex">
														<div class="m-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
																style="background-image: url('{{ asset($kendaraan->gambar) }}')"></div>
														<div class="flex w-full flex-col">
																<span
																		class="mb-1 self-start rounded-full border border-green-600 bg-green-200 px-1.5 text-xs text-green-600 md:mb-0 md:mr-1">
																		{{ ucfirst($kendaraan->jenis) }}
																</span>
																<div class="mt-1 flex flex-col md:flex-row md:items-center">
																		<p class="font-medium line-clamp-2">{{ ucfirst($kendaraan->merk) }}</p>
																</div>
																<p class="my-1 text-sm font-normal uppercase line-clamp-1">{{ $kendaraan->plat_nomor }}</p>
																<div class="flex items-center overflow-x-auto">
																		<img src="/images/ic_gudang.png" alt="" class="mr-1 h-[1.1em] w-auto">
																		<p class="text-sm font-normal line-clamp-2">{{ $kendaraan->gudang->nama }}</p>
																</div>
																@if ($kendaraan->user)
																		<div class="flex items-center">
																				<img class="mr-1 h-[1.1em] w-[1.1em] rounded-full object-none object-center"
																						src="{{ asset($kendaraan->user->foto) }}" alt="">
																				<p class="text-sm font-normal line-clamp-2">{{ ucfirst($kendaraan->user->nama) }}</p>
																		</div>
																@endif
														</div>
												</div>
										</a>
										<div class="relative hidden h-full w-full items-center justify-center md:hidden md:group-hover:flex">
												<div
														class="absolute left-0 top-0 z-10 flex h-auto w-full justify-end rounded-b-xl bg-white px-2 pb-2 pt-0 shadow-sm">
														<form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST">
																@csrf
																<button type="submit"
																		class="show_confirm rounded-md border border-red-500 bg-white px-3 py-1 text-sm text-red-500"
																		data-name="{{ $kendaraan->merk }} ({{ $kendaraan->plat_nomor }})">Hapus</button>
														</form>
														<a href="{{ route('kendaraan.edit', $kendaraan->id) }}"
																class="ml-2 self-start rounded-md bg-primary px-3 py-1 text-sm text-white">Edit</a>
												</div>
										</div>
								</div>
						@endforeach
				</div>
		</div>
		<div class="pl-4 mt-5">
				{{ $allKendaraan->links() }}
		</div>
</div>
@endsection
