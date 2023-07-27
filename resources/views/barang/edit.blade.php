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
					<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit</span>
				</div>
			</li>
		</ol>
	</nav>
<h1 class="text-lg font-bold uppercase my-6 w-full text-center">Edit Barang</h1>
	<form method="POST" action="{{ route('barang.update',$barang->barang_id) }}" enctype="multipart/form-data">
		@csrf
		<div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
			<div>
				<label for="nama" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Barang</label>
				<input type="text" id="nama" name="nama"
					value="{{ old('nama',$barang->barang->nama) }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Nama Barang" required>
					@error('nama') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="jenis" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Jenis Barang</label>
				<select id="jenis" name="jenis"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					disabled>
					<option value="TIDAK_HABIS_PAKAI" {{ ($barang->barang->jenis == "TIDAK_HABIS_PAKAI") ? 'selected' : '' }}>Tidak Habis Pakai</option>
					<option value="HABIS_PAKAI" {{ ($barang->barang->jenis == "HABIS_PAKAI") ? 'selected' : '' }}>Habis Pakai</option>
				</select>
				@error('jenis') @include('shared.errorText') @enderror
			</div>
			<div style="{{ ($barang->barang->jenis == "HABIS_PAKAI") ? 'display: none' : '' }}" id="kondisi-field">
				<label for="kondisi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Kondisi Barang</label>
				<select id="kondisi" name="kondisi"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					>
					<option value="BARU">Baru</option>
					<option value="BEKAS">Bekas</option>
				</select>
				@error('kondisi') @include('shared.errorText') @enderror
			</div>
			<div >
				<label for="gudang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Gudang</label>
				<select id="gudang" name="gudang_id"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					>
					@foreach ($gudangs as $gudang)
						<option value="{{ $gudang->id }}" {{ ($barang->barang->jenis == "TIDAK_HABIS_PAKAI") ? 'selected' : '' }}>{{ $gudang->nama }}</option>
					@endforeach
				</select>
				@error('gudang') @include('shared.errorText') @enderror
			</div>
			<div id="merk-field">
				<label for="merk" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Merk</label>
				<input type="text" id="merk" min="1" name="merk" value="{{ old('merk', $barang->barang->merk) }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Merk Barang" >
				@error('merk') @include('shared.errorText') @enderror
			</div>
			<div style="{{ ($barang->barang->jenis == "TIDAK_HABIS_PAKAI") ? 'display: none' : '' }}" id="jumlah-field">
				<label for="jumlah" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Jumlah Barang</label>
				<input type="number" id="jumlah" min="1" name="jumlah" value="{{ old('jumlah',$barang->jumlah) }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Jumlah Barang" >
				@error('jumlah') @include('shared.errorText') @enderror
			</div>
			<div style="{{ ($barang->barang->jenis == "TIDAK_HABIS_PAKAI") ? 'display: none' : '' }}" id="ukuran-field">
				<label for="ukuran" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Ukuran Barang</label>
				<input type="text" id="ukuran" min="1" name="ukuran" value="{{ old('ukuran',$barang->ukuran) }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Ukuran Barang" >
				@error('ukuran') @include('shared.errorText') @enderror
			</div>
			<div style="{{ ($barang->barang->jenis == "TIDAK_HABIS_PAKAI") ? 'display: none' : '' }}" id="satuan-field">
				<label for="satuan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Barang</label>
				<input type="text" id="satuan" min="1" name="satuan" value="{{ old('satuan',$barang->satuan) }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Satuan Barang" >
				@error('satuan') @include('shared.errorText') @enderror
			</div>
			
			<div  class="">
				<label for="detail" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Detail Barang</label>
				<textarea name="detail" id="detail" rows="3"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan Detail Barang" >{{ old('detail', $barang->barang->detail) }}</textarea>
				 @error('detail') @include('shared.errorText') @enderror
			</div>
			<div  class="col-span-2" id="keterangan-field" style="{{ ($barang->barang->jenis == "HABIS_PAKAI") ? 'display: none' : '' }}">
				<label for="keterangan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Keterangan</label>
				<textarea name="keterangan" id="keterangan" rows="1"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan Keterangan Barang" >{{ old('keterangan',$barang->keterangan) }}</textarea>
				 @error('keterangan') @include('shared.errorText') @enderror
			</div>
			<input type="hidden" value="{{ $barang->barang->gambar }}">
			<div class="col-span-2">
				<label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300" for="gambar">Gambar</label>
				<div class="flex items-center flex-col md:flex-row">
					<img class="border md:mr-2 border-gray-200 rounded-lg mb-2 md:mb-0 max-w-[40%]" id="preview-image" src="{{ ($barang->barang->gambar) ? asset($barang->barang->gambar) : 'https://img.freepik.com/free-vector/illustration-gallery-icon_53876-27002.jpg?w=740&t=st=1662267352~exp=1662267952~hmac=f0385ce0a49bd1243809578d71f8efef2a35d44a28cb49ff48186f6c1e7834a8' }}"
							alt="preview image" >
					<input
						class="self-center block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
						name="gambar" aria-describedby="gambar_help" id="gambar" type="file" accept="image/*" value="{{ $barang->barang->gambar }}">
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
	$('#jenis').change(function(){
		if(e.value == 'HABIS_PAKAI'){
			document.getElementById('jumlah-field').style.display = 'block';
			document.getElementById('satuan-field').style.display = 'block';
			document.getElementById('ukuran-field').style.display = 'block';
			document.getElementById('keterangan-field').style.display = 'none';
			document.getElementById('kondisi-field').style.display = 'none';
		}
		if(e.value == 'TIDAK_HABIS_PAKAI'){
			document.getElementById('jumlah-field').style.display = 'none';
			document.getElementById('satuan-field').style.display = 'none';
			document.getElementById('ukuran-field').style.display = 'none';
			document.getElementById('keterangan-field').style.display = 'block';
			document.getElementById('kondisi-field').style.display = 'block';
		}
	});
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
