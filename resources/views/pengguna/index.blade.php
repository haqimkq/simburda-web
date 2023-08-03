@extends('layouts.app')
@push('prepend-script')
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"
				integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script>
				@can('ADMIN')
						$('.show_confirm').click(function(event) {
								var form = $(this).closest("form");
								var nama = $(this).data("name");
								event.preventDefault();
								Swal.fire({
										title: "Apakah kamu yakin?",
										html: `Akun yang dihapus tidak dapat dikembalikan, ingin menghapus akun <b>${nama}</b>`,
										icon: 'warning',
										showCancelButton: true,
										confirmButtonText: 'Ya, Hapus Akun',
										cancelButtonText: 'Batalkan',
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33'
								}).then((result) => {
										if (result.isConfirmed) {
												form.submit();
										}
								})
						});
				@endcan
		</script>
@endpush
@section('content')
		<div class="w-full md:ml-[16em]">
				<div class="w-full">
						@if (session('createPenggunaSuccess'))
								@section('alertMessage', session('createPenggunaSuccess'))
								@include('shared.alerts.success')
						@endif
						@if (session('deletePenggunaSuccess'))
								@section('alertMessage', session('deletePenggunaSuccess'))
								@include('shared.alerts.success')
						@endif
						@if (session('updatePenggunaSuccess'))
								@section('alertMessage', session('updatePenggunaSuccess'))
								@include('shared.alerts.success')
						@endif
						@section('headerName', 'Pengguna')
				@section('role', $authUser->role)
				@if ($authUser->foto)
						@section('foto', asset($authUser->foto))
				@endif
				@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
				@include('includes.header')
				<div class="my-5 flex w-full items-center justify-items-center">
						<div class="flex w-full">
								@section('last-search')
										<a href="{{ route('pengguna.create') }}" class="button-custom !h-auto !w-auto px-5">
												+ Tambah Pengguna
										</a>
								@endsection
								@section('placeholderSearch', 'Cari Pengguna') @section('action', '/pengguna')
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
														<option value="role" @if (request('orderBy') == 'role') selected @endif>Role</option>
												</select>
										</div>
										<div class="flex w-full flex-col">
												<label for="filterRole" class="mb-1 block text-sm font-normal text-gray-700">Filter Role</label>
												<select name="filter" id="filterRole" onchange="this.form.submit()"
														class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
														<option value="semua role" @if (request('filter') == 'semua role') selected @endif>Semua Role</option>
														<option value="admin" @if (request('filter') == 'admin') selected @endif>Admin</option>
														<option value="project manager" @if (request('filter') == 'project manager') selected @endif>Project Manager</option>
														<option value="supervisor" @if (request('filter') == 'supervisor') selected @endif>Supervisor</option>
														<option value="logistic" @if (request('filter') == 'logistic') selected @endif>Logistic</option>
														<option value="user" @if (request('filter') == 'user') selected @endif>User</option>
														<option value="admin gudang" @if (request('filter') == 'admin gudang') selected @endif>Admin Gudang</option>
														<option value="purchasing" @if (request('filter') == 'purchasing') selected @endif>Purchasing</option>
														<option value="site manager" @if (request('filter') == 'site manager') selected @endif>Set Manager</option>
												</select>
										</div>
								@endsection
								@include('shared.search')
						</div>
				</div>
				@if(!$allUser->isEmpty())
				<div class="grid grid-cols-2 gap-5 md:grid-cols-2 xl:grid-cols-4">
						@foreach ($allUser as $user)
								<div
										class="group flex flex-col items-center justify-center rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
										<div class="mb-2 h-[6em] w-[6em] rounded-full bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
												style="background-image: url('{{ asset($user->foto) }}')">
										</div>
										<div class="flex w-full flex-col">
												<div class="flex flex-col p-2">
														<span
																class="my-1 self-start rounded-full border border-green-600 bg-green-200 px-1.5 text-xs text-green-600">
																{{ \App\Helpers\Utils::underscoreToNormal($user->role) }}
														</span>
														<p class="mb-1 text-sm font-normal">{{ $user->nama }}</p>
														<p class="mb-1 text-xs font-normal text-gray-500">{{ $user->no_hp }}</p>
														@if ($user->role=='LOGISTIC')
															<div class="flex flex-wrap">
																		@if (count($user->activeDeliveryOrderLogistic) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																						class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
																						{{ count($user->activeDeliveryOrderLogistic) }}
																								DO Aktif
																						</p>
																				</div>
																		@endif
																		@if (count($user->activeSJGPLogistic) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																						class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
																						{{ count($user->activeSJGPLogistic) }}
																								SJGP Aktif
																						</p>
																				</div>
																		@endif
																		@if (count($user->activeSJPGLogistic) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																						class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
																						{{ count($user->activeSJPGLogistic) }} SJPG Aktif
																						</p>
																				</div>
																		@endif
																		@if (count($user->activeSJPPLogistic) != 0)
																				<div class="mr-2 flex items-center md:flex-col lg:flex-row">
																						<p
																						class="mb-2 self-start rounded-md border border-gray-600 bg-gray-200 px-2 text-xs text-gray-600">
																						{{ count($user->activeSJPPLogistic) }} SJPP Aktif
																						</p>
																				</div>
																		@endif
																</div>
														@endif
												</div>
												@can('ADMIN')
														<div class="relative hidden h-full w-full items-center justify-center group-hover:flex">
																<div class="absolute left-0 top-0 z-10 flex h-auto w-full rounded-b-xl bg-white px-2 pb-2 pt-0 shadow-md">
																		<form action="{{ route('pengguna.destroy', $user->id) }}" method="POST">
																				@csrf
																				<button type="submit"
																						class="show_confirm rounded-md border border-red-500 bg-white px-3 py-1 text-sm text-red-500"
																						data-name="{{ $user->nama }}">Hapus</button>
																		</form>
																		<a href="{{ route('pengguna.edit', $user->id) }}"
																				class="ml-2 self-start rounded-md bg-primary px-3 py-1 text-sm text-white">Edit</a>
																</div>
														</div>
												@endcan
										</div>
								</div>
						@endforeach
				</div>
				@else
						@if (request('search'))
								<h1 class="text-md mb-2 text-center font-medium text-red-600">Tidak ada pengguna bernama {{ request('search') }}</h1>
						@else
								<h1 class="text-md mb-2 text-center font-medium text-red-600">Belum ada pengguna</h1>
				@endif
				@endif
				<div class="mt-5">
						{{ $allUser->links() }}
				</div>
		</div>
@endsection
