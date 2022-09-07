@extends('layouts.app')
@section('content')
<div class="md:ml-[16em] w-full">
	<div class="w-full pl-4">
			@if (session('createProyekSuccess'))
				@section('alertMessage', session('createProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@if (session('deleteProyekSuccess'))
				@section('alertMessage', session('deleteProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@section('headerName', 'Proyek')
			@section('role', $authUser->role)
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
		@if (!$proyeks->isEmpty())
				@if (request('search'))
				<div class="flex items-center">
					<button class="bg-red-600 py-1 px-2 mb-2 mr-2 text-center font-normal text-sm delete_search text-white rounded-md" onclick="">Hapus pencarian</button>
					<h1 class="mb-2 text-center font-medium text-md">Hasil Pencarian Proyek {{request('search')}}</h1>
				</div>
				@endif
				<div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-5">
					@foreach ($proyeks as $proyek)
					<div class="group flex flex-col shadow-md shadow-gray-100 rounded-xl hover:rounded-b-none">
						<a href="{{ route('proyek.show', $proyek->id) }}" class=" p-2 ">
						<div class="flex flex-col w-full">
							<span
								class="self-start my-1 {{$proyek->selesai ? 'bg-green-200 text-green-600 border-green-600 ' : 'bg-yellow-200 text-yellow-600 border-yellow-600 '}} rounded-full border px-1.5 text-xs">
								{{ $proyek->selesai ? 'Selesai' : 'Masih Berlangsung'}}
							</span>
							<p class="font-medium my-1 line-clamp-2">{{ucfirst($proyek->nama_proyek)}} </p>
							<p class="font-normal text-sm mb-2 line-clamp-1 text-gray-700"><span class="inline-block font-semibold text-[0.6em] border  text-blue-600 border-blue-600 rounded-full px-1">PM</span> {{ucfirst($proyek->proyekManager->nama)}}</p>
							<div class="flex ">
								<svg class="mr-1 h-4 w-4 fill-blue-600" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
									<path  fill-rule="evenodd" clip-rule="evenodd" d="M9.36364 0C5.29681 0 2 3.29681 2 7.36364C2 11.4305 9.36364 18 9.36364 18C9.36364 18 16.7273 11.4305 16.7273 7.36364C16.7273 3.29681 13.4305 0 9.36364 0ZM9.36364 9.81818C10.7192 9.81818 11.8182 8.71924 11.8182 7.36364C11.8182 6.00803 10.7192 4.90909 9.36364 4.90909C8.00803 4.90909 6.90909 6.00803 6.90909 7.36364C6.90909 8.71924 8.00803 9.81818 9.36364 9.81818Z"/>
								</svg>
								<p class="font-normal text-sm mb-2 line-clamp-1 text-gray-700">{{ucfirst($proyek->alamat)}}</p>
							</div>
							<p class="mb-2 text-xs font-normal text-gray-500">{{ $proyek->created_at }} @if ($proyek->tggl_selesai)- {{ $proyek->tggl_selesai }}@endif</p>
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
