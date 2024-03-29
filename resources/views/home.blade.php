@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="w-full md:ml-[16em] md:px-4">
		@section('headerName', 'Dashboard')
		@section('role', App\Helpers\Utils::underscoreToNormal($authUser->role))
		@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
		@if ($authUser->foto)
				@section('foto', asset($authUser->foto))
		@endif
		@include('includes.header')
		@if($authUser->role != 'USER')
			<div>
					@if (!$authUser->ttd)
							Anda Belum membuat tanda tangan
							<a href="{{ route('signature') }}" class="ml-2 rounded-lg bg-primary px-2 py-1 text-white">
									Tambah Tanda Tangan
							</a>
					@else
							Tanda tangan sudah dibuat
							<a href="{{ route('signature') }}" class="ml-2 rounded-lg bg-primary px-2 py-1 text-white">
									Ubah Tanda Tangan
							</a>
					@endif
			</div>
			<div class="grid w-full gap-2 xl:grid-cols-2">
					<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
							<h2 class="text-center text-xl font-medium">Pengguna</h2>
							<canvas id="userRoleChart" class="max-h-[40vw] md:max-h-[30vw] lg:max-h-[20vw]"></canvas>
					</div>
					@if ($proyek)
							<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
									<h2 class="text-center text-xl font-medium">Proyek</h2>
									<canvas id="proyekChart"></canvas>
							</div>
					@endif
					<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
							<h2 class="text-center text-xl font-medium">Barang</h2>
							{{-- <canvas id="barangChart"></canvas> --}}
					</div>
					<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
							<h2 class="text-center text-xl font-medium">Peminjaman Barang</h2>
							{{-- <canvas id="peminjamanChart"></canvas> --}}
					</div>
					<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
							<h2 class="text-center text-xl font-medium">Surat Jalan</h2>
							{{-- <canvas id="peminjamanChart"></canvas> --}}
					</div>
					<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
							<h2 class="text-center text-xl font-medium">Delivery Order</h2>
							{{-- <canvas id="peminjamanChart"></canvas> --}}
					</div>
					<div class="relative rounded-lg p-4 text-primary shadow-md shadow-gray-100">
							<h2 class="text-center text-xl font-medium">Kendaraan</h2>
							{{-- <canvas id="peminjamanChart"></canvas> --}}
					</div>
			</div>
		@endif
</div>
@endsection

@push('prepend-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
		integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
		// var labelsProyekBS = {{ Js::from($labelsProyekBS) }};
		// var labelsProyekS = {{ Js::from($labelsProyekS) }};
		const configPie = {
				type: 'doughnut',
				options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: {
								legend: {
										labels: {
												boxWidth: 8,
												boxHeight: 8,
												usePointStyle: true,
												padding: 15,
										},
								}
						}
				},
		};
		const configLine = {
				type: 'line',
				options: {
						plugins: {
								legend: {
										labels: {
												boxWidth: 5,
												boxHeight: 5,
												usePointStyle: true,
												padding: 15,
										},
								}
						},
						tension: 0.4,
						scales: {
								y: {
										grid: {
												color: '#ffff',
										},
										scaleLabel: {
												display: true,
												labelString: 'Temperature'
										},
										ticks: {
												precision: 0
										}
										// beginAtZero: true,
								},
								x: {
										grid: {
												color: '#ffff',
										},
										ticks: {
												maxTicksLimit: 5
										}
								},
						}
				}
		};

		var proyekLabels = {{ Js::from($proyekLabels) }};
		var proyek = {{ Js::from($proyek) }};
		var proyekBelumSelesai = {{ Js::from($proyekBelumSelesai) }};
		var proyekSelesai = {{ Js::from($proyekSelesai) }};

		const dataProyek = {
				labels: proyekLabels,
				datasets: [{
						label: 'Semua Proyek',
						backgroundColor: '#E12BD3',
						borderColor: '#E12BD3',
						data: proyek,
						pointStyle: 'circle'
				}, {
						label: 'Proyek Belum Selesai',
						backgroundColor: '#B2B699',
						borderColor: '#B2B699',
						pointStyle: 'circle'
				}, {
						label: 'Proyek Selesai',
						backgroundColor: '#41EBA5',
						borderColor: '#41EBA5',
						pointStyle: 'circle'
				}, ]
		};
		const configProyek = configLine;
		configProyek.data = dataProyek;

		const proyekChart = new Chart(
				document.getElementById('proyekChart'),
				configProyek
		);

		// Push data pada dataset proyek belum selesai dengan nilai x yaitu label dan y adalah nilainya
		Object.keys(proyekBelumSelesai).forEach(key => {
				proyekChart.data.datasets[1].data.push({
						x: key,
						y: proyekBelumSelesai[key]
				});
		});
		// Push data pada dataset proyek selesai dengan nilai x yaitu label dan y adalah nilainya
		Object.keys(proyekSelesai).forEach(key => {
				proyekChart.data.datasets[2].data.push({
						x: key,
						y: proyekSelesai[key]
				});
		});
		// update chart proyek
		proyekChart.update();

		// const configBarang = configLine;
		// configProyek.data = dataProyek;
		// const barangChart = new Chart(
		// 				document.getElementById('barangChart'),
		// 				configBarang
		// );

		// const configPeminjaman = configLine;
		// configProyek.data = dataProyek;
		// const peminjamanChart = new Chart(
		// 				document.getElementById('peminjamanChart'),
		// 				configPeminjaman
		// );

		var userRoleLabels = {{ Js::from($userRoleLabels) }};
		var userRole = {{ Js::from($userRole) }};
		const dataUserRole = {
				labels: userRoleLabels,
				datasets: [{
						data: userRole,
						pointStyle: 'rect',
						backgroundColor: [
								"#E12BD3",
								"#B4DE74",
								"#FF8153",
								"#FFEA88",
								"#41EBA5",
								"#B2B699"
						],
				}]
		};

		const configUserRole = configPie;
		configUserRole.data = dataUserRole;
		const userRoleChart = new Chart(
				document.getElementById('userRoleChart'),
				configUserRole
		);
</script>
@endpush
