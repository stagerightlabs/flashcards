@extends('layout')

@section('title')
  Create Domain
@stop

@section('content')
<form action="{{ route('domains.store') }}" method="POST">
  <div class="fixed inset-0 w-full h-screen flex items-center justify-center">
    <div class="w-full md:w-1/2 bg-gray-100 shadow-xl rounded-sm p-4 md:p-8 m-4">
      @csrf
      <article class="overflow-y-scroll h-full mb-4">
        <div class="field">
          <label for="name" class="text-gray-600 text-lg">Name Your New Knowledge Domain</label>
          <input type="text" name="name" id="name" class="w-full h-12 text-2xl bg-gray-100 font-serif p-2 text-gray-800 border">
          @error('name')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
      </article>
      <footer class="flex justify-between">
        <button type="submit" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">Create</button>
      </footer>
    </div>
  </div>
</form>

@include('badge')

@stop
