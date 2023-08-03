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
					<a href="{{ route('kendaraan')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">Kendaraan</a>
				</div>
			</li>
			<li aria-current="page">
				<div class="flex items-center">
					<svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
					<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit</span>
				</div>
			</li>
		</ol>
	</nav>
<h1 class="text-lg font-bold uppercase my-6 w-full text-center">Edit Kendaraan</h1>
	<form method="POST" action="{{ route('kendaraan.update',$kendaraan->id) }}" enctype="multipart/form-data">
		@csrf
		<div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
			<div id="merk-field">
				<label for="merk" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Merk</label>
				<input type="text" id="merk" min="1" name="merk" value="{{ old('merk',$kendaraan->merk) }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Merk Kendaraan" >
				@error('merk') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="jenis" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Jenis Kendaraan</label>
				<select id="jenis" name="jenis"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					required>
					<option disabled>Pilih Jenis Kendaraan</option>
					<option value="MOTOR" {{ ($kendaraan->jenis == "MOTOR") ? 'selected' : '' }}>Motor</option>
					<option value="MOBIL" {{ ($kendaraan->jenis == "MOBIL") ? 'selected' : '' }}>Mobil</option>
					<option value="PICKUP" {{ ($kendaraan->jenis == "PICKUP") ? 'selected' : '' }}>Pickup</option>
					<option value="TRUCK" {{ ($kendaraan->jenis == "TRUCK") ? 'selected' : '' }}>Truck</option>
					<option value="TRONTON" {{ ($kendaraan->jenis == "TRONTON") ? 'selected' : '' }}>Tronton</option>
					<option value="MINIBUS" {{ ($kendaraan->jenis == "MINIBUS") ? 'selected' : '' }}>Minibus</option>
				</select>
				@error('jenis') @include('shared.errorText') @enderror
			</div>
			<div >
				<label for="gudang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Gudang</label>
				<select id="gudang" name="gudang_id"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					>
					<option disabled selected value="{{ old('gudang') }}">Pilih Gudang</option>
					@foreach ($gudangs as $gudang)
						<option value="{{ $gudang->id }}" {{ ($kendaraan->gudang_id == $gudang->id) ? 'selected' : '' }}>{{ $gudang->nama }}</option>
					@endforeach
				</select>
				@error('gudang') @include('shared.errorText') @enderror
			</div>
			<div >
				<label for="plat_nomor" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Plat Nomor</label>
				<textarea name="plat_nomor" id="plat_nomor" rows="1"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan Plat Nomor Kendaraan" >{{ old('plat_nomor', $kendaraan->plat_nomor) }}</textarea>
				 @error('plat_nomor') @include('shared.errorText') @enderror
			</div>
			<input type="hidden" name="oldImage" value="{{ $kendaraan->gambar }}">
			<div class="col-span-2">
				<label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300" for="gambar">Gambar</label>
				<div class="flex items-center flex-col md:flex-row">
					<img class="border md:mr-2 border-gray-200 rounded-lg mb-2 md:mb-0 max-w-[40%]" id="preview-image" src="@if($kendaraan->gambar) {{ asset($kendaraan->gambar) }} @else https://img.freepik.com/free-vector/illustration-gallery-icon_53876-27002.jpg?w=740&t=st=1662267352~exp=1662267952~hmac=f0385ce0a49bd1243809578d71f8efef2a35d44a28cb49ff48186f6c1e7834a8 @endif"
							alt="preview image">
					<input
						class="self-center block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
						name="gambar" aria-describedby="gambar_help" id="gambar" type="file" accept="image/*" value="{{ old('gambar') }}">
						@error('gambar') @include('shared.errorText') @enderror
				</div>
			</div>
		</div>
		<button type="submit"
			class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-auto">Submit</button>
	</form>
@endsection

@push('prepend-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script type="text/javascript">
	var e = document.getElementById("jenis");
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
