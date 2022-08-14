@extends('layouts.app')

@section('content')
<div class="px-4 w-full">
  @section('headerName') Dashboard @endsection
  @include('includes.header')
  <div class="flex w-full my-5">
    @section('action') /home @endsection
    @include('shared.search')
  </div>
</div>
@endsection
