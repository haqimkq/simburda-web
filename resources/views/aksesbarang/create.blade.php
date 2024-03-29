@extends('layouts.create')
@push('addon-style')
@include('includes.jquery')
	{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@section('content')
<nav class="flex mt-5" aria-label="Breadcrumb">
		<ol class="inline-flex items-center space-x-1 md:space-x-3">
			<li class="inline-flex items-center">
				<a href="{{ route('home')}}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
					<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
					Home
				</a>
			</li>
			<li>
				<div class="flex items-center">
					<svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
					<a href="{{ route('proyek')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">Proyek</a>
				</div>
			</li>
			<li aria-current="page">
				<div class="flex items-center">
					<svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
					<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Tambah</span>
				</div>
			</li>
		</ol>
	</nav>
<h1 class="text-lg font-bold uppercase my-6 w-full text-center">Tambah Proyek</h1>
	<form method="POST" action="{{ route('proyek.store') }}">
		@csrf
		<div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
			<div>
				<label for="nama_proyek" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Proyek</label>
				<input type="text" id="nama_proyek" name="nama_proyek"
					value="{{ old('nama_proyek') }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
					placeholder="Masukkan Nama Proyek" required>
					@error('nama_proyek') @include('shared.errorText') @enderror
			</div>
			<div class="@can('admin') block @elsecan('project-manager') hidden @endcan">
				<label for="proyek_manager_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Proyek Manager</label>
				<select id="searchProyekManager" name="proyek_manager_id"
					class="searchProyekManager block w-full"
					required></select>
				@error('proyek_manager_id') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="latitude" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Latitude 
					<a class="inline-block cursor-pointer text-green hover:text-green font-normal rounded-lg text-sm" type="button" data-modal-toggle="latitude-longitde">
					Cara Mengambil Latitude</a>
				</label>
				<input type="number" id="latitude" name="latitude" step="any"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
					placeholder="-6.2501422" required value="{{ old('latitude') }}">
				@error('latitude') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="longitude" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Longitude
					<a class="inline-block cursor-pointer text-green hover:text-green font-normal rounded-lg text-sm" type="button" data-modal-toggle="latitude-longitde">
  				Cara Mengambil Longitude</a>
				</label>
				<input type="number" id="longitude" step="any" value="{{ old('longitude') }}" name="longitude"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
					placeholder="106.8543034" required>
				@error('longitude') @include('shared.errorText') @enderror
			</div>
			<div class="@can('admin') block md:col-span-2 @elsecan('project-manager')  @endcan">
				<label for="alamat" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Alamat Proyek</label>
				<textarea name="alamat" id="alamat" @can('admin') rows="3" @elsecan('project-manager') rows="1" @endcan
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
				 placeholder="Masukkan Alamat Proyek" required>{{ old('alamat') }}</textarea>
				 @error('alamat') @include('shared.errorText') @enderror
			</div>
		</div>
		<button type="submit"
			class="w-full rounded-lg bg-green px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-green dark:hover:bg-green dark:focus:ring-green sm:w-auto">Submit</button>
	</form>

<!-- Main modal -->
<div id="latitude-longitde" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full" >
    <div class="relative p-4 w-full max-w-5xl h-full" >
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Cara Mengambil Alamat, Latitude dan Longitude
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="latitude-longitde">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <p class="text-gray-800 dark:text-gray-400">
                   	1. Buka link <a class="text-green" href="https://maps.google.com/" target="_blank">maps.google.com</a>
                </p>
								<p class="text-gray-800 dark:text-gray-400">
									2. Cari lokasi melalui search box
									<img class="w-[50%]" src="/images/1-lat-lon.png" alt="">
                </p>
								<p class="text-gray-800 dark:text-gray-400">
									3. Copy alamat berdasarkan hasil pencarian
									<img class="w-[30%]" src="/images/3-lat-lon.png" alt="">
                </p>
								<p class="text-gray-800 dark:text-gray-400">
									4. Pada bagian kanan bawah terdapat icon manusia, tahan dan tarik icon tersebut dan letakkan di marker merah dari hasil lokasi pencarian. 
									<div class="flex"><img class="w-[30%] mr-2" src="/images/3-new-lat-lon.png" alt="">
									<img class="w-[30%]" src="/images/4-new-lat-lon.png" alt=""></div>
                </p>
								<p class="text-gray-800 dark:text-gray-400">
									5. Perhatikan url pada browser, dan copy bagian yang ditandai dengan box merah<br>box pertama merupakan latitude, box kedua merupakan longitude
									<img class="w-full" src="/images/5-new-lat-lon.png" alt="">
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('prepend-script')
	<script>
		$('#searchProyekManager').select2({
				width: null,
        placeholder: 'Pilih Proyek Manager',
				language: {
					inputTooShort: function() {
						return 'Masukkan 1 atau lebih karakter';
					},
					formatNoMatches: function () { return "Tidak ditemukan"; },
					noResults: function(){
        		return "Proyek Manager tidak ditemukan";
       		},
					searching: function(){
        		return "Sedang mencari...";
       		}
				},
        ajax: {
            url: '/selectProyekManager',
            dataType: 'json',
						delay: 100,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama,
                            id: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });
	</script>
@endpush
