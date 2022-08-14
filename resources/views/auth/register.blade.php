@extends('layouts.auth')
@section('title', 'Burda Contraco - Daftar')
@section('content')
<!-- Page Content -->
<div class="card">
    @if (session('registerError'))
        @section('alertMessage',session('registerError'))
        @include('shared.alerts.error')
    @endif
    <h1 class="text-4xl font-medium">Daftar</h1>
    <form method="POST" action="{{ route('register-user') }}" class="mt-3">
        @csrf
        <div class="flex flex-col space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <label for="nama">
                    <p class="font-medium text-slate-700 pb-2">Nama</p>
                    <input id="nama" name="nama" type="text"
                        class="input-field @error('nama') border-red-500 @enderror @error('registerError') border-red-500 @enderror" 
                        placeholder="Masukkan Nama Anda"
                        value="{{ old('nama') }}"
                    >
                    @error('nama') @include('shared.errorText') @enderror
                </label>
                <label for="email">
                    <p class="font-medium text-slate-700 pb-2">Email address</p>
                    <input id="email" name="email" type="email" 
                    class="input-field @error('email') border-red-500 @enderror @error('registerError') border-red-500 @enderror" 
                    placeholder="Masukkan Email Anda"
                    value="{{ old('email') }}"
                    >
                    @error('email')  @include('shared.errorText')  @enderror
                </label>
                <label for="password">
                    <p class="font-medium text-slate-700 pb-2">Password</p>
                    <input id="password" name="password" type="password" class="input-field @error('password') border-red-500 @enderror @error('registerError') border-red-500 @enderror" placeholder="Masukkan Password Anda">
                    @error('password')  @include('shared.errorText')  @enderror
                </label>
                <label for="no_hp">
                    <p class="font-medium text-slate-700 pb-2">Nomor Handphone</p>
                    <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp') }}" class="input-field @error('no_hp') border-red-500 @enderror @error('registerError') border-red-500 @enderror" placeholder="Masukkan Nomor Handphone Anda">
                    @error('no_hp')  @include('shared.errorText')  @enderror
                </label>
                <label for="companycode" class="col-span-2">
                    <p class="font-medium text-slate-700 pb-2">Kode Perusahaan</p>
                    <input id="companycode" name="companycode" type="text" class="input-field @error('companycode') border-red-500 @enderror @error('registerError') border-red-500 @enderror" placeholder="Masukkan Kode Perusahaan">
                    @error('companycode')  @include('shared.errorText')  @enderror
                </label>
            </div>
            <button type="submit" class="w-full py-3 font-medium text-white bg-primary hover:bg-primary-light rounded-lg border-primary-light hover:shadow inline-flex space-x-2 items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Daftar</span>
            </button>
            <p class="text-center">Sudah Punya Akun? 
                <a href="{{ route('login') }}" class="text-primary font-medium inline-flex space-x-1 items-center">
                    <span>Masuk Sekarang</span>
                    <span>
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </span>
                </a>
            </p>
        </div>
    </form>
</div>
@endsection
