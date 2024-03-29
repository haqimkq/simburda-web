@extends('layouts.app')
@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
@section('content')
<div class="md:ml-[16em] w-full">
	<div class="w-full">
			@if (session('createProyekSuccess'))
				@section('alertMessage', session('createProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@if (session('deleteProyekSuccess'))
				@section('alertMessage', session('deleteProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@section('headerName', 'Proyek')
			@if ($authUser->foto)
				@section('foto', asset($authUser->foto))
			@endif
			@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
			@include('includes.header')
		<div class="my-5 flex w-full items-center justify-items-center">
			<div class="w-full flex">
				@section('last-search')
				<a href="{{ route('proyek.create') }}" class="button-custom !w-auto px-5 !h-auto">
					+ Tambah Proyek
				</a>
				@endsection
				@section('placeholderSearch', 'Cari Proyek') @section('action', '/proyek') 
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
						</select>
					</div>
					<div class="flex flex-col w-full">
						<label for="filterStatus" class="mb-1 block text-sm font-normal text-gray-700">Filter Status</label>
						<select name="filter" id="filterStatus" onchange="this.form.submit()"
						class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900  focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark: dark:focus:ring-green"
						>
							<option value="semua status"  @if(request('filter') == 'semua status') selected @endif>Semua Status</option>
							<option value='masih berlangsung' @if(request('filter') == 'masih berlangsung') selected @endif>Masih Berlangsung</option>
							<option value='selesai'  @if(request('filter') == 'selesai') selected @endif>Selesai</option>
						</select>
					</div>
				@endsection
				@include('shared.search')
			</div>
		</div>
		<div class="mb-4 flex items-center">
			<div class="all-status flex items-center">
				<div class="border-green-500 mr-1 h-5 w-5 rounded-full border"></div>
				<p class="text-sm">Ditangani</p>
			</div>
			<div class="borrow-status ml-2 flex items-center">
				<div class="mr-1 h-5 w-5 rounded-full border border-red-500"></div>
				<p class="text-sm">Tidak Ditangani</p>
			</div>
		</div>
		@if (!$proyeks->isEmpty())
				@if (request('search'))
				<div class="flex items-center">
					<button class="bg-red-600 py-1 px-2 mb-2 mr-2 text-center font-normal text-sm delete_search text-white rounded-md" onclick="">Hapus pencarian</button>
					<h1 class="mb-2 text-center font-medium text-md">Hasil Pencarian Proyek {{request('search')}}</h1>
				</div>
				@endif
				<div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-5">
					@foreach ($proyeks as $proyek)
					<div class="group flex flex-col shadow-md shadow-gray-100 rounded-xl hover:rounded-b-none border @if ($proyek->isUserMenanganiProyek($authUser->id,$proyek->id)) border-green-500 @else border-red-500 @endif">
						<a href="{{ route('proyek.show', $proyek->id) }}" class="">
						<div class="flex flex-col w-full p-2">
							<span
								class="self-start my-1 {{$proyek->selesai ? 'bg-green-200 text-green-600 border-green-600 ' : 'bg-yellow-200 text-yellow-600 border-yellow-600 '}} rounded-full border px-1.5 text-xs">
								{{ $proyek->selesai ? 'Selesai' : 'Masih Berlangsung'}}
							</span>
							<p class="font-medium my-1 line-clamp-2">{{ucfirst($proyek->nama_proyek)}} </p>
							<div class="flex items-center">
								<svg class="fill-blue-600 h-[1.1em] w-auto mr-1" width="24" height="26" viewBox="0 0 24 26" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12.028 11.993C15.309 11.993 17.9687 9.30826 17.9687 5.99648C17.9687 2.6847 15.309 0 12.028 0C8.74711 0 6.08739 2.6847 6.08739 5.99648C6.08739 9.30826 8.74711 11.993 12.028 11.993ZM1.4479 18.1268C3.47951 14.4282 7.16181 13.1679 8.749 13V16.6069C8.749 16.8841 8.81137 17.1578 8.93142 17.4071L10.3135 20.2782L10.8601 20.63L10.5753 18.576L11.6102 16.636C11.6066 16.6335 11.6031 16.631 11.5997 16.6284C11.5834 16.6166 11.5676 16.6035 11.5526 16.5894L10.9492 16.0239C10.7367 15.8247 10.7367 15.4852 10.9492 15.286L11.5526 14.7205C11.744 14.5411 12.04 14.5411 12.2314 14.7205L12.8347 15.286C13.0473 15.4852 13.0473 15.8247 12.8347 16.0239L12.2314 16.5894C12.2131 16.6065 12.1938 16.6221 12.1738 16.636L13.2087 18.576L12.9156 20.6894L13.4722 20.3697L14.8687 17.6029C14.9986 17.3455 15.0664 17.0607 15.0664 16.7717V13C16.6536 13.1679 20.5033 14.4282 22.5349 18.1268C23.4079 19.716 23.807 21.3932 23.9782 22.7893C24.2023 24.6161 22.6664 26 20.8427 26H3.15101C1.34221 26 -0.187285 24.6373 0.0186429 22.8234C0.1779 21.4206 0.567807 19.729 1.4479 18.1268Z"/>
								</svg>
								<p class="font-normal text-sm mb-2 line-clamp-1 text-gray-700">
									{{ucfirst($proyek->siteManager->nama)}}
								</p>
							</div>
							<div class="flex items-center">
								<img src="/images/ic_tujuan.png" alt="" class="h-[1.1em] w-auto mr-1">
								<p class="font-normal text-sm mb-2 line-clamp-2 text-gray-700">{{ucfirst($proyek->alamat)}}</p>
							</div>
							<p class="mb-2 text-xs font-normal text-gray-500">{{ \App\Helpers\Date::parseMilliseconds($proyek->created_at) }} @if ($proyek->tgl_selesai)- {{ \App\Helpers\Date::parseMilliseconds($proyek->tgl_selesai) }}@endif</p>
						</a>
						<div class="relative hidden justify-center items-center group-hover:flex w-full h-full">
							<div class="absolute w-full z-10  h-auto bg-white flex top-0 px-2 left-0 rounded-b-xl pt-0 pb-2 shadow-md">
								<form action="{{ route('proyek.destroy', $proyek->id) }}" method="POST">
									@csrf
									<button type="submit"
										class="show_confirm  bg-white rounded-md border border-red-500 py-1 px-3 text-sm text-red-500"
										data-name="{{ $proyek->nama_proyek }}">Hapus</button>
								</form>
								<a href="{{ route('proyek.edit', $proyek->id) }}"
									class="bg-primary ml-2 rounded-md py-1 px-3 text-sm text-white self-start">Edit</a>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		@else
			@if (request('search'))
				<h1 class="mb-2 text-center font-medium text-md text-red-600">Tidak ada Proyek {{request('search')}}</h1>
			@else
				<h1 class="mb-2 text-center font-medium text-md text-red-600">Belum ada Proyek</h1>
			@endif
		@endif
	<div class="mt-5">
		{{ $proyeks->links() }}
	</div>
</div>
@endsection

@push('prepend-script')
	@include('includes.sweetalert')
	@include('includes.jquery')
	<script>
		$('.show_confirm').click(function(event) {
				var form =  $(this).closest("form");
				var nama = $(this).data("name");
				event.preventDefault();
				Swal.fire({
						title: "Apakah kamu yakin?",
						html: `Proyek yang dihapus tidak dapat dikembalikan, ingin menghapus proyek <b>${nama}</b>`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Hapus Proyek',
						cancelButtonText: 'Batalkan',
						confirmButtonColor: '#3085d6',
  					cancelButtonColor: '#d33'
				}).then((result) => {
					if (result.isConfirmed) {
						form.submit();
					}
				})
		});

		$('.delete_search').click(function(e){
			$( "#searchbox" ).val('');
			$("#form").submit();
		})
		
	</script>
@endpush
