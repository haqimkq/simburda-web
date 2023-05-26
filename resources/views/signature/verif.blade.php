@extends('layouts.signature')
@push('prepend-style')
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
	<style>
		.box {
			position: relative;
			width: 30em;
			height: 400px;
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
					#ccef2f, #91ff00, #ccef2f, #91ff00, #ccef2f, #91ff00, #ccef2f,
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
@endpush
@section('content')
	<div class="grid h-screen grid-cols-1 content-center gap-4 p-[10em]">
		<div class="card-header flex flex-col items-center justify-center">
			<h1 class="mb-5 text-3xl font-bold">Verifikasi Tanda Tangan</h1>
			<img class="mb-2 w-[10em]" src="/images/logo-burda.png" alt="">
			<p class="mb-2 text-lg font-normal">Electronic Signature</p>
		</div>
		<div class="flex flex-col items-center justify-center p-5">
			<div class="box flex flex-col items-center justify-center p-5">
				<div class="flex">
					<img class="h-36 mr-3" src="{{ asset($sjVerif->user->ttd) }}" alt="">
					<div class="user flex flex-col justify-center">
						<div class="image">
							<img class="h-12 border rounded-md" src="{{ asset($sjVerif->user->foto) }}" alt="">
						</div>
						<p class="font-semibold mt-2">{{ $sjVerif->user->nama }}</p>
						<p class="font-normal mt-1 text-gray-400">{{ $role }}</p>
					</div>
				</div>
				<s></s>
				<p>{{ $sjVerif->keterangan }}</p>
				<div class="align-content-between mt-3 grid grid-flow-col gap-4">
					<div class="flex">
						<span class="material-symbols-outlined"> calendar_month </span>
						<p class="ml-1 font-bold">{{ \App\Helpers\Date::parseMilliseconds($sjVerif->created_at, 'dddd, D MMM YYYY') }}</p>
					</div>
					<div class="flex">
						<span class="material-symbols-outlined"> schedule </span>
						<p class="ml-1 font-bold">{{ \App\Helpers\Date::parseMilliseconds($sjVerif->created_at, 'H:mm') }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('addon-script')
@endpush
