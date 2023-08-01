@extends('layouts.signature')
@push('prepend-style')
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
	<style>
		.box {
			position: relative;
			background-color: #fff;
			border-radius: 2em;
		}
		

		.box::before,
		.box::after {
			content: '';
			z-index: -1;
			position: absolute;
			width: calc(100% + 5px);
			height: calc(100% + 5px);
			top: -50;
			left: -50;
			border-radius: 2em;
			background: linear-gradient(45deg,
					#ccef2f, #fffb00, #ccef2f, #fffb00, #ccef2f, #fffb00, #ccef2f,
					#91ff00, #ccef2f, #91ff00, #ccef2f, #91ff00, #ccef2f, #91ff00);
			background-size: 300%;
			animation: border 12s linear infinite;
		}

		.box::after {
			filter: blur(10px);
		}

		@keyframes border {
			0%,
			100% {
				background-position: 0 0;
			}

			50% {
				background-position: 300%;
			}
		}
	</style>
	<style>
	.material-symbols-outlined {
		font-variation-settings:
		'FILL' 1,
		'wght' 400,
		'GRAD' 0,
		'opsz' 48
	}
	</style>
@endpush
@section('content')
	<div class="grid h-screen grid-cols-1 content-center gap-4 sm:p-[5em] lg:p-[10em]">
		<div class="card-header flex flex-col items-center justify-center w-full">
			<h3 class="mb-5 text-3xl font-semibold text-center">Verifikasi Tanda Tangan</h3>
			<img class="mb-2 w-[10em]" src="/images/logo-burda.png" alt="">
			<p class="mb-2 text-lg font-normal">Electronic Signature</p>
		</div>
		<div class="flex flex-col items-center justify-center p-5">
			<div class="box flex flex-col items-center justify-center p-5">
				<div class="sm:flex sm:flex-col md:flex-row justify-center items-center">
					<img class="h-36" src="{{ asset($doVerif->user->ttd) }}" alt="">
					<div class="user flex md:flex-col justify-center w-full md:ml-3">
						<div class="image flex justify-items-center">
							<img class="h-12 border rounded-md" src="{{ asset($doVerif->user->foto) }}" alt="">
						</div>
						<div class="flex flex-col md:mt-2 sm:ml-4 md:ml-0">
							<p class="font-semibold">{{ $doVerif->user->nama }}</p>
							<p class="font-normal mt-1 text-gray-400">{{ $role }}</p>
						</div>
					</div>
				</div>
				<s></s>
				<div class="verification-message my-3 flex flex-col">
					<p class="text-center">Telah menandatangani {{ $perihal }} </p>
					<div class="flex items-center mt-2 justify-center">
						<p class="text-center font-bold">{{ $kode }}</p>
					</div>
					<div class="flex mt-2 items-center">
						<span class="material-symbols-outlined text-orange-500"> location_on </span>
						<p class="ml-1 font-normal">{{ $gudang }}</p>
					</div>
					<div class="flex mt-2 items-center">
						<span class="material-symbols-outlined text-blue-500"> location_on </span>
						<p class="ml-1 font-normal max-w-sm">{{ $perusahaan }}</p>
					</div>
				</div>
				{{-- <p>{{ $doVerif->keterangan }}</p> --}}
				<div class="align-content-between mt-3 flex gap-3 text-gray-500">
					<div class="flex">
						<span class="material-symbols-outlined"> calendar_month </span>
						<p class="ml-1 font-light">{{ \App\Helpers\Date::parseMilliseconds($doVerif->created_at, 'dddd, D MMM YYYY') }}</p>
					</div>
					<div class="flex">
						<span class="material-symbols-outlined"> schedule </span>
						<p class="ml-1 font-light">{{ \App\Helpers\Date::parseMilliseconds($doVerif->created_at, 'H:mm') }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('addon-script')
@endpush
