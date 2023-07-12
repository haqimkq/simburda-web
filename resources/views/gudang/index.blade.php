@extends('layouts.app')
@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
@section('content')
    <div class="md:ml-[16em] w-full">
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
                <div class="w-full flex">
                @section('last-search')
                    <a href="{{ route('gudang.create') }}" class="button-custom !w-auto px-5 !h-auto">
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
								class="focus:ring-green dark: dark:focus:ring-green block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
								<option value="terbaru" @if (request('orderBy') == 'terbaru') selected @endif>Terbaru</option>
								<option value="terlama" @if (request('orderBy') == 'terlama') selected @endif>Terlama</option>
							</select>
						</div>
						<div class="flex w-full flex-col">
							<label for="filterProvinsi" class="mb-1 block text-sm font-normal text-gray-700">Filter Provinsi</label>
							<select name="filter" id="filterProvinsi" onchange="this.form.submit()"
								class="focus:ring-green dark: dark:focus:ring-green block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
								<option value="semua provinsi" @if (request('filter') == "semua provinsi") selected @endif>Semua Provinsi</option>
								@foreach ($provinsis as $provinsi)
									<option value="{{ $provinsi->provinsi }}" @if (request('filter') == $provinsi->provinsi) selected @endif>{{ $provinsi->provinsi }}</option>
								@endforeach
							</select>
						</div>
					@endsection
                @include('shared.search')
            </div>
        </div>
        @if (!$gudangs->isEmpty())
            @if (request('search'))
                <div class="flex items-center mb-5">
                    <button class="bg-red-600 py-1 px-2 mr-2 text-center font-normal text-sm delete_search text-white rounded-md" onclick="">Hapus pencarian</button>
                    <h1 class="text-center font-normal text-md">Hasil Pencarian Gudang "{{request('search')}}"</h1>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                @foreach ($gudangs as $gudang)
                    <div class="group flex flex-col shadow-md shadow-gray-100 rounded-xl hover:rounded-b-none">
                        <a href="" class="flex flex-col p-2">
                            <div class="mr-2 h-[15em] w-full rounded-xl bg-cover md:w-full lg:h-[10em] lg:w-full"
                                style="background-image: url('{{ asset($gudang->gambar) }}')"></div>
                            <div class="flex flex-col">
                                <span
                                    class="bg-gray-200 text-gray-600 border-gray-600 my-2 self-start rounded-full border px-1.5 text-xs">
                                    {{ App\Helpers\Utils::underscoreToNormal($gudang->provinsi) }}
                                </span>
                                <p class="mb-2 font-medium line-clamp-1 md:line-clamp-2 xl:line-clamp-3">{{ $gudang->nama }}</p>
                                <p class="font-normal text-sm mb-2 line-clamp-1 md:line-clamp-2 xl:line-clamp-3 text-gray-700">{{ucfirst($gudang->alamat)}}</p>
                                <div class="flex flex-wrap">
																	@if (\App\Models\Gudang::getActiveDeliveryOrder($gudang->id)!=0)
                                    <div class="flex items-center md:flex-col lg:flex-row mr-2">
                                        <p class="border-green-600 mb-2 self-start rounded-full border px-2 py-1 text-xs text-gray-600 bg-green-200">
                                            {{ \App\Models\Gudang::getActiveDeliveryOrder($gudang->id) }}
                                            DO Aktif
                                        </p>
                                    </div>
                                @endif
																@if (\App\Models\Gudang::getActiveSjGp($gudang->id)!=0)
                                    <div class="flex items-center md:flex-col lg:flex-row mr-2">
                                        <p class="border-green-600 mb-2 self-start rounded-full border px-2 py-1 text-xs text-gray-600 bg-green-200">
                                            {{ \App\Models\Gudang::getActiveSjGp($gudang->id) }}
                                            SJGP Aktif
                                        </p>
                                    </div>
                                @endif
																@if (\App\Models\Gudang::getActiveSjPg($gudang->id)!=0)
                                    <div class="flex items-center md:flex-col lg:flex-row mr-2">
                                        <p class="border-green-600 mb-2 self-start rounded-full border px-2 py-1 text-xs text-gray-600 bg-green-200">
                                            {{ \App\Models\Gudang::getActiveSjPg($gudang->id) }}
                                            SJPG Aktif
                                        </p>
                                    </div>
                                @endif
																</div>
                            </div>
                        </a>
                        <div class="relative hidden justify-center items-center group-hover:flex w-full h-full">
                            <div
                                class="absolute w-full z-10  h-auto bg-white flex top-0 px-2 left-0 rounded-b-xl pt-0 pb-2 shadow-md">
                                <form action="{{ route('gudang.destroy', $gudang->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="show_confirm  bg-white rounded-md border border-red-500 py-1 px-3 text-sm text-red-500"
                                        data-name="{{ $gudang->nama }}">Hapus</button>
                                </form>
                                <a href="{{ route('gudang.edit', $gudang->id) }}"
                                    class="bg-primary ml-2 rounded-md py-1 px-3 text-sm text-white self-start">Edit</a>
                            </div>
                        </div>
                    </div>
				@endforeach
            </div>
    </div>
@else
    @if (request('search'))
        <h1 class="mb-2 text-center font-medium text-md text-red-600">Tidak ada Gudang {{ request('search') }}</h1>
    @else
        <h1 class="mb-2 text-center font-medium text-md text-red-600">Belum ada Gudang</h1>
    @endif
    @endif
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

		$('.delete_search').click(function(e){
			$("#searchbox" ).val('');
			$("#form").submit();
		})
		
	</script>
@endpush
