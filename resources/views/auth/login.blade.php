@extends('layouts.auth')
@section('title', 'Burda Contraco - Masuk')
@section('content')
@push('prepend-script')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
  <script>
    var timezone = moment.tz.guess();
    $('#timezone').val(timezone);
  </script>
@endpush
<!-- Page Content -->
<div class="card">
  @if (session('registerSuccess'))
      @section('alertMessage', session('registerSuccess'))
      @include('shared.alerts.success')
  @endif
  @if (session('loginError'))
      @section('alertMessage',session('loginError'))
      @include('shared.alerts.error')
  @endif
  <h1 class="text-4xl font-medium">Masuk</h1>
  @error('loginError') @include('shared.errorText') @enderror
  <form method="POST" action="{{ route('authenticate') }}" class="mt-3">
    @csrf
    <div class="flex flex-col space-y-5">
      <input type="hidden" name="timezone" id="timezone">
      <label for="email">
        <p class="font-medium text-slate-700 pb-2">Alamat email</p>
        <input id="email" name="email" type="email" class="w-full py-3 border border-primary rounded-lg px-3 focus:outline-none focus:border-green hover:shadow 
        @error('email') border-red-500 @enderror @error('loginError') border-red-500 @enderror" 
        placeholder="Masukkan Email Anda"
          value="{{ old('email') }}"
        >
        @error('email') @include('shared.errorText') @enderror
      </label>
      <label for="password">
        <p class="font-medium text-slate-700 pb-2">Password</p>
        <input id="password" name="password" type="password" 
        class=" @error('password') border-red-500 @enderror @error('loginError') border-red-500 @enderror
         w-full py-3 border border-primary rounded-lg px-3 focus:outline-none focus:border-green hover:shadow" 
        placeholder="Masukkan Password Anda">
        @error('password') @include('shared.errorText') @enderror
      </label>
      <button class="w-full py-3 font-medium text-white bg-primary hover:bg-primary-light rounded-lg border-primary-bg-primary-light hover:shadow inline-flex space-x-2 items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          <span>Masuk</span>
      </button>
      <p class="text-center">Belum punya akun? 
        <a href="{{ route('register') }}" class="text-primary font-medium inline-flex space-x-1 items-center">
          <span>Daftar Sekarang</span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </span>
        </a>
      </p>
    </div>
  </form>
</div>
@endsection
