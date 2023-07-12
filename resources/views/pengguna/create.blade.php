@extends('layouts.create')
@section('content')
	<form method="POST" action="{{ route('pengguna.store') }}" enctype="multipart/form-data">
		@csrf
		<div class="mb-6 grid gap-6 md:grid-cols-2 w-[80vw]">
			<div>
				<label for="nama" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Pengguna <span class="text-red-700">*</span></label>
				<input type="text" id="nama" name="nama"
					value="{{ old('nama') }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Nama User" required>
					@error('nama') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="role" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Role<span class="text-red-700">*</span></label>
				<select id="role" name="role"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					required>
						<option value="user">User</option>			
						<option value="user">Set Manager</option>			
						<option value="admin">Admin</option>
						<option value="project manager">Project Manager</option>
						<option value="admin gudang">Admin Gudang</option>
						<option value="supervisor">Supervisor</option>
						<option value="purchasing">Purchasing</option>
						<option value="logistic">Logistic</option>
				</select>
				@error('role') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Email<span class="text-red-700">*</span></label>
				<input type="text" id="email" name="email"
					value="{{ old('email') }}"
					class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
					placeholder="Masukkan Email" required>
					@error('email') @include('shared.errorText') @enderror
			</div>
			<div>
				<label for="no_hp" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">No Hp<span class="text-red-700">*</span></label>
				<textarea name="no_hp" id="no_hp" rows="1"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan No HP Barang" required>{{ old('no_hp') }}</textarea>
				 @error('no_hp') @include('shared.errorText') @enderror
			</div>
			<div class="col-span-2">
				<label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300" for="gambar">Gambar</label>
				<input
					class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
					name="gambar" aria-describedby="gambar_help" id="gambar" type="file" accept="image/*" value="{{ old('gambar') }}">
					@error('gambar') @include('shared.errorText') @enderror
			</div>
			<div class="col-span-2">
				<label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Password<span class="text-red-700">*</span></label>
				<input name="password" id="password" rows="1" type="password"
				 class="block w-full resize-y min-h-[3em] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
				 placeholder="Masukkan Password" required>
				 @error('password') @include('shared.errorText') @enderror
			</div>
		</div>
		<button type="submit"
			class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-auto">Submit</button>
	</form>
@endsection
