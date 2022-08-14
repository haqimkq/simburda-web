@extends('layouts.app')

@section('content')
	<div class="w-full px-4">
		<div class="flex items-center justify-between">
			<h1 class="text-primary text-[2em] font-bold">Dashboard</h1>
			<div class="flex items-center">
				<div class="flex flex-col items-end">
					<h3 class="text-primary inline-flex !w-full text-lg font-semibold">{{ $authUser->nama }}</h3>
					<p class="text-primary font-light">{{ ucfirst($authUser->role) }}</p>
				</div>
				<img class="mr-2 h-16 w-auto rounded-full" src="{{ $authUser->foto ? asset($authUser->foto) : asset('images/manager.png') }}"
					alt="">
			</div>
		</div>
		<div class="my-5 flex w-full">
		@section('action','/home')
		@include('shared.search')
	</div>
	<div class="w-full rounded-lg border bg-white shadow-none dark:border-gray-700 dark:bg-gray-800">
		<div class="sm:hidden">
			<label for="tabs" class="sr-only">Select tab</label>
			<select id="tabs"
				class="focus:ring-primary focus:border-primary dark:focus:ring-primary dark:focus:border-primary block w-full rounded-t-lg border-0 border-b border-gray-200 bg-gray-50 text-primary hover:text-primary-70 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 sm:text-sm">
				<option>Pengguna</option>
				<option>Barang</option>
				<option>Kendaraan</option>
			</select>
		</div>
		<ul
			class="hidden divide-x divide-gray-200 rounded-lg text-center text-sm font-medium text-gray-500 dark:divide-gray-600 dark:text-gray-400 sm:flex"
			id="fullWidthTab" data-tabs-toggle="#fullWidthTabContent" role="tablist">
			<li class="w-full">
				<button id="pengguna-tab" data-tabs-target="#pengguna" type="button" role="tab" aria-controls="pengguna"
					aria-selected="true"
					class="inline-block w-full rounded-tl-lg bg-gray-50 text-primary hover:text-primary-70 p-4 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">Pengguna</button>
			</li>
			<li class="w-full">
				<button id="barang-tab" data-tabs-target="#barang" type="button" role="tab" aria-controls="barang"
					aria-selected="false"
					class="inline-block w-full bg-gray-50 text-primary hover:text-primary-70 p-4 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">Barang</button>
			</li>
			<li class="w-full">
				<button id="kendaraan-tab" data-tabs-target="#kendaraan" type="button" role="tab" aria-controls="kendaraan"
					aria-selected="false"
					class="inline-block w-full rounded-tr-lg bg-gray-50 text-primary hover:text-primary-70 p-4 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">Kendaraan</button>
			</li>
		</ul>
		<div id="fullWidthTabContent" class="border-t border-gray-200 dark:border-gray-600">
			<div class="hidden rounded-lg bg-white p-4 dark:bg-gray-800 md:p-8" id="pengguna" role="tabpanel"
				aria-labelledby="pengguna-tab">
				<dl
					class="mx-auto grid max-w-screen-xl grid-cols-2 gap-8 p-4 text-gray-900 dark:text-white sm:grid-cols-3 sm:p-8 xl:grid-cols-6">
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$projectmanager->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Project Manager</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$supervisor->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Supervisor</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$admingudang->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Admin Gudang</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$logistic->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Logistic</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$purchasing->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Purchasing</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$user->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Tanpa Role</dd>
					</div>
				</dl>
			</div>
			<div class="hidden rounded-lg bg-white p-4 dark:bg-gray-800 md:p-8" id="barang" role="tabpanel"
				aria-labelledby="barang-tab">
				<dl
					class="mx-auto grid max-w-screen-xl grid-cols-2 gap-8 p-4 text-gray-900 dark:text-white sm:grid-cols-3 sm:p-8 xl:grid-cols-6">
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$projectmanager->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Barang di gudang</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$supervisor->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Barang sedang dipinjam</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$admingudang->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Permintaan Akses Barang</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$logistic->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Logistic</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$purchasing->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Purchasing</dd>
					</div>
					<div class="flex flex-col items-center justify-center">
						<dt class="mb-2 text-3xl font-extrabold text-primary">{{$user->count()}}</dt>
						<dd class="font-light text-gray-500 dark:text-gray-400">Tanpa Role</dd>
					</div>
				</dl>
			</div>
			<div class="hidden rounded-lg bg-white p-4 dark:bg-gray-800 md:p-8" id="kendaraan" role="tabpanel"
				aria-labelledby="kendaraan-tab">
				<div id="accordion-flush" data-accordion="collapse"
					data-active-classes="bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
					data-inactive-classes="text-gray-500 dark:text-gray-400">
					<h2 id="accordion-flush-heading-1">
						<button type="button"
							class="flex w-full items-center justify-between border-b border-gray-200 py-5 text-left font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400"
							data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
							<span>What is Flowbite?</span>
							<svg data-accordion-icon class="h-6 w-6 shrink-0 rotate-180" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
									clip-rule="evenodd"></path>
							</svg>
						</button>
					</h2>
					<div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
						<div class="border-b border-gray-200 py-5 font-light dark:border-gray-700">
							<p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is an open-source library of interactive components
								built on top of Tailwind CSS including buttons, dropdowns, modals, navbars, and more.</p>
							<p class="text-gray-500 dark:text-gray-400">Check out this guide to learn how to <a
									href="/docs/getting-started/introduction/" class="text-primary dark:text-primary hover:underline">get
									started</a> and start developing websites even faster with components on top of Tailwind CSS.</p>
						</div>
					</div>
					<h2 id="accordion-flush-heading-2">
						<button type="button"
							class="flex w-full items-center justify-between border-b border-gray-200 py-5 text-left font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400"
							data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
							<span>Is there a Figma file available?</span>
							<svg data-accordion-icon class="h-6 w-6 shrink-0" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
									clip-rule="evenodd"></path>
							</svg>
						</button>
					</h2>
					<div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
						<div class="border-b border-gray-200 py-5 font-light dark:border-gray-700">
							<p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is first conceptualized and designed using the Figma
								software so everything you see in the library has a design equivalent in our Figma file.</p>
							<p class="text-gray-500 dark:text-gray-400">Check out the <a href="https://flowbite.com/figma/"
									class="text-primary dark:text-primary hover:underline">Figma design system</a> based on the utility classes
								from Tailwind CSS and components from Flowbite.</p>
						</div>
					</div>
					<h2 id="accordion-flush-heading-3">
						<button type="button"
							class="flex w-full items-center justify-between border-b border-gray-200 py-5 text-left font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400"
							data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
							<span>What are the differences between Flowbite and Tailwind UI?</span>
							<svg data-accordion-icon class="h-6 w-6 shrink-0" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
									clip-rule="evenodd"></path>
							</svg>
						</button>
					</h2>
					<div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
						<div class="border-b border-gray-200 py-5 font-light dark:border-gray-700">
							<p class="mb-2 text-gray-500 dark:text-gray-400">The main difference is that the core components from Flowbite
								are open source under the MIT license, whereas Tailwind UI is a paid product. Another difference is that
								Flowbite relies on smaller and standalone components, whereas Tailwind UI offers sections of pages.</p>
							<p class="mb-2 text-gray-500 dark:text-gray-400">However, we actually recommend using both Flowbite, Flowbite
								Pro, and even Tailwind UI as there is no technical reason stopping you from using the best of two worlds.</p>
							<p class="mb-2 text-gray-500 dark:text-gray-400">Learn more barang these technologies:</p>
							<ul class="list-disc pl-5 text-gray-500 dark:text-gray-400">
								<li><a href="https://flowbite.com/pro/" class="text-primary dark:text-primary hover:underline">Flowbite
										Pro</a></li>
								<li><a href="https://tailwindui.com/" rel="nofollow"
										class="text-primary dark:text-primary hover:underline">Tailwind UI</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
