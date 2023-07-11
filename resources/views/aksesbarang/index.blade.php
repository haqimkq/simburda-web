@extends('layouts.app')
@section('content')
<div class="md:ml-[16em] w-full relative">
	<div class="w-full pl-4">
		@if (session('successUpdateAkses'))
			@section('alertMessage', session('successUpdateAkses'))
			@include('shared.alerts.success')
		@endif
		@if (session('deleteProyekSuccess'))
				@section('alertMessage', session('deleteProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@section('headerName', 'Akses Barang')
			@section('role', \App\Helpers\Utils::underscoreToNormal($authUser->role))
			@if ($authUser->foto)
			@section('foto', asset($authUser->foto))
			@endif
			@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
			@include('includes.header')
			<div class="my-5 flex w-full items-center justify-items-center">
				<div class="w-full flex">
				{{-- @section('last-search')
				<a href="{{ route('akses-barang.create') }}" class="button-custom !w-auto px-5 !h-auto">
					+ Tambah Akses Barang
				</a>
				@endsection --}}
				@section('placeholderSearch', 'Cari Nama Proyek') @section('action', '/akses-barang') 
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
							<option value="akses-belum-ditentukan"  @if(request('filter') == 'akses-belum-ditentukan') selected @endif>Akses Belum Ditentukan</option>
							<option value='semua-akses'  @if(request('filter') == 'semua-akses') selected @endif>Semua Akses</option>
							<option value='disetujui-admin-dan-sm'  @if(request('filter') == 'disetujui-admin-dan-sm') selected @endif>Disetujui Admin Gudang dan SM</option>
							<option value="disetujui-sm"  @if(request('filter') == 'disetujui-sm') selected @endif>Disetujui SM</option>
							<option value="ditolak-sm"  @if(request('filter') == 'ditolak-sm') selected @endif>Ditolak SM</option>
							<option value="akses-belum-ditentukan-sm"  @if(request('filter') == 'akses-belum-ditentukan-sm') selected @endif>SM Belum Menentukan Akses</option>
							<option value='disetujui-admin' @if(request('filter') == 'disetujui-admin') selected @endif>Disetujui Admin Gudang</option>
							<option value="ditolak-admin"  @if(request('filter') == 'ditolak-admin') selected @endif>Ditolak Admin Gudang</option>
							<option value="akses-belum-ditentukan-admin"  @if(request('filter') == 'akses-belum-ditentukan-admin') selected @endif>Admin Gudang Belum Menentukan Akses</option>
						</select>
					</div>
				@endsection
				@include('shared.search')
			</div>
		</div>
		@if (!$aksesBarangs->isEmpty())
				@if (request('search'))
				<div class="flex items-center">
					<button class="bg-red-600 py-1 px-2 mb-2 mr-2 text-center font-normal text-sm delete_search text-white rounded-md" onclick="">Hapus pencarian</button>
					<h1 class="mb-2 text-center font-medium text-md">Hasil Pencarian Akses Barang {{request('search')}}</h1>
				</div>
				@endif
				@php $nama_proyek_before = NULL @endphp
				<div class="flex items-center font-normal text-sm text-gray-600">
					<div class="flex mr-2">
						<svg class="w-4 h-4 mr-1 fill-green" width="25" height="29" viewBox="0 0 25 29" xmlns="http://www.w3.org/2000/svg">
							<path d="M14.5966 0.394986L14.2487 3.19271C14.4126 3.24282 14.5735 3.2999 14.7311 3.36362L15.4068 0.796254C17.0362 1.77434 18.1337 3.5574 18.1583 5.60052C18.5889 5.79392 18.8315 6.0286 18.8315 6.30606C18.8315 6.70076 18.6063 6.97699 18.2046 7.1702C18.3763 7.73198 18.4687 8.32896 18.4687 8.94779C18.4687 12.2676 15.809 14.9589 12.528 14.9589C9.24711 14.9589 6.58739 12.2676 6.58739 8.94779C6.58739 8.32896 6.6798 7.73198 6.85143 7.1702C6.4498 6.97699 6.2246 6.70076 6.2246 6.30606C6.2246 6.02877 6.46681 5.79383 6.89697 5.60052C6.89748 5.55803 6.89847 5.51567 6.8999 5.47344C6.90282 5.38682 6.90768 5.30074 6.91443 5.21516C7.06139 3.3528 8.10189 1.74384 9.60414 0.823115L10.2778 3.38292C10.4343 3.31804 10.5942 3.25973 10.7571 3.20832L10.4097 0.41474C10.4747 0.388126 10.5404 0.362711 10.6066 0.338536C11.2061 0.119471 11.853 0 12.5276 0C13.1305 0 13.7111 0.0953786 14.2557 0.272002C14.371 0.309402 14.4847 0.350438 14.5966 0.394986ZM1.9479 21.1076C3.97951 17.4 7.66181 16.1366 9.249 15.9684V19.584C9.249 19.8619 9.31138 20.1362 9.43142 20.3862L10.8135 23.2642L11.3601 23.6169L11.0753 21.5579L12.1075 19.6181L11.4492 18.9996C11.2367 18.7999 11.2367 18.4596 11.4492 18.2599L12.392 17.3741L13.3347 18.2599C13.5473 18.4596 13.5473 18.7999 13.3347 18.9996L12.6765 19.6181L13.7087 21.5579L13.4156 23.6764L13.9722 23.356L15.3687 20.5825C15.4986 20.3244 15.5664 20.0389 15.5664 19.7493V15.9684C17.1536 16.1366 21.0033 17.4 23.0349 21.1076C23.9079 22.7007 24.307 24.382 24.4782 25.7815C24.7023 27.6128 23.1664 29 21.3427 29H3.65101C1.84221 29 0.312714 27.634 0.518643 25.8156C0.677901 24.4094 1.06781 22.7137 1.9479 21.1076Z"/>
						</svg>
						<p class="">Supervisor (Peminta Akses)</p>
					</div>
					<div class="flex">
						<svg class="fill-blue-600 h-4 w-4 mr-1" width="24" height="26" viewBox="0 0 24 26" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M12.028 11.993C15.309 11.993 17.9687 9.30826 17.9687 5.99648C17.9687 2.6847 15.309 0 12.028 0C8.74711 0 6.08739 2.6847 6.08739 5.99648C6.08739 9.30826 8.74711 11.993 12.028 11.993ZM1.4479 18.1268C3.47951 14.4282 7.16181 13.1679 8.749 13V16.6069C8.749 16.8841 8.81137 17.1578 8.93142 17.4071L10.3135 20.2782L10.8601 20.63L10.5753 18.576L11.6102 16.636C11.6066 16.6335 11.6031 16.631 11.5997 16.6284C11.5834 16.6166 11.5676 16.6035 11.5526 16.5894L10.9492 16.0239C10.7367 15.8247 10.7367 15.4852 10.9492 15.286L11.5526 14.7205C11.744 14.5411 12.04 14.5411 12.2314 14.7205L12.8347 15.286C13.0473 15.4852 13.0473 15.8247 12.8347 16.0239L12.2314 16.5894C12.2131 16.6065 12.1938 16.6221 12.1738 16.636L13.2087 18.576L12.9156 20.6894L13.4722 20.3697L14.8687 17.6029C14.9986 17.3455 15.0664 17.0607 15.0664 16.7717V13C16.6536 13.1679 20.5033 14.4282 22.5349 18.1268C23.4079 19.716 23.807 21.3932 23.9782 22.7893C24.2023 24.6161 22.6664 26 20.8427 26H3.15101C1.34221 26 -0.187285 24.6373 0.0186429 22.8234C0.1779 21.4206 0.567807 19.729 1.4479 18.1268Z"/>
						</svg>
						<p class="">Project Manager</p>
					</div>
				</div>
				@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
				<div class="flex items-center mt-3 ">
					<input type="checkbox" id="selectAllProyek" class="cursor-pointer selectAllProyek rounded-md border-green border w-5 h-5 focus:ring-green checked:bg-green mr-2">
					<label  for="selectAllProyek" class="cursor-pointer">Pilih Semua Proyek</label>
				</div>
				@endcanany
				@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
				<form action="{{route('akses-barang.store')}}" method="POST" id="" class="formPemberianAkses">
					@csrf
				@endcanany
				<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-2">
				@foreach ($aksesBarangs as $aksesbarang)
					@if (!isset($nama_proyek_before) || $aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->nama_proyek != $nama_proyek_before)
						@php $nama_proyek_before = $aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->nama_proyek @endphp
						<div class="flex flex-col mt-5 lg:col-span-2 xl:col-span-3">
							@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
							<div class="flex items-center">
									@if (!$aksesbarang->disetujui_sm || !$aksesbarang->disetujui_admin)
									<input id="{{$aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->id}}" type="checkbox" data-id-proyek-peminjaman="{{$aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->id}}" class="cursor-pointer selectProyek rounded-md border-green border w-5 h-5 focus:ring-green checked:bg-green mr-2">
									@endif
									<label for="{{$aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->id}}" class="cursor-pointer text-xl font-semibold line-clamp-1">{{ucfirst($aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->nama_proyek)}}</label>
								</div>
							@endcanany
							<div class="mt-1 flex sm:flex-col lg:flex lg:flex-row">
								<div class="flex">
									<svg class="fill-blue-600 h-4 w-4 mr-1" width="24" height="26" viewBox="0 0 24 26" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M12.028 11.993C15.309 11.993 17.9687 9.30826 17.9687 5.99648C17.9687 2.6847 15.309 0 12.028 0C8.74711 0 6.08739 2.6847 6.08739 5.99648C6.08739 9.30826 8.74711 11.993 12.028 11.993ZM1.4479 18.1268C3.47951 14.4282 7.16181 13.1679 8.749 13V16.6069C8.749 16.8841 8.81137 17.1578 8.93142 17.4071L10.3135 20.2782L10.8601 20.63L10.5753 18.576L11.6102 16.636C11.6066 16.6335 11.6031 16.631 11.5997 16.6284C11.5834 16.6166 11.5676 16.6035 11.5526 16.5894L10.9492 16.0239C10.7367 15.8247 10.7367 15.4852 10.9492 15.286L11.5526 14.7205C11.744 14.5411 12.04 14.5411 12.2314 14.7205L12.8347 15.286C13.0473 15.4852 13.0473 15.8247 12.8347 16.0239L12.2314 16.5894C12.2131 16.6065 12.1938 16.6221 12.1738 16.636L13.2087 18.576L12.9156 20.6894L13.4722 20.3697L14.8687 17.6029C14.9986 17.3455 15.0664 17.0607 15.0664 16.7717V13C16.6536 13.1679 20.5033 14.4282 22.5349 18.1268C23.4079 19.716 23.807 21.3932 23.9782 22.7893C24.2023 24.6161 22.6664 26 20.8427 26H3.15101C1.34221 26 -0.187285 24.6373 0.0186429 22.8234C0.1779 21.4206 0.567807 19.729 1.4479 18.1268Z"/>
									</svg>
									<p class="font-normal text-sm text-gray-700">
										{{ucfirst($aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->setManager->nama)}}
									</p>
								</div>
								<div class="flex">
									<svg class="lg:ml-2 mr-1 h-4 w-4 fill-blue-600" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
									<path  fill-rule="evenodd" clip-rule="evenodd" d="M9.36364 0C5.29681 0 2 3.29681 2 7.36364C2 11.4305 9.36364 18 9.36364 18C9.36364 18 16.7273 11.4305 16.7273 7.36364C16.7273 3.29681 13.4305 0 9.36364 0ZM9.36364 9.81818C10.7192 9.81818 11.8182 8.71924 11.8182 7.36364C11.8182 6.00803 10.7192 4.90909 9.36364 4.90909C8.00803 4.90909 6.90909 6.00803 6.90909 7.36364C6.90909 8.71924 8.00803 9.81818 9.36364 9.81818Z"/>
								</svg>
								<p class="font-normal text-sm mb-2 text-gray-700">{{ucfirst($aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->alamat)}}</p>
								</div>
							</div>
						</div>
					@endif
					<a href="{{ route('akses-barang.show', $aksesbarang->id) }}" class="p-2 group flex flex-col shadow-md shadow-gray-100 rounded-xl hover:rounded-b-none">
						<div class="flex flex-col p-2">
							<div class="flex items-center mb-2">
									@if (isset($aksesbarang->disetujui_sm))
										@if ($aksesbarang->disetujui_sm)
											<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-green-600 mr-1">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M22 12.5C22 17.7467 17.7467 22 12.5 22C7.25329 22 3 17.7467 3 12.5C3 7.25329 7.25329 3 12.5 3C17.7467 3 22 7.25329 22 12.5ZM25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5ZM18.5607 11.0607C19.1464 10.4749 19.1464 9.52513 18.5607 8.93934C17.9749 8.35355 17.0251 8.35355 16.4393 8.93934L11.4215 13.9571L8.97619 11.8611C8.3472 11.322 7.40025 11.3948 6.86111 12.0238C6.32198 12.6528 6.39482 13.5998 7.02381 14.1389L10.5238 17.1389C11.1189 17.649 12.0064 17.6149 12.5607 17.0607L18.5607 11.0607Z"/>
											</svg>
										@else
											<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-red-600 mr-1">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 22C17.7467 22 22 17.7467 22 12.5C22 7.25329 17.7467 3 12.5 3C7.25329 3 3 7.25329 3 12.5C3 17.7467 7.25329 22 12.5 22ZM12.5 25C19.4036 25 25 19.4036 25 12.5C25 5.59644 19.4036 0 12.5 0C5.59644 0 0 5.59644 0 12.5C0 19.4036 5.59644 25 12.5 25ZM8.25733 17.2426C7.67155 16.6569 7.67155 15.7071 8.25733 15.1213L10.3787 13L8.25733 10.8787C7.67155 10.2929 7.67155 9.34314 8.25733 8.75736C8.84312 8.17157 9.79287 8.17157 10.3787 8.75736L12.5 10.8787L14.6213 8.75736C15.2071 8.17158 16.1568 8.17158 16.7426 8.75736C17.3284 9.34315 17.3284 10.2929 16.7426 10.8787L14.6213 13L16.7426 15.1213C17.3284 15.7071 17.3284 16.6569 16.7426 17.2426C16.1568 17.8284 15.2071 17.8284 14.6213 17.2426L12.5 15.1213L10.3787 17.2426C9.79287 17.8284 8.84312 17.8284 8.25733 17.2426Z"/>
											</svg>
										@endif
										<p class="text-xs font-normal {{$aksesbarang->disetujui_sm ? 'text-green-600' : 'text-red-600'}} mr-3">{{ ($aksesbarang->disetujui_sm) ? 'SM' : 'SM' }}</p>
									@else
										<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"  class="w-3 h-3 fill-gray-500 mr-1">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M22 12.5C22 17.7467 17.7467 22 12.5 22C7.25329 22 3 17.7467 3 12.5C3 7.25329 7.25329 3 12.5 3C17.7467 3 22 7.25329 22 12.5ZM25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5ZM8 11.5C7.17157 11.5 6.5 12.1716 6.5 13C6.5 13.8284 7.17157 14.5 8 14.5H17C17.8284 14.5 18.5 13.8284 18.5 13C18.5 12.1716 17.8284 11.5 17 11.5H8Z"/>
										</svg>
										<p class="text-xs font-normal text-gray-500 mr-3">SM</p>
									@endif
									@if (isset($aksesbarang->disetujui_admin))
										@if ($aksesbarang->disetujui_admin)
											<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-green-600 mr-1">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M22 12.5C22 17.7467 17.7467 22 12.5 22C7.25329 22 3 17.7467 3 12.5C3 7.25329 7.25329 3 12.5 3C17.7467 3 22 7.25329 22 12.5ZM25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5ZM18.5607 11.0607C19.1464 10.4749 19.1464 9.52513 18.5607 8.93934C17.9749 8.35355 17.0251 8.35355 16.4393 8.93934L11.4215 13.9571L8.97619 11.8611C8.3472 11.322 7.40025 11.3948 6.86111 12.0238C6.32198 12.6528 6.39482 13.5998 7.02381 14.1389L10.5238 17.1389C11.1189 17.649 12.0064 17.6149 12.5607 17.0607L18.5607 11.0607Z"/>
											</svg>
										@else
											<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-red-600 mr-1">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 22C17.7467 22 22 17.7467 22 12.5C22 7.25329 17.7467 3 12.5 3C7.25329 3 3 7.25329 3 12.5C3 17.7467 7.25329 22 12.5 22ZM12.5 25C19.4036 25 25 19.4036 25 12.5C25 5.59644 19.4036 0 12.5 0C5.59644 0 0 5.59644 0 12.5C0 19.4036 5.59644 25 12.5 25ZM8.25733 17.2426C7.67155 16.6569 7.67155 15.7071 8.25733 15.1213L10.3787 13L8.25733 10.8787C7.67155 10.2929 7.67155 9.34314 8.25733 8.75736C8.84312 8.17157 9.79287 8.17157 10.3787 8.75736L12.5 10.8787L14.6213 8.75736C15.2071 8.17158 16.1568 8.17158 16.7426 8.75736C17.3284 9.34315 17.3284 10.2929 16.7426 10.8787L14.6213 13L16.7426 15.1213C17.3284 15.7071 17.3284 16.6569 16.7426 17.2426C16.1568 17.8284 15.2071 17.8284 14.6213 17.2426L12.5 15.1213L10.3787 17.2426C9.79287 17.8284 8.84312 17.8284 8.25733 17.2426Z"/>
											</svg>
										@endif
										<p class="text-xs font-normal {{$aksesbarang->disetujui_admin ? 'text-green-600' : 'text-red-600'}} mr-3">{{ ($aksesbarang->disetujui_admin) ? 'Admin Gudang' : 'Admin Gudang' }}</p>
									@else
										<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"  class="w-3 h-3 fill-gray-500 mr-1">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M22 12.5C22 17.7467 17.7467 22 12.5 22C7.25329 22 3 17.7467 3 12.5C3 7.25329 7.25329 3 12.5 3C17.7467 3 22 7.25329 22 12.5ZM25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5ZM8 11.5C7.17157 11.5 6.5 12.1716 6.5 13C6.5 13.8284 7.17157 14.5 8 14.5H17C17.8284 14.5 18.5 13.8284 18.5 13C18.5 12.1716 17.8284 11.5 17 11.5H8Z"/>
										</svg>
										<p class="text-xs font-normal text-gray-500 mr-3">Admin Gudang</p>
									@endif
								</div>
								<div class="flex items-center">
									@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
									@if (!$aksesbarang->disetujui_sm || !$aksesbarang->disetujui_admin)
									<input type="checkbox" name="id[]" data-id-proyek-peminjaman="{{$aksesbarang->peminjamanDetail->peminjaman->menangani->proyek->id}}" value="{{$aksesbarang->id}}" class="cursor-pointer rounded-md border-green border w-5 h-5 focus:ring-green checked:bg-green mr-2">
									@endif
									@endcanany
									{{-- <img  src="{{asset($aksesbarang->peminjamanDetail->peminjaman->barang->gambar)}}" alt="" class="w-20 h-20 object-fit object-center rounded-md mr-2"> --}}
									<div class="flex flex-col">
											{{-- <p class="text-xs font-normal text-gray-500">{{ ($aksesbarang->disetujui_admin) 'Disetujui Admin Gudang' }}</p> --}}
										<div class=" h-[5em] w-[5em] rounded-md bg-cover"
					style="background-image: url('{{ asset($aksesbarang->peminjamanDetail->barang->barang->gambar) }}')"></div>
										<p class="font-medium mb-1 line-clamp-2">{{$aksesbarang->peminjamanDetail->barang->barang->nama }} </p>
										{{-- <p class="font-medium mb-1 line-clamp-2">#{{$aksesbarang->peminjamanDetail->peminjaman->barang->nomor_seri }} {{ucfirst($aksesbarang->peminjamanDetail->peminjaman->barang->nama)}} </p> --}}
										<div class="flex">
											<svg class="w-4 h-4 mr-1 fill-green" width="25" height="29" viewBox="0 0 25 29" xmlns="http://www.w3.org/2000/svg">
												<path d="M14.5966 0.394986L14.2487 3.19271C14.4126 3.24282 14.5735 3.2999 14.7311 3.36362L15.4068 0.796254C17.0362 1.77434 18.1337 3.5574 18.1583 5.60052C18.5889 5.79392 18.8315 6.0286 18.8315 6.30606C18.8315 6.70076 18.6063 6.97699 18.2046 7.1702C18.3763 7.73198 18.4687 8.32896 18.4687 8.94779C18.4687 12.2676 15.809 14.9589 12.528 14.9589C9.24711 14.9589 6.58739 12.2676 6.58739 8.94779C6.58739 8.32896 6.6798 7.73198 6.85143 7.1702C6.4498 6.97699 6.2246 6.70076 6.2246 6.30606C6.2246 6.02877 6.46681 5.79383 6.89697 5.60052C6.89748 5.55803 6.89847 5.51567 6.8999 5.47344C6.90282 5.38682 6.90768 5.30074 6.91443 5.21516C7.06139 3.3528 8.10189 1.74384 9.60414 0.823115L10.2778 3.38292C10.4343 3.31804 10.5942 3.25973 10.7571 3.20832L10.4097 0.41474C10.4747 0.388126 10.5404 0.362711 10.6066 0.338536C11.2061 0.119471 11.853 0 12.5276 0C13.1305 0 13.7111 0.0953786 14.2557 0.272002C14.371 0.309402 14.4847 0.350438 14.5966 0.394986ZM1.9479 21.1076C3.97951 17.4 7.66181 16.1366 9.249 15.9684V19.584C9.249 19.8619 9.31138 20.1362 9.43142 20.3862L10.8135 23.2642L11.3601 23.6169L11.0753 21.5579L12.1075 19.6181L11.4492 18.9996C11.2367 18.7999 11.2367 18.4596 11.4492 18.2599L12.392 17.3741L13.3347 18.2599C13.5473 18.4596 13.5473 18.7999 13.3347 18.9996L12.6765 19.6181L13.7087 21.5579L13.4156 23.6764L13.9722 23.356L15.3687 20.5825C15.4986 20.3244 15.5664 20.0389 15.5664 19.7493V15.9684C17.1536 16.1366 21.0033 17.4 23.0349 21.1076C23.9079 22.7007 24.307 24.382 24.4782 25.7815C24.7023 27.6128 23.1664 29 21.3427 29H3.65101C1.84221 29 0.312714 27.634 0.518643 25.8156C0.677901 24.4094 1.06781 22.7137 1.9479 21.1076Z"/>
											</svg>
											<p class="font-normal line-clamp-1 text-sm">{{ucfirst($aksesbarang->peminjamanDetail->peminjaman->menangani->user->nama)}} </p>
										</div>
										<p class="mt-2 text-xs font-normal">{{ \App\Helpers\Date::parseMilliseconds($aksesbarang->peminjamanDetail->peminjaman->tgl_peminjaman) }} s/d {{ \App\Helpers\Date::parseMilliseconds($aksesbarang->peminjamanDetail->peminjaman->tgl_berakhir) }}</p>
									</div>
								</div>
						</div>
					</a>
				@endforeach
				@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
				<input type="radio" name="akses" id="setujui" value="setujui" class="hidden">
				<input type="radio" name="akses" id="tolak" value="tolak" class="hidden">
				@endcanany
				</form>
			</div>
		@else
			@if (request('search'))
				<h1 class="mb-2 text-center font-medium text-md text-red-600">Tidak ada Akses Barang {{request('search')}}</h1>
			@else
				<h1 class="mb-2 text-center font-medium text-md text-red-600">Belum ada Akses Barang</h1>
			@endif
		@endif
	<div class="mt-5 mb-20">
		{{ $aksesBarangs->links() }}
	</div>
	@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
	<div class="z-50 bg-white shadow-md bottom-0 right-0 fixed m-4 p-4 items-end justify-end rounded-lg w-auto flex-col buttonAjukanAksesBarang hidden">
		<p class="w-full mb-3 text-md font-semibold"><span class="jumlahBarang ">0</span> Akses Barang Dipilih</p>
		<div class="flex">
			<button type="submit" class="border-2 border-red-600 py-2 px-3 text-red-600 rounded-md buttonTolak">Tolak</button>
			<button type="submit" class="bg-green py-2 px-3 text-white rounded-md ml-3 buttonSetujui">Setujui</button>
		</div>
	</div>
	@endcanany
</div>
@endsection

@push('prepend-script')
	@include('includes.jquery')
	<script>
		$('.delete_search').click(function(e){
			$( "#searchbox" ).val('');
			$("#form").submit();
		})

		@canany(['ADMIN', 'ADMIN_GUDANG', 'SET_MANAGER'])
		$('input:checkbox').change(function(){
			var countCheckedValue = $('input[name="id[]"]:checked').length;
			if(countCheckedValue>0){
				$('.buttonAjukanAksesBarang').removeClass('hidden');
				$('.buttonAjukanAksesBarang').addClass('flex');
			}else{
				$('.buttonAjukanAksesBarang').removeClass('flex');
				$('.buttonAjukanAksesBarang').addClass('hidden');
			}
		})
		// Ubah jumlah barang ketika input dengan type checkbox dan atribut name id[] ada perubahan 
		$('input[name="id[]"]:checkbox').change(function(){
			var idProyekPeminjaman = $(this).data("idProyekPeminjaman");
			var countCheckedValue = $('input[name="id[]"]:checked').length;
			$('.jumlahBarang').text(countCheckedValue);

			// centang select all ketika semua data pada id yang sama telah dicentang dan sebaliknya
			var countPeminjamanWithProyekId = $(`input[name="id[]"][data-id-proyek-peminjaman="${idProyekPeminjaman}"]`).length;
			var countIsCheckedPeminjamanWithProyekId = $(`input[name="id[]"][data-id-proyek-peminjaman="${idProyekPeminjaman}"]:checked`).length;
			if(countPeminjamanWithProyekId == countIsCheckedPeminjamanWithProyekId) 
				$(`input[data-id-proyek-peminjaman="${idProyekPeminjaman}"]:checkbox.selectProyek`).prop('checked', true);
			else
				$(`input[data-id-proyek-peminjaman="${idProyekPeminjaman}"]:checkbox.selectProyek`).prop('checked', false);
			isSelectAllProyekChecked()
    });

		$('.selectProyek').click(function(){
			var idProyekPeminjaman = $(this).data("idProyekPeminjaman");
			$(`input[data-id-proyek-peminjaman="${idProyekPeminjaman}"]:checkbox`).prop('checked', this.checked);
			var countCheckedValue = $('input[name="id[]"]:checked').length;
			$('.jumlahBarang').text(countCheckedValue);
			isSelectAllProyekChecked()
		});

		$('.selectAllProyek').click(function(){
			$(`input:checkbox`).prop('checked', this.checked);
			var countCheckedValue = $('input[name="id[]"]:checked').length;
			$('.jumlahBarang').text(countCheckedValue);
		});
		$('.buttonSetujui').click(function(){
			$('input[name="akses"][value="setujui"]:radio').prop('checked', true);
			$('.formPemberianAkses').submit();
		})
		$('.buttonTolak').click(function(){
			$('input[name="akses"][value="tolak"]:radio').prop('checked', true);
			$('.formPemberianAkses').submit();
		})

		function isSelectAllProyekChecked(){
			var allPeminjamanCheckbox = $(`input[name="id[]"]`).length;
			var peminjamanCheckboxChecked = $(`input[name="id[]"]:checked`).length;
			if(allPeminjamanCheckbox == peminjamanCheckboxChecked) 
				$(`input:checkbox.selectAllProyek`).prop('checked', true);
			else
				$(`input:checkbox.selectAllProyek`).prop('checked', false);
		}
		@endcanany
	</script>
@endpush
