@extends('layouts.create')
@section('content')
	<form method="POST" action="{{ route('pengguna.update', $user->id) }}" enctype="multipart/form-data">
		@csrf
		<div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
			<div>
				<label for="nama" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Pengguna</label>
				<input type="text" id="nama" name="nama"
					value="{{ $user->nama }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
					placeholder="Masukkan Nama" required>
					@error('nama') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="role" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Role</label>
				<select id="role" name="role"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
					required>
					<option selected value="{{$user->role}}">{{ucfirst($user->role)}}</option>
					@if ($user->role != 'user')
						<option value="user">User</option>
					@endif
					@if ($user->role != 'admin') 
						<option value="admin">Admin</option>
					@endif
					@if ($user->role != 'project manager')
						<option value="project manager">Project Manager</option>
					@endif
					@if ($user->role != 'admin gudang')
						<option value="admin gudang">Admin Gudang</option>
					@endif
					@if ($user->role != 'supervisor')
						<option value="supervisor">Supervisor</option>
					@endif
					@if ($user->role != 'purchasing')
						<option value="purchasing">Purchasing</option>
					@endif
					@if ($user->role != 'logistic')
						<option value="logistic">Logistic</option>
					@endif
				</select>
				@error('role') @include('shared.errorText') @enderror
			</div>
			<div>
				<label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300" for="foto">Foto</label>
				<img class="mb-2" id="preview-image" src="
					{{$user->foto ? asset($user->foto) : 'https://img.freepik.com/free-vector/illustration-gallery-icon_53876-27002.jpg?w=740&t=st=1662267352~exp=1662267952~hmac=f0385ce0a49bd1243809578d71f8efef2a35d44a28cb49ff48186f6c1e7834a8'}}
				"
						alt="preview image" style="max-height: 200px;">
				<input
					class="self-start block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none"
					name="foto" id="foto" type="file" accept="image/*">
					@error('foto') @include('shared.errorText') @enderror
			</div>
		<button type="submit"
			class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto">Submit</button>
	</form>
@endsection

@push('prepend-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script type="text/javascript">
    $('#foto').change(function(){
    let reader = new FileReader();
    reader.onload = (e) => { 
      $('#preview-image').attr('src', e.target.result); 
    }
    reader.readAsDataURL(this.files[0]); 
   });
  </script>
@endpush
