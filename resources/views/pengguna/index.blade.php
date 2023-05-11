@extends('layouts.app')
@push('prepend-script')
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script>
		$('.show_confirm').click(function(event) {
				var form =  $(this).closest("form");
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
	</script>
@endpush
@section('content')
<div class="md:ml-[16em] w-full">
	<div class="w-full pl-4">
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
			<div class="w-full flex">
				@section('last-search')
				<a href="{{ route('pengguna.create') }}" class="button-custom !w-auto px-5 !h-auto">
					+ Tambah Pengguna
				</a>
				@endsection
				@section('placeholderSearch', 'Cari Pengguna') @section('action', '/pengguna') 
				@section('middle-search')
					@if (request('page'))
						<input type="hidden" name="page" id="" value="{{ request('page') }}">
					@endif
					<div class="flex flex-col w-full">
						<label for="orderBy" class="mb-1 block text-sm font-normal text-gray-700">Urutkan Berdasarkan</label>
						<select name="orderBy" id="orderBy" onchange="this.form.submit()"
							class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900  focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark: dark:focus:ring-green"
							>
							<option value="terbaru" @if(request('orderBy') == 'terbaru') selected @endif>Terbaru</option>
							<option value="terlama" @if(request('orderBy') == 'terlama') selected @endif>Terlama</option>
							<option value="role" @if(request('orderBy') == 'role') selected @endif>Role</option>
						</select>
					</div>
					<div class="flex flex-col w-full">
						<label for="filterRole" class="mb-1 block text-sm font-normal text-gray-700">Filter Role</label>
						<select name="filter" id="filterRole" onchange="this.form.submit()"
						class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900  focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark: dark:focus:ring-green"
						>
							<option value="semua role"  @if(request('filter') == 'semua role') selected @endif>Semua Role</option>
							<option value="ADMIN" @if(request('filter') == 'ADMIN') selected @endif>Admin</option>
							<option value="PROJECT_MANAGER"  @if(request('filter') == 'PROJECT_MANAGER') selected @endif>Project Manager</option>
							<option value="SUPERVISOR" @if(request('filter') == 'SUPERVISOR') selected @endif>Supervisor</option>
							<option value="LOGISTIC"  @if(request('filter') == 'LOGISTIC') selected @endif>Logistic</option>
							<option value="USER"  @if(request('filter') == 'USER') selected @endif>User</option>
							<option value="ADMIN_GUDANG"  @if(request('filter') == 'ADMIN_GUDANG') selected @endif>Admin Gudang</option>
							<option value="PURCHASING"  @if(request('filter') == 'PURCHASING') selected @endif>Purchasing</option>
						</select>
					</div>
				@endsection
				@include('shared.search')
			</div>
		</div>
		<div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-5">
			@foreach ($allUser as $user)
			<div class="group flex flex-col shadow-md shadow-gray-100 rounded-xl justify-center items-center hover:rounded-b-none">
				<a href="{{ route('pengguna.show', $user->id) }}" class=" p-2 ">
				<div class="rounded-full w-[6em] md:w-[5em] lg:w-[7em] h-[6em] md:h-[5em] lg:h-[7em] bg-cover mb-2" 
					style="background-image: url('{{asset($user->foto)}}')">
				</div>
				<div class="flex flex-col w-full">
					<span
						class="self-start my-1 bg-green-200 text-green-600 border-green-600 rounded-full border px-1.5 text-xs">
						{{ ucfirst($user->role) }}
					</span>
					<p class="text-sm font-normal mb-1 line-clamp-1">{{$user->nama}}</p>
					<p class="text-xs font-normal mb-1 text-gray-500">{{$user->no_hp}}</p>
				</a>
				<div class="relative hidden justify-center items-center group-hover:flex w-full h-full">
					<div class="absolute w-full z-10  h-auto bg-white flex top-0 px-2 left-0 rounded-b-xl pt-0 pb-2 shadow-md">
						<form action="{{ route('pengguna.destroy', $user->id) }}" method="POST">
							@csrf
							<button type="submit"
								class="show_confirm  bg-white rounded-md border border-red-500 py-1 px-3 text-sm text-red-500"
								data-name="{{ $user->nama }}">Hapus</button>
						</form>
						<a href="{{ route('pengguna.edit', $user->id) }}"
							class="bg-primary ml-2 rounded-md py-1 px-3 text-sm text-white self-start">Edit</a>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
	<div class="mt-5">
		{{ $allUser->links() }}
	</div>
</div>
@endsection
