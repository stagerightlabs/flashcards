@extends('layout')

@section('content')
<header class="flex items-center text-5xl p-4">
  <h1 class="mr-4 text-gray-800">{{ $domain->name }}</h1>
  @livewire('create-card-button')
</header>
@livewire('create-card-form')
@livewire('card-detail')
@livewire('cards')
<div class="fixed bottom-0 right-0 w-full md:w-auto md:m-4 bg-gray-300 border border-gray-400 p-2 flex md:rounded-tl-full md:rounded-bl-full items-center justify-around shadow-lg">
  <img src="{{ $user->avatar }}" alt="avatar" class="w-12 rounded-full mr-4" title="{{ $user->email }}">
  <p><a href="{{ route('logout') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded h-8">Log out</a></p>
</div>
@stop
