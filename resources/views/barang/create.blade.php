@extends('layouts.create')
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
					<a href="{{ route('barang')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">Barang</a>
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
<h1 class="text-lg font-bold uppercase my-6 w-full text-center">Tambah Barang</h1>
	<form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data">
		@csrf
		<div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
			<div>
				<label for="nama" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Barang</label>
				<input type="text" id="nama" name="nama"
					value="{{ old('nama') }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Nama Barang" required>
					@error('nama') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="jenis" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Jenis Barang</label>
				<select id="countries" name="jenis"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					required>
					<option disabled selected value="{{ old('jenis') }}">Pilih Jenis Barang</option>
					<option value="tidak habis pakai">Tidak Habis Pakai</option>
					<option value="habis pakai">Habis Pakai</option>
				</select>
				@error('jenis') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="jumlah" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Jumlah Barang</label>
				<input type="number" id="jumlah" min="1" name="jumlah" value="{{ old('jumlah') }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Jumlah Barang" required>
				@error('jumlah') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="satuan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Barang</label>
				<select id="countries" name="satuan"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					required>
					<option disabled selected value="{{ old('satuan') }}">Pilih Satuan Barang</option>
					<option value="buah">Buah</option>
					<option value="kilogram">Kilogram</option>
					<option value="meter">Meter</option>
					<option value="batang">Batang</option>
					<option value="lembar">Lembar</option>
				</select>
				@error('satuan') @include('shared.errorText') @enderror
			</div>
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
			<div>
				<label for="alamat" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Alamat Barang</label>
				<textarea name="alamat" id="alamat" rows="1"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan Alamat Barang" required>{{ old('alamat') }}</textarea>
				 @error('alamat') @include('shared.errorText') @enderror
			</div>
			<div class="">
				<label for="detail" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Detail Barang</label>
				<textarea name="detail" id="detail" rows="1"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan Detail Barang" required>{{ old('detail') }}</textarea>
				 @error('detail') @include('shared.errorText') @enderror
			</div>
			<div>
				<label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300" for="gambar">Gambar</label>
				<div class="flex items-center flex-col md:flex-row">
					<img class="border md:mr-2 border-gray-200 rounded-lg mb-2 md:mb-0 max-w-[40%]" id="preview-image" src="https://img.freepik.com/free-vector/illustration-gallery-icon_53876-27002.jpg?w=740&t=st=1662267352~exp=1662267952~hmac=f0385ce0a49bd1243809578d71f8efef2a35d44a28cb49ff48186f6c1e7834a8"
							alt="preview image" >
					<input
						class="self-center block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
						name="gambar" aria-describedby="gambar_help" id="gambar" type="file" accept="image/*" value="{{ old('gambar') }}">
						@error('gambar') @include('shared.errorText') @enderror
				</div>
			</div>
		</div>
		<button disabled type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
				<svg role="status" id="loading" class="hidden mr-3 w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
				<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
				</svg>
				Loading...
		</button>
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

@push('prepend-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script type="text/javascript">
    $('#gambar').change(function(){
    let reader = new FileReader();
    reader.onload = (e) => { 
      $('#preview-image').attr('src', e.target.result); 
    }
    reader.readAsDataURL(this.files[0]); 
   });

	 $('button:submit').click(function(){
		$('#loading').removeAttr('hidden');
		$('#loading').$(selector).attr(attributeName, value);
	 })
  </script>
@endpush
