@extends('layouts.app')
@section('content')
	<div class="w-full md:ml-[16em]">
		<div class="w-full">
			@if (session('createBarangSuccess'))
				@section('alertMessage', session('createBarangSuccess'))
				@include('shared.alerts.success')
			@endif
			@if (session('deleteBarangSuccess'))
				@section('alertMessage', session('deleteBarangSuccess'))
				@include('shared.alerts.success')
			@endif
			@section('headerName', 'Barang')
			@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
			@if ($authUser->foto)
				@section('foto', asset($authUser->foto))
			@endif
			@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
			@include('includes.header')
			<div class="my-5 flex w-full items-center justify-items-center">
				<div class="flex w-full">
					@section('last-search')
						<a href="{{ route('barang.create') }}" class="button-custom !h-auto !w-auto px-5">
							+ Tambah Barang
						</a>
					@endsection
					@section('placeholderSearch', 'Cari Barang') @section('action', '/barang')
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
								<option value="jumlah tersedikit" @if (request('orderBy') == 'jumlah tersedikit') selected @endif>Jumlah Tersedikit</option>
								<option value="jumlah terbanyak" @if (request('orderBy') == 'jumlah terbanyak') selected @endif>Jumlah Terbanyak</option>
							</select>
						</div>
						<div class="flex w-full flex-col">
							<label for="filterJenis" class="mb-1 block text-sm font-normal text-gray-700">Filter Jenis</label>
							<select name="filter" id="filterJenis" onchange="this.form.submit()"
								class="focus:ring-green dark: dark:focus:ring-green block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
								<option value="semua jenis" @if (request('filter') == 'semua jenis') selected @endif>Semua Jenis</option>
								<option value="habis pakai" @if (request('filter') == 'habis pakai') selected @endif>Habis Pakai</option>
								<option value="tidak habis pakai" @if (request('filter') == 'tidak habis pakai') selected @endif>Tidak Habis Pakai</option>
							</select>
						</div>
					@endsection
					@include('shared.search')
				</div>
			</div>
			@if(!$barangs->isEmpty())
				@if (request('search'))
					<div class="flex items-center mb-5">
						<button class="bg-red-600 py-1 px-2 mr-2 text-center font-normal text-sm delete_search text-white rounded-md" onclick="">Hapus pencarian</button>
						<h1 class="text-center font-normal text-md">Hasil Pencarian Barang "{{request('search')}}"</h1>
					</div>
				@endif
				<div class="mb-2 flex items-center">
					<div class="all-status flex items-center">
						<div class="border-green mr-1 h-5 w-5 rounded-full border"></div>
						<p class="text-sm">Total Barang</p>
					</div>
					<div class="borrow-status ml-2 flex items-center">
						<div class="mr-1 h-5 w-5 rounded-full border border-orange-500"></div>
						<p class="text-sm">Jumlah Barang Digunakan</p>
					</div>
				</div>
				<div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
					@foreach ($barangs as $barang)
					<div class="relative group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
						<a href="{{ route('barang.show',$barang->id) }}" class="flex p-2">
							@if (isset($barang->gambar))
									<div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
								style="background-image: url('{{ asset($barang->gambar) }}')"></div>
							@endif
							<div class="flex flex-col align-i">
								<span
									class="{{ $barang->jenis == 'TIDAK_HABIS_PAKAI' ? 'bg-green-200 text-green-600 border-green-600' : 'text-primary border-primary bg-primary-30' }} mb-2 self-start rounded-full border px-1.5 text-xs">
									{{ App\Helpers\Utils::underscoreToNormal($barang->jenis) }}
								</span>
								<p class="mb-2 font-medium line-clamp-1">{{ $barang->nama }}</p>
								<p class="mb-2 text-xs font-normal">{{ \App\Helpers\Date::parseMilliseconds($barang->created_at) }}</p>
								@php
									$jumlahBarang = \App\Models\Barang::where('nama', $barang->nama)->count();
									$barangTersedia = \App\Models\BarangTidakHabisPakai::where('barang_id',$barang->id)->whereNull('peminjaman_id')->count();
									$barangDipinjam = $jumlahBarang - $barangTersedia;
								@endphp
								<div class="flex items-center md:flex-col lg:flex-row">
									<p class="border-green mb-2 self-start rounded-full border px-2 py-1 text-xs text-black">
										{{ $jumlahBarang }} {{ $barang->satuan }}
									</p>
									@if($barangDipinjam>0)
									  <p class="mb-2 ml-2 self-start rounded-full border border-orange-500 px-2 py-1 text-xs text-black md:ml-0 lg:ml-2">{{$barangDipinjam}} {{$barang->satuan}}</p>
									@endif
								</div>
							</div>
						</a>
						<div class="relative hidden justify-center items-center group-hover:flex w-full h-full">
							<div class="absolute w-full z-10  h-auto bg-white flex top-0 px-2 left-0 rounded-b-xl pt-0 pb-2 shadow-md">
								<form action="{{ route('barang.destroy', $barang->id) }}" method="POST">
									@csrf
									<button type="submit"
										class="show_confirm  bg-white rounded-md border border-red-500 py-1 px-3 text-sm text-red-500"
										data-name="{{ $barang->nama }}">Hapus</button>
								</form>
								<a href="{{ route('barang.edit', $barang->id) }}"
									class="bg-primary ml-2 rounded-md py-1 px-3 text-sm text-white self-start">Edit</a>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				@else
				<div class="flex justify-center items-center text-center md:h-[65vh] mb-2 font-medium text-md text-red-600">
					@if (request('search'))
						<h1>Tidak ada Barang {{request('search')}}</h1>
					@else
						<h1>Belum ada Barang</h1>
					@endif
				</div>
				@endif
	<div class="mt-5">
		{{ $barangs->links() }}
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
						html: `Barang yang dihapus tidak dapat dikembalikan, ingin menghapus barang <b>${nama}</b>`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Hapus Barang',
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
