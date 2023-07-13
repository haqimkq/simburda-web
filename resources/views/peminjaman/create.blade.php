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
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('proyek') }}"
                        class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">Peminjaman</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Tambah</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-lg font-bold uppercase my-6 w-full text-center">Tambah Peminjaman</h1>
    <form method="POST" action="{{ route('peminjaman.store') }}">
        @csrf
        <div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
            <div class="flex w-full flex-col col-span-2">
                <label for="orderBy" class="mb-2 block text-sm font-normal text-gray-700">Proyek</label>
                <select name="proyek" id="country"
                    class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
                    @foreach ($proyeks as $proyek)
                        <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tgl_peminjaman" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                    Peminjaman</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input name="tgl_peminjaman" datepicker datepicker-autohide type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date">
                </div>
                @error('tgl_peminjaman')
                    @include('shared.errorText')
                @enderror
            </div>
            <div>
                <label for="tgl_berakhir" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                    Berakhir</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input name="tgl_berakhir" datepicker datepicker-autohide type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date">
                </div>
                @error('tgl_berakhir')
                    @include('shared.errorText')
                @enderror
            </div>
			<div class="flex w-full flex-col" id="tipe-field">
                <label for="orderBy" class="mb-2 block text-sm font-normal text-gray-700">Tipe</label>
                <select name="proyek" id="tipe"
				class="dark: block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
					<option value="" selected>Tipe</option>
					<option value="GUDANG_PROYEK">Gudang Ke Proyek</option>
                    <option value="PROYEK_PROYEK">Proyek Ke Proyek</option>
                </select>
            </div>
			<div class="w-full flex-col hidden" id="gudang-field">
                <label for="orderBy" class="mb-2 block text-sm font-normal text-gray-700">Gudang</label>
                <select name="gudang_id" id="gudang"
                    class="dark: w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-green dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-green">
					<option value="" selected>Tipe</option>
					@foreach ($gudangs as $gudang)
						<option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
					@endforeach
                </select>
            </div>
        </div>
		<div id="barang"></div>
                {{-- @foreach ($barangs as $barang)
                    <div class="relative group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
                        <div class="flex p-2 align-items-center">
							<input id="default-checkbox" type="checkbox" name="barang[]" value="{{ $barang->id }}" class=" text-blue-600 bg-gray-100 rounded dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-3 m-3 checked:bg-green mr-2  border-green border w-5 h-5 focus:ring-green">
                            @if (isset($barang->barang->gambar))
                                <div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
                                    style="background-image: url('{{ asset($barang->barang->gambar) }}')"></div>
                            @endif
                            <div class="flex flex-col">
                                <span
                                    class="{{ $barang->kondisi == 'BARU' ? 'bg-green-200 text-green-600 border-green-600' : 'text-primary border-primary bg-primary-30' }} mb-2 self-start rounded-full border px-1.5 text-xs">
                                    {{ App\Helpers\Utils::underscoreToNormal($barang->kondisi) }}
                                </span>
                                <p class="mb-2 font-medium line-clamp-1">{{ $barang->barang->nama }}</p>
                                <p class="mb-2 text-xs font-normal">
                                    {{ \App\Helpers\Date::parseMilliseconds($barang->barang->created_at) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach --}}
		<div class="flex justify-center">
			<button type="submit"
				class="content-center w-full mt-5 self-center rounded-lg bg-green px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-green dark:hover:bg-green dark:focus:ring-green sm:w-auto">Submit</button>
		</div>
    </form>
	<script>
		$("#tipe").change(function() {
			var tipe = $("#tipe option:selected").val();
			var gudang = document.getElementById("gudang-field");
			if(tipe == "GUDANG_PROYEK"){
				gudang.classList.remove("hidden");
			}else if(tipe == "PROYEK_PROYEK"){
				if(!gudang.classList.contains('hidden')){
					console.log('p');
					gudang.classList.add("hidden");
				}
			}
		}); 
		$("#gudang").change(function() {
			$.getJSON('http://127.0.0.1:8000/' + "/peminjaman/tambah/barangByGudang/" + $("#gudang option:selected").val(), function(data) {
				console.log(data);
				var temp = [];
				//CONVERT INTO ARRAY
				$.each(data, function(key, value) {
					temp.push({v:value, k: key});
				});
				//SORT THE ARRAY
				temp.sort(function(a,b){
					if(a.v > b.v){ return 1}
					if(a.v < b.v){ return -1}
						return 0;
				});
				//APPEND INTO SELECT BOX
				$('#barang').empty();
				$.each(temp, function(key, obj) {
					// $('#barang').append(`
					// 	<div class="relative group flex flex-col rounded-xl shadow-md shadow-gray-100 hover:rounded-b-none">
                    //     <div class="flex p-2 align-items-center">
					// 		<input id="default-checkbox" type="checkbox" name="barang[]" value="{{ $barang->id }}" class=" text-blue-600 bg-gray-100 rounded dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-3 m-3 checked:bg-green mr-2  border-green border w-5 h-5 focus:ring-green">
                    //         @if (isset(`+obj+`))
                    //             <div class="mr-2 h-[6em] w-[6em] rounded-xl bg-cover md:h-[5em] md:w-[5em] lg:h-[7em] lg:w-[7em]"
                    //                 style="background-image: url('{{ asset($barang->barang->gambar) }}')"></div>
                    //         @endif
                    //         <div class="flex flex-col">
                    //             <span
                    //                 class="{{ $barang->kondisi == 'BARU' ? 'bg-green-200 text-green-600 border-green-600' : 'text-primary border-primary bg-primary-30' }} mb-2 self-start rounded-full border px-1.5 text-xs">
                    //                 {{ App\Helpers\Utils::underscoreToNormal($barang->kondisi) }}
                    //             </span>
                    //             <p class="mb-2 font-medium line-clamp-1">{{ $barang->barang->nama }}</p>
                    //             <p class="mb-2 text-xs font-normal">
                    //                 {{ \App\Helpers\Date::parseMilliseconds($barang->barang->created_at) }}</p>
                    //         </div>
                    //     </div>
                    // </div>
					// `);           
				});            
			});   
		}); 
	</script>
@endsection

@push('prepend-script')
    @include('includes.flowbiteDatePicker')
@endpush
