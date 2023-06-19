<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

	<title>@yield('title')</title>

	<link rel="icon" type="image/png" href="/images/favico.png" />

	<style>
		body {
			font-family: 'Poppins', sans-serif;
			overflow-x: hidden;
		}

		.center {
			margin: auto;
			width: 50%;
		}

		.p-5 {
			padding: 1.25rem
				/* 20px */
					!important;
		}

		@media (min-width: 768px) {
			.md\:p-8 {
				padding: 2rem
					/* 32px */
						!important;
			}
		}

		.w-full {
			width: 100% !important;
		}

		.items-center {
			align-items: center !important;
		}

		.justify-center {
			justify-content: center !important;
		}

		@media print {
			.print\:hidden {
				display: none !important;
			}

			.print\:text-black {
				color: rgb(0 0 0) !important;
			}

			.print\:p-0 {
				padding: 0px !important;
			}
		}

		.inline-flex {
			display: inline-flex !important;
		}

		.space-x-1> :not([hidden])~ :not([hidden]) {
			margin-right: calc(0.25rem * var(--tw-space-x-reverse)) !important;
			margin-left: calc(0.25rem * calc(1 - var(--tw-space-x-reverse))) !important;
		}

		@media (min-width: 768px) {
			.md\:space-x-3> :not([hidden])~ :not([hidden]) {
				margin-right: calc(0.75rem * var(--tw-space-x-reverse)) !important;
				margin-left: calc(0.75rem * calc(1 - var(--tw-space-x-reverse))) !important;
			}
		}

		.text-sm {
			font-size: 0.875rem
				/* 14px */
					!important;
			line-height: 1.25rem
				/* 20px */
					!important;
		}

		.text-xsm {
			font-size: 0.6rem
				/* 14px */
					!important;
			line-height: 1rem
				/* 20px */
					!important;
		}

		.text-white {
			color: rgb(255 255 255) !important;
		}

		.self-center {
			align-self: center !important;
		}

		.font-medium {
			font-weight: 500 !important;
		}

		.text-gray-700 {
			color: rgb(55 65 81) !important;
		}

		.text-gray-400 {
			color: rgb(156 163 175) !important;
		}

		.text-base {
			font-size: 1rem
				/* 16px */
					!important;
			line-height: 1.5rem
				/* 24px */
					!important;
		}

		.hover\:text-gray-900:hover {
			color: rgb(17 24 39) !important;
		}

		.h-6 {
			height: 1.5rem
				/* 24px */
					!important;
		}

		.w-6 {
			width: 1.5rem
				/* 24px */
					!important;
		}

		.ml-1 {
			margin-left: 0.25rem
				/* 4px */
					!important;
		}

		.text-gray-500 {
			color: rgb(107 114 128) !important;
		}

		@media (min-width: 768px) {
			.md\:ml-2 {
				margin-left: 0.5rem
					/* 8px */
						!important;
			}
		}

		.float-right {
			float: right !important;
		}

		.float-left {
			float: left !important;
		}

		.text-center {
			text-align: center !important;
		}

		.max-w-xl {
			max-width: 36rem
				/* 576px */
					!important;
		}

		.self-start {
			align-self: flex-start !important;
		}

		.h-10 {
			height: 2.5rem
				/* 40px */
					!important;
		}

		.h-3 {
			height: 0.75rem
				/* 12px */
					!important;
		}

		.my-5 {
			margin-top: 1.25rem
				/* 20px */
					!important;
			margin-bottom: 1.25rem
				/* 20px */
					!important;
		}

		.justify-end {
			justify-content: flex-end !important;
		}

		.mb-3 {
			margin-bottom: 0.75rem
				/* 12px */
					!important;
		}

		.text-white {
			color: rgb(255 255 255) !important;
		}

		.bg-primary {
			background-color: rgb(31 72 68) !important;
		}

		.text-red-600 {
			color: rgb(224 36 36) !important;
		}

		.p-1 {
			padding: 0.25rem
				/* 4px */
					!important;
		}

		.border {
			border: 1px solid rgb(75 85 99);
		}

		.mb-5 {
			margin-bottom: 1.25rem
				/* 20px */
					!important;
		}

		.border-gray-600 {
			border-color: rgb(75 85 99) !important;
		}

		.table-auto {
			table-layout: auto !important;
			border-collapse: collapse;
		}

		.bg-green-200 {
			background-color: rgb(188 240 218) !important;
		}

		.w-56 {
			width: 14rem
				/* 224px */
					!important;
		}

		.bg-green-200 {
			background-color: rgb(188 240 218) !important;
		}

		.border-gray-600 {
			border-color: rgb(75 85 99) !important;
		}

		.flex {
			display: flex !important;
		}

		.inline-block {
			display: inline-block !important;
		}

		.w-28 {
			width: 7rem
				/* 112px */
					!important;
		}

		.w-40 {
			width: 10rem
				/* 160px */
					!important;
		}

		.mb-1 {
			margin-bottom: 0.25rem
				/* 4px */
					!important;
		}

		.flex-col {
			flex-direction: column !important;
		}

		.items-end {
			align-items: flex-end !important;
		}

		p,
		h1 {
			margin: 0;
		}

		.bg-center {
			background-position: center !important;
		}

		.bg-no-repeat {
			background-repeat: no-repeat !important;
		}

		.bg-contain {
			background-size: contain !important;
		}

		.my-3 {
			margin-top: 0.75rem
				/* 12px */
					!important;
			margin-bottom: 0.75rem
				/* 12px */
					!important;
		}

		.ml-2 {
			margin-left: 0.5rem
				/* 8px */
					!important;
		}

		.text-right {
			text-align: right !important;
		}

		.font-bold {
			font-weight: 700 !important;
		}

		.h-full {
			height: 100% !important;
		}

		.h-auto {
			height: auto !important;
		}

		.h-24 {
			height: 6rem
				/* 96px */
					!important;
		}
	</style>
</head>

<body class="w-full">
	<div class="text-sm">
		<div class="mb-1 w-full overflow-hidden" style="height: 6.5em">
			<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo-burda.png'))) }}"
				alt="" class="h-10 w-auto">
			<p class="text-xsm float-right ml-2 text-right"><span class="text-base font-bold">PT.BURDA CONTRACO </span>
				<br>Wisma NH Jl. Raya Pasar Minggu Blok B-C No. 2, RT 002 RW 002, Pancoran – Jakarta Selatan
				<br>JL. Pengadegan Selatan II No. 1 Pancoran – Jakarta Selatan
				<br>Telp. (62-21) 7988968 – 70 Fax. (62-21) 79195987
				<br>website: www.burdacontraco.co.id, email: info@burdacontraco.co.id / admin@burdacontraco.co.id
			</p>
		</div>
		<div class="mb-1 h-auto w-full bg-primary text-center">
			<p class="text-xsm w-full text-white">
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
							{{ $suratJalan->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->supervisor->nama }}
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
							{{ $suratJalan->sjPengirimanGp->peminjaman->menangani->supervisor->nama }}
						@elseif ($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
							{{ $suratJalan->sjPengirimanPp->peminjaman->menangani->supervisor->nama }}
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
						@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK')
							<b>{{ $suratJalan->sjPengirimanGp->peminjaman->gudang->nama }}</b>
							({{ $suratJalan->sjPengirimanGp->peminjaman->gudang->alamat }})
						@elseif ($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
							<b>{{ $suratJalan->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->nama_proyek }}</b>
							({{ $suratJalan->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->alamat }})
						@else
							<b>{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->proyek->nama_proyek }}</b>
							({{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->proyek->alamat }})
						@endif
					</td>
				</tr>
				<tr>
					<td class="w-56 border border-gray-600 bg-green-200 p-1">
						Tempat Tujuan
					</td>
					<td class="border border-gray-600 p-1">
						@if ($suratJalan->tipe == 'PENGIRIMAN_GUDANG_PROYEK')
							<b>{{ $suratJalan->sjPengirimanGp->peminjaman->menangani->proyek->nama_proyek }}</b>
							({{ $suratJalan->sjPengirimanGp->peminjaman->menangani->proyek->alamat }})
						@elseif ($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
							<b>{{ $suratJalan->sjPengirimanPp->peminjaman->menangani->proyek->nama_proyek }}</b>
							({{ $suratJalan->sjPengirimanPp->peminjaman->menangani->proyek->alamat }})
						@else
							<b>{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->gudang->nama }}</b>
							({{ $suratJalan->sjPengembalian->pengembalian->peminjaman->gudang->alamat }})
						@endif
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
					@foreach ($suratJalan->sjPengirimanGp->peminjaman->peminjamanDetail as $pd)
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
					@foreach ($suratJalan->sjPengirimanPp->peminjaman->peminjamanDetail as $pd)
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
		<table class="mb-5 w-full table-auto">
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
		<div class="w-full">
			<table class="mb-5 w-full table-auto">
				<tbody>
					<tr>
						<td>
							@if ($suratJalan->tipe != 'PENGIRIMAN_PROYEK_PROYEK')
								@if ($ttdPath)
									<table class="mb-5 w-full table-auto">
										<tbody>
											<tr>
												<td>
													<div class="bg-contain bg-center bg-no-repeat"
														style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
														<img
															src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->adminGudang->ttd))) }}"
															alt="" class="my-3 w-40 self-center">
													</div>
												</td>
												<td>
													<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPath)) }}" alt=""
														class="my-3 w-28 self-center">
												</td>
											</tr>
										</tbody>
									</table>
								@else
									<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
										style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
									</div>
								@endif
								<p class="text-center">{{ $suratJalan->adminGudang->nama }}<br><span>Admin
										Gudang</span><br>{{ $suratJalan->adminGudang->no_hp }}</p>
							@elseif($suratJalan->tipe == 'PENGIRIMAN_PROYEK_PROYEK')
								@if ($ttdPathSupervisor2)
									<table class="mb-5 w-full table-auto">
										<tbody>
											<tr>
												<td>
													<div class="bg-contain bg-center bg-no-repeat"
														style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
														<img
															src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->supervisor->ttd))) }}"
															alt="" class="my-3 w-40 self-center">
													</div>
												</td>
												<td>
													<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPathSupervisor2)) }}"
														alt="" class="my-3 w-28 self-center">
												</td>
											</tr>
										</tbody>
									</table>
								@else
									<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
										style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
									</div>
								@endif
								<p class="text-center">
									{{ $suratJalan->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->supervisor->nama }}<br><span>
										Supervisor Peminjam
									</span><br>{{ $suratJalan->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->supervisor->no_hp }}
								</p>
							@endif
						</td>
						<td>
							@if ($ttdPathSupervisor)
								<table class="mb-5 w-full table-auto">
									<tbody>
										<tr>
											<td>
												<div class="bg-contain bg-center bg-no-repeat"
													style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
													@if ($suratJalan->sjPengirimanGp != null)
														<img
															src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengirimanGp->peminjaman->menangani->supervisor->ttd))) }}"
															alt="" class="my-3 w-40 self-center">
													@elseif ($suratJalan->sjPengirimanPp != null)
														<img
															src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengirimanPp->peminjaman->menangani->supervisor->ttd))) }}"
															alt="" class="my-3 w-40 self-center">
													@else
														<img
															src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->ttd))) }}"
															alt="" class="my-3 w-40 self-center">
													@endif
												</div>
											</td>
											<td>
												<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPathSupervisor)) }}" alt=""
													class="my-3 w-28 self-center">
											</td>
										</tr>
									</tbody>
								</table>
							@else
								<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
									style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
								</div>
							@endif
							@if ($suratJalan->sjPengirimanGp != null)
								<p class="text-center">
									{{ $suratJalan->sjPengirimanGp->peminjaman->menangani->supervisor->nama }}<br><span>Supervisor</span><br>{{ $suratJalan->sjPengirimanGp->peminjaman->menangani->supervisor->no_hp }}
								</p>
							@elseif ($suratJalan->sjPengirimanPp != null)
								<p class="text-center">
									{{ $suratJalan->sjPengirimanPp->peminjaman->menangani->supervisor->nama }}<br><span>Supervisor</span><br>{{ $suratJalan->sjPengirimanPp->peminjaman->menangani->supervisor->no_hp }}
								</p>
							@else
								<p class="text-center">
									{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->nama }}<br><span>Supervisor</span><br>{{ $suratJalan->sjPengembalian->pengembalian->peminjaman->menangani->supervisor->no_hp }}
								</p>
							@endif
						</td>
					</tr>
					<tr colspan=2>
						<td style="text-align: center; vertical-align: middle;">
							@if ($ttdPathLogistic)
								<table class="mb-5 w-full table-auto">
									<tbody>
										<tr>
											<td>
												<div class="bg-contain bg-center bg-no-repeat"
													style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
													<img
														src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $suratJalan->logistic->ttd))) }}"
														alt="" class="my-3 w-40 self-center">
												</div>
											</td>
											<td>
												<img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPathLogistic)) }}" alt=""
													class="my-3 w-28 self-center">
											</td>
										</tr>
									</tbody>
								</table>
							@else
								<div class="h-24 w-40 bg-contain bg-center bg-no-repeat"
									style="background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/stempel-burda.png'))) }}')">
								</div>
							@endif
							<p class="text-center">
									{{ $suratJalan->logistic->nama }}<br><span>Driver</span><br>{{ $suratJalan->logistic->no_hp }}
								</p>
						</td>
					</tr>
				</tbody>
			</table>
</body>

</html>
