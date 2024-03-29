@extends('layouts.create')
@section('content')

<h1 class="text-lg font-bold uppercase my-6 w-full text-center">Tambah Firebase Logistic</h1>
	<form method="POST" action="{{ route('firebase.store') }}" enctype="multipart/form-data">
		@csrf
			<div>
				<label for="latitude" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Latitude 
					<a class="inline-block cursor-pointer text-blue-700 hover:text-blue-800 font-normal rounded-lg text-sm" type="button" data-modal-toggle="latitude-longitde">
  Cara Mengambil Latitude</a>
				</label>
				<input type="number" id="latitude" name="latitude" step="any"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="-6.2501422" required value="{{ old('latitude') }}">
				@error('latitude') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="longitude" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Longitude
					<a class="inline-block cursor-pointer text-blue-700 hover:text-blue-800 font-normal rounded-lg text-sm" type="button" data-modal-toggle="latitude-longitde">
  Cara Mengambil Longitude</a>
				</label>
				<input type="number" id="longitude" step="any" value="{{ old('longitude') }}" name="longitude"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="106.8543034" required>
				@error('longitude') @include('shared.errorText') @enderror
			</div>
		<button type="submit"
			class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-auto">Submit</button>
	</form>

<!-- Main modal -->
<div id="latitude-longitde" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full">
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
                   	1. Buka link <a class="text-blue-700" href="https://maps.google.com/" target="_blank">maps.google.com</a>
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
