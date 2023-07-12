@extends('layouts.app')
@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
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
            @section('headerName', 'Perusahaan')
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
                {{-- @section('placeholderSearch', 'Cari Proyek') @section('action', '/proyek') --}}
                @include('shared.search')
            </div>
        </div>
        @if (!$perusahaans->isEmpty())
            @if (request('search'))
                <div class="flex items-center">
                    <button
                        class="bg-red-600 py-1 px-2 mb-2 mr-2 text-center font-normal text-sm delete_search text-white rounded-md"
                        onclick="">Hapus pencarian</button>
                    <h1 class="mb-2 text-center font-medium text-md">Hasil Pencarian Perusahaan {{ request('search') }}
                    </h1>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                @foreach ($perusahaans as $perusahaan)
                    <div class="group flex flex-col shadow-md shadow-gray-100 rounded-xl hover:rounded-b-none">
                        <a href="" class="flex p-2">
                            <div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
                                style="background-image: url('{{ asset($perusahaan->gambar) }}')"></div>
                            <div class="flex flex-col w-[20em]">
                                <p class="mb-2 font-medium line-clamp-1">{{ $perusahaan->nama }}</p>
								<div class="flex">
									{{-- <svg class="mr-1 h-4 w-4 fill-blue-600" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
										<path  fill-rule="evenodd" clip-rule="evenodd" d="M9.36364 0C5.29681 0 2 3.29681 2 7.36364C2 11.4305 9.36364 18 9.36364 18C9.36364 18 16.7273 11.4305 16.7273 7.36364C16.7273 3.29681 13.4305 0 9.36364 0ZM9.36364 9.81818C10.7192 9.81818 11.8182 8.71924 11.8182 7.36364C11.8182 6.00803 10.7192 4.90909 9.36364 4.90909C8.00803 4.90909 6.90909 6.00803 6.90909 7.36364C6.90909 8.71924 8.00803 9.81818 9.36364 9.81818Z"/>
									</svg> --}}
									<p class="font-normal text-sm mb-2 line-clamp-1 text-gray-700">{{ucfirst($perusahaan->alamat)}}</p>
								</div>
                                <p class="mb-2 text-xs font-normal">
                                    {{ \App\Helpers\Date::parseMilliseconds($perusahaan->created_at) }}</p>
                                <div class="flex items-center md:flex-col lg:flex-row">
                                </div>
                            </div>
                        </a>
                        <div class="relative hidden justify-center items-center group-hover:flex w-full h-full">
                            <div
                                class="absolute w-full z-10  h-auto bg-white flex top-0 px-2 left-0 rounded-b-xl pt-0 pb-2 shadow-md">
                                <form action="{{ route('proyek.destroy', $perusahaan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="show_confirm  bg-white rounded-md border border-red-500 py-1 px-3 text-sm text-red-500"
                                        data-name="{{ $perusahaan->nama }}">Hapus</button>
                                </form>
                                <a href="{{ route('proyek.edit', $perusahaan->id) }}"
                                    class="bg-primary ml-2 rounded-md py-1 px-3 text-sm text-white self-start">Edit</a>
                            </div>
                        </div>
                    </div>
				@endforeach
            </div>
    </div>
@else
    @if (request('search'))
        <h1 class="mb-2 text-center font-medium text-md text-red-600">Tidak ada Proyek {{ request('search') }}</h1>
    @else
        <h1 class="mb-2 text-center font-medium text-md text-red-600">Belum ada Proyek</h1>
    @endif
    @endif
</div>
@endsection

{{-- @push('prepend-script')
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
@endpush --}}
