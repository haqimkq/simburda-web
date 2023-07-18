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
							<option value="ADMIN" @if (request('filter') == 'ADMIN') selected @endif>Admin</option>
							<option value="PROJECT_MANAGER" @if (request('filter') == 'PROJECT_MANAGER') selected @endif>Project Manager</option>
							<option value="SUPERVISOR" @if (request('filter') == 'SUPERVISOR') selected @endif>Supervisor</option>
							<option value="LOGISTIC" @if (request('filter') == 'LOGISTIC') selected @endif>Logistic</option>
							<option value="USER" @if (request('filter') == 'USER') selected @endif>User</option>
							<option value="ADMIN_GUDANG" @if (request('filter') == 'ADMIN_GUDANG') selected @endif>Admin Gudang</option>
							<option value="PURCHASING" @if (request('filter') == 'PURCHASING') selected @endif>Purchasing</option>
							<option value="SET_MANAGER" @if (request('filter') == 'SET_MANAGER') selected @endif>Set Manager</option>
						</select>
					</div>
				@endsection
				@include('shared.search')
			</div>
		</div>
		<div class="grid grid-cols-2 gap-5 md:grid-cols-2 xl:grid-cols-4">
			@foreach ($allUser as $user)
				<div
					class="group flex flex-col items-center justify-center rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
						<div class="mb-2 h-[6em] w-[6em] rounded-full bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
							style="background-image: url('{{ asset($user->foto) }}')">
						</div>
						<div class="flex w-full flex-col">
							<span class="my-1 self-start rounded-full border border-green-600 bg-green-200 px-1.5 text-xs text-green-600">
								{{ \App\Helpers\Utils::underscoreToNormal($user->role) }}
							</span>
							<p class="mb-1 text-sm font-normal line-clamp-1">{{ $user->nama }}</p>
							<p class="mb-1 text-xs font-normal text-gray-500">{{ $user->no_hp }}</p>
					@can('ADMIN')
						<div class="relative hidden h-full w-full items-center justify-center group-hover:flex">
							<div class="absolute top-0 left-0 z-10 flex h-auto w-full rounded-b-xl bg-white px-2 pt-0 pb-2 shadow-md">
								<form action="{{ route('pengguna.destroy', $user->id) }}" method="POST">
									@csrf
									<button type="submit"
										class="show_confirm rounded-md border border-red-500 bg-white py-1 px-3 text-sm text-red-500"
										data-name="{{ $user->nama }}">Hapus</button>
								</form>
								<a href="{{ route('pengguna.edit', $user->id) }}"
									class="ml-2 self-start rounded-md bg-primary py-1 px-3 text-sm text-white">Edit</a>
							</div>
						</div>
					@endcan
				</div>
		</div>
		@endforeach
	</div>
	<div class="mt-5">
		{{ $allUser->links() }}
	</div>
</div>
@endsection
