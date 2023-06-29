@extends('layouts.cetak')
@push('addon-style')
	<style>
		.text-xsm {
			font-size: 0.6rem
				/* 14px */
					!important;
			line-height: 1rem
				/* 20px */
					!important;
		}

		.page {
			width: 21cm;
			min-height: 29.7cm;
			padding: 2cm;
			margin: 1cm auto;
			border: 1px #D3D3D3 solid;
			border-radius: 5px;
			background: white;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}
	</style>
@endpush
@section('content')
	<nav class="flex print:hidden" aria-label="Breadcrumb">
		<ol class="inline-flex items-center space-x-1 md:space-x-3">
			<li class="inline-flex items-center">
				<a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
					<svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
						</path>
					</svg>
					Home
				</a>
			</li>
			<li>
				<div class="flex items-center">
					<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
							clip-rule="evenodd"></path>
					</svg>
					<a href="{{ route('surat-jalan') }}"
						class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2">Surat Jalan</a>
				</div>
			</li>
			<li aria-current="page">
				<div class="flex items-center">
					<svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
							clip-rule="evenodd"></path>
					</svg>
					<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail</span>
				</div>
			</li>
		</ol>
	</nav>

	<div class="flex justify-end">
		<a href="{{ route('surat-jalan.downloadPDF', $suratJalan->id) }}" target="_blank"
			class="self-end rounded-full bg-primary py-1 px-2 text-white">
			Download PDF
		</a>
	</div>
	<div class="page flex flex-col text-sm">
		<div class="mb-1 mt-5 flex w-full justify-between">
			<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo-burda.png'))) }}"
				alt="" class="my-5 h-10 w-auto self-start">
			<p class="text-xsm ml-2 text-right">
				<span class="text-base font-bold">
					PT.BURDA CONTRACO
				</span>
				<br>Wisma NH Jl. Raya Pasar Minggu Blok B-C No. 2, RT 002 RW 002, Pancoran – Jakarta Selatan
				<br>JL. Pengadegan Selatan II No. 1 Pancoran – Jakarta Selatan
				<br>Telp. (62-21) 7988968 – 70 Fax. (62-21) 79195987
				<br>website: www.burdacontraco.co.id, email: info@burdacontraco.co.id / admin@burdacontraco.co.id
			</p>
		</div>
		<div class="mb-1 h-auto w-full bg-primary text-center">
			<p class="text-xsm text-white">
				Civil Works | Steel Construction | Infrastructure
			</p>
		</div>
		<div class="w-full bg-primary">
			<h1 class="w-auto p-1 text-center text-base text-white print:p-0 print:text-black">Form Surat Jalan
				{{ ucwords(strtolower(str_replace('_', ' ', $suratJalan->tipe))) }}</h1>
		</div>
		<table class="my-5 w-full table-auto border border-gray-600">
			<tbody>
				<tr>
					<td class="w-56 border border-gray-600 bg-green-200 p-1">No.</td>
					<td class="border border-gray-600 p-1">{{ $suratJalan->kode_surat }}</td>
				</tr>
				<tr>
					<td class="w-56 border border-gray-600 bg-green-200 p-1">
						@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK')
							Pemberi (Admin Gudang)
						@elseif ($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK' || $suratJalan->tipe == 'PENGEMBALIAN')
							Pemberi (Supervisor)
						@endif
					</td>
					<td class="border border-gray-600 p-1">
						@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK')
							{{ $suratJalan->adminGudang->nama }}
						@elseif ($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
							{{ $suratJalan->sjPengirimanPp->peminjamanPp->peminjamanAsal->menangani->supervisor->nama }}
						@else
							{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->nama }}
						@endif
					</td>
				</tr>
				<tr>
					<td class="w-56 border border-gray-600 bg-green-200 p-1">
						@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK' || $suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
							Penerima (Supervisor)
						@else
							Penerima (Admin Gudang)
						@endif
					</td>
					<td class="border border-gray-600 p-1">
						@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK')
							{{ $suratJalan->sjPengirimanGp->peminjamanGp->peminjaman->menangani->supervisor->nama }}
						@elseif ($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
							{{ $suratJalan->sjPengirimanPp->peminjamanPp->peminjaman->menangani->supervisor->nama }}
						@else
							{{ $suratJalan->adminGudang->nama }}
						@endif
					</td>
				</tr>
				<tr>
					<td class="w-56 border border-gray-600 bg-green-200 p-1">
						Tempat Asal
					</td>
					<td class="border border-gray-600 p-1">
						<b>{{ $lokasi['lokasi_asal']['nama'] }}</b>
							({{ $lokasi['lokasi_asal']['alamat'] }})
					</td>
				</tr>
				<tr>
					<td class="w-56 border border-gray-600 bg-green-200 p-1">
						Tempat Tujuan
					</td>
					<td class="border border-gray-600 p-1">
						<b>{{ $lokasi['lokasi_tujuan']['nama'] }}</b>
							({{ $lokasi['lokasi_tujuan']['alamat'] }})
					</td>
				</tr>
			</tbody>
		</table>
		<table class="mb-5 w-full table-auto text-center">
			<thead>
				<tr>
					<th class="border border-gray-600 bg-green-200 p-1">No.</th>
					<th class="border border-gray-600 bg-green-200 p-1">Jumlah</th>
					<th class="border border-gray-600 bg-green-200 p-1">Nama Barang</th>
				</tr>
			</thead>
			<tbody>
				@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK')
					@foreach ($suratJalan->sjPengirimanGp->peminjamanGp->peminjaman->peminjamanDetail as $pd)
						<tr>
							<td class="border border-gray-600 p-1">{{ $loop->iteration }}</td>
							<td class="border border-gray-600 p-1">{{ $pd->jumlah_satuan }}</td>
							<td class="border border-gray-600 p-1">
								{{ $pd->barang->nama }}
								@if ($pd->barang->jenis == 'TIDAK_HABIS_PAKAI')
									(#{{ $pd->barang->barangTidakHabisPakai->nomor_seri }}
									{{ $pd->barang->barangTidakHabisPakai->kondisi }})
								@endif
							</td>
						</tr>
					@endforeach
				@elseif($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
					@foreach ($suratJalan->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanDetail as $pd)
						<tr>
							<td class="border border-gray-600 p-1">{{ $loop->iteration }}</td>
							<td class="border border-gray-600 p-1">{{ $pd->jumlah_satuan }}</td>
							<td class="border border-gray-600 p-1">
								{{ $pd->barang->nama }}
								@if ($pd->barang->jenis == 'TIDAK_HABIS_PAKAI')
									(#{{ $pd->barang->barangTidakHabisPakai->nomor_seri }})
									({{ $pd->barang->barangTidakHabisPakai->kondisi }})
								@endif
							</td>
						</tr>
					@endforeach
				@else
					@foreach ($suratJalan->sjPengembalian->pengembalian->pengembalianDetail as $pd)
						<tr>
							<td class="border border-gray-600 p-1">{{ $loop->iteration }}</td>
							<td class="border border-gray-600 p-1">{{ $pd->jumlah_satuan }}</td>
							<td class="border border-gray-600 p-1">
								{{ $pd->barang->nama }}
								@if ($pd->barang->jenis == 'TIDAK_HABIS_PAKAI')
									(#{{ $pd->barang->barangTidakHabisPakai->nomor_seri }})
									({{ $pd->barang->barangTidakHabisPakai->kondisi }})
								@endif
							</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
		<table class="mb-5 table-auto">
			<tbody>
				<tr>
					<td>No. Kendaraan</td>
					<td>: {{ $suratJalan->kendaraan->plat_nomor }}</td>
				</tr>
				<tr>
					<td>Supir</td>
					<td>: {{ $suratJalan->logistic->nama }}</td>
				</tr>
			</tbody>
		</table>
		<div class="flex justify-end">
			@if ($suratJalan->tipe != 'PENGIRIMAN_PROYEK_PROYEK')
				<div class="flex flex-col">
					<div class="flex">
						@if ($ttdPath)
							<div class="bg-contain bg-center bg-no-repeat"
								style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
								<img
									src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->adminGudang->ttd))) }}"
									alt="" class="my-3 w-40 self-center">
							</div>
							<div class="bg-contain bg-center bg-no-repeat">
								<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPath)) }}" alt=""
									class="my-3 w-28 self-center">
							</div>
						@else
							<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
								style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
							</div>
						@endif
					</div>
					<p class="text-center">{{ $suratJalan->adminGudang->nama }}<br><span>Admin
							Gudang</span><br>{{ $suratJalan->adminGudang->no_hp }}</p>
					<a href="{{route('signature.verifiedTTDSuratJalan', $suratJalan->ttd_admin)}}" target="_blank" class="rounded-full py-1 px-2 text-white self-center mt-2 bg-green-400" >
						Verifikasi
					</a>
				</div>
			@elseif($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
				<div class="flex flex-col">
					<div class="flex">
						@if ($ttdPathSupervisor2)
							<div class="bg-contain bg-center bg-no-repeat"
								style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
								<img
									src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengirimanPp->peminjamanPp->peminjamanAsal->menangani->supervisor->ttd))) }}"
									alt="" class="my-3 w-40 self-center">
							</div>
							<div class="bg-contain bg-center bg-no-repeat">
								<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPathSupervisor2)) }}" alt=""
									class="my-3 w-28 self-center">
							</div>
						@else
							<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
								style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
							</div>
						@endif
					</div>
					<p class="text-center">
						{{ $suratJalan->sjPengirimanPp->peminjamanPp->peminjamanAsal->menangani->supervisor->nama }}<br><span>
							Supervisor Peminjam	
						</span><br>{{ $suratJalan->sjPengirimanPp->peminjamanPp->peminjamanAsal->menangani->supervisor->no_hp }}
					</p>
					<a href="{{route('signature.verifiedTTDSuratJalan', $suratJalan->sjPengirimanPp->ttd_supervisor_peminjam)}}" target="_blank" class="rounded-full py-1 px-2 text-white self-center mt-2 bg-green-400" >
						Verifikasi
					</a>
				</div>
			@endif
			<div class="flex flex-col">
				<div class="flex">
					@if ($ttdPathSupervisor)
						<div class="bg-contain bg-center bg-no-repeat"
							style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
							@if ($suratJalan->sjPengirimanGp != null)
								<img
									src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengirimanGp->peminjamanGp->peminjaman->menangani->supervisor->ttd))) }}"
									alt="" class="my-3 w-40 self-center">
							@elseif ($suratJalan->sjPengirimanPp != null)
								<img
									src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengirimanPp->peminjamanPp->peminjaman->menangani->supervisor->ttd))) }}"
									alt="" class="my-3 w-40 self-center">
							@else
								<img
									src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->ttd))) }}"
									alt="" class="my-3 w-40 self-center">
							@endif
						</div>
						<div class="bg-contain bg-center bg-no-repeat">
							<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPathSupervisor)) }}" alt=""
								class="my-3 w-28 self-center">
						</div>
					@else
						<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
							style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
						</div>
					@endif
				</div>
				@if ($suratJalan->sjPengirimanGp != null)
					<p class="text-center">
						{{ $suratJalan->sjPengirimanGp->peminjamanGp->peminjaman->menangani->supervisor->nama }}<br><span>Supervisor</span><br>{{ $suratJalan->sjPengirimanGp->peminjamanGp->peminjaman->menangani->supervisor->no_hp }}
					</p>
				@elseif ($suratJalan->sjPengirimanPp != null)
					<p class="text-center">
						{{ $suratJalan->sjPengirimanPp->peminjamanPp->peminjaman->menangani->supervisor->nama }}<br><span>Supervisor</span><br>{{ $suratJalan->sjPengirimanPp->peminjamanPp->peminjaman->menangani->supervisor->no_hp }}
					</p>
				@else
					<p class="text-center">
						{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->nama }}<br><span>Supervisor</span><br>{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->no_hp }}
					</p>
				@endif
				@if ($suratJalan->ttd_supervisor)
				<a href="{{route('signature.verifiedTTDSuratJalan', $suratJalan->ttd_supervisor)}}" target="_blank" class="rounded-full py-1 px-2 text-white self-center mt-2 bg-green-400" >
					Verifikasi
				</a>
				@endif
			</div>
			<div class="flex flex-col">
				<div class="flex">
					@if ($ttdPathLogistic)
						<div class="bg-contain bg-center bg-no-repeat"
							style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
							<img
								src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->logistic->ttd))) }}"
								alt="" class="my-3 w-40 self-center">
						</div>
						<div class="bg-contain bg-center bg-no-repeat">
							<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPathLogistic)) }}" alt=""
								class="my-3 w-28 self-center">
						</div>
					@else
						<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
							style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
						</div>
					@endif
				</div>
				<p class="text-center">{{ $suratJalan->logistic->nama }}<br><span>Driver</span><br>{{ $suratJalan->logistic->no_hp }}</p>
				
				@if ($suratJalan->ttd_driver)
					<a href="{{route('signature.verifiedTTDSuratJalan', $suratJalan->ttd_driver)}}" target="_blank" class="rounded-full py-1 px-2 text-white self-center mt-2 bg-green-400" >
						Verifikasi
					</a>
				@endif
			</div>
		</div>
	</div>
@endsection
