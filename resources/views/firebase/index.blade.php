@extends('layouts.app')
@section('content')
<div class="md:ml-[16em] w-full">
	<div class="w-full pl-4">
			@if (session('createProyekSuccess'))
				@section('alertMessage', session('createProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@if (session('deleteProyekSuccess'))
				@section('alertMessage', session('deleteProyekSuccess'))
				@include('shared.alerts.success')
			@endif
			@section('headerName', 'Proyek')
			@section('role', $authUser->role)
			@if ($authUser->foto)
				@section('foto', asset($authUser->foto))
			@endif
			@section('nama', ucfirst(explode(' ', $authUser->nama, 2)[0]))
			@include('includes.header')
		@if ($data > 0)
			<div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-5">
				@foreach ($data as $key => $row)
				<div class="group flex flex-col shadow-md shadow-gray-100 rounded-xl hover:rounded-b-none">
					<div class="flex flex-col w-full">
						<p class="font-medium my-1 line-clamp-2">{{ucfirst($key)}}</p>
						<div class="flex">
							<svg class="fill-blue-600 h-4 w-4 mr-1" width="24" height="26" viewBox="0 0 24 26" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12.028 11.993C15.309 11.993 17.9687 9.30826 17.9687 5.99648C17.9687 2.6847 15.309 0 12.028 0C8.74711 0 6.08739 2.6847 6.08739 5.99648C6.08739 9.30826 8.74711 11.993 12.028 11.993ZM1.4479 18.1268C3.47951 14.4282 7.16181 13.1679 8.749 13V16.6069C8.749 16.8841 8.81137 17.1578 8.93142 17.4071L10.3135 20.2782L10.8601 20.63L10.5753 18.576L11.6102 16.636C11.6066 16.6335 11.6031 16.631 11.5997 16.6284C11.5834 16.6166 11.5676 16.6035 11.5526 16.5894L10.9492 16.0239C10.7367 15.8247 10.7367 15.4852 10.9492 15.286L11.5526 14.7205C11.744 14.5411 12.04 14.5411 12.2314 14.7205L12.8347 15.286C13.0473 15.4852 13.0473 15.8247 12.8347 16.0239L12.2314 16.5894C12.2131 16.6065 12.1938 16.6221 12.1738 16.636L13.2087 18.576L12.9156 20.6894L13.4722 20.3697L14.8687 17.6029C14.9986 17.3455 15.0664 17.0607 15.0664 16.7717V13C16.6536 13.1679 20.5033 14.4282 22.5349 18.1268C23.4079 19.716 23.807 21.3932 23.9782 22.7893C24.2023 24.6161 22.6664 26 20.8427 26H3.15101C1.34221 26 -0.187285 24.6373 0.0186429 22.8234C0.1779 21.4206 0.567807 19.729 1.4479 18.1268Z"/>
							</svg>
							<p class="font-normal text-sm mb-2 line-clamp-1 text-gray-700">
								{{ucfirst($row['latitude'])}}
							</p>
						</div>
						<div class="flex">
							<svg class="fill-blue-600 h-4 w-4 mr-1" width="24" height="26" viewBox="0 0 24 26" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12.028 11.993C15.309 11.993 17.9687 9.30826 17.9687 5.99648C17.9687 2.6847 15.309 0 12.028 0C8.74711 0 6.08739 2.6847 6.08739 5.99648C6.08739 9.30826 8.74711 11.993 12.028 11.993ZM1.4479 18.1268C3.47951 14.4282 7.16181 13.1679 8.749 13V16.6069C8.749 16.8841 8.81137 17.1578 8.93142 17.4071L10.3135 20.2782L10.8601 20.63L10.5753 18.576L11.6102 16.636C11.6066 16.6335 11.6031 16.631 11.5997 16.6284C11.5834 16.6166 11.5676 16.6035 11.5526 16.5894L10.9492 16.0239C10.7367 15.8247 10.7367 15.4852 10.9492 15.286L11.5526 14.7205C11.744 14.5411 12.04 14.5411 12.2314 14.7205L12.8347 15.286C13.0473 15.4852 13.0473 15.8247 12.8347 16.0239L12.2314 16.5894C12.2131 16.6065 12.1938 16.6221 12.1738 16.636L13.2087 18.576L12.9156 20.6894L13.4722 20.3697L14.8687 17.6029C14.9986 17.3455 15.0664 17.0607 15.0664 16.7717V13C16.6536 13.1679 20.5033 14.4282 22.5349 18.1268C23.4079 19.716 23.807 21.3932 23.9782 22.7893C24.2023 24.6161 22.6664 26 20.8427 26H3.15101C1.34221 26 -0.187285 24.6373 0.0186429 22.8234C0.1779 21.4206 0.567807 19.729 1.4479 18.1268Z"/>
							</svg>
							<p class="font-normal text-sm mb-2 line-clamp-1 text-gray-700">
								{{ucfirst($row['longitude'])}}
							</p>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@else
		<h1 class="mb-2 text-center font-medium text-md text-red-600">Belum ada Proyek</h1>
	@endif
</div>
@endsection
