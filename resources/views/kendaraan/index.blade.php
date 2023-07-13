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
		<div class="w-full pl-4">
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
							class="focus:ring-green dark: dark:focus:ring-green block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
							<option value="terbaru" @if (request('orderBy') == 'terbaru') selected @endif>Terbaru</option>
							<option value="terlama" @if (request('orderBy') == 'terlama') selected @endif>Terlama</option>
						</select>
					</div>
					<div class="flex w-full flex-col">
						<label for="filterRole" class="mb-1 block text-sm font-normal text-gray-700">Filter Jenis</label>
						<select name="filter" id="filterRole" onchange="this.form.submit()"
							class="focus:ring-green dark: dark:focus:ring-green block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
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
			<div class="w-full flex flex-col group rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
				<div class="flex">
					<div class="m-2 h-[5em] w-[8em] rounded-md bg-cover bg-center"
					style="background-image: url('{{ asset($kendaraan->gambar) }}')"></div>
					<a href="{{ route('kendaraan.show', $kendaraan->id) }}" class="pl-0">
					<div
						class="flex flex-col w-full">
							<div class="flex w-full flex-col">
								<span class="md:mr-1 mb-1 md:mb-0 self-start rounded-full border border-green-600 bg-green-200 px-1.5 text-xs text-green-600">
									{{ ucfirst($kendaraan->jenis) }}
								</span>
								<div class="flex flex-col md:flex-row md:items-center mt-1">
									<p class="line-clamp-2 font-medium">{{ ucfirst($kendaraan->merk) }}</p>
								</div>
								<p class="line-clamp-1 my-1 text-sm font-normal uppercase">{{ $kendaraan->plat_nomor }}</p>
								@if ($kendaraan->user)
									<div class="flex items-center">
										<img class="mr-1 h-5 w-5 rounded-full object-none object-center" src="{{ asset($kendaraan->user->foto) }}"
											alt="">
										<p class="line-clamp-1 my-1 text-sm font-normal">{{ ucfirst($kendaraan->user->nama) }}</p>
									</div>
								@endif
							</div>
						</a>
						</div>
			</div>
			<div class="relative hidden md:hidden h-full w-full items-center justify-center md:group-hover:flex">
				<div class="absolute top-0 left-0 z-10 flex h-auto w-full rounded-b-xl bg-white px-2 pt-0 pb-2 shadow-sm justify-end">
					<form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST">
						@csrf
						<button type="submit"
							class="show_confirm rounded-md border border-red-500 bg-white py-1 px-3 text-sm text-red-500"
							data-name="{{ $kendaraan->merk }} ({{ $kendaraan->plat_nomor }})">Hapus</button>
					</form>
					<a href="{{ route('kendaraan.edit', $kendaraan->id) }}"
						class="bg-primary ml-2 self-start rounded-md py-1 px-3 text-sm text-white">Edit</a>
				</div>
			</div>
			</div>
		@endforeach
	</div>
	<div class="mt-5">
		{{ $allKendaraan->links() }}
	</div>
</div>
@endsection
