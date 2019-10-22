@extends('layout')

@section('content')
<header class="flex justify-between text-5xl p-4">
  <div class="flex">
    <h1 class="mr-4 text-gray-800">{{ $domain->name }}</h1>
    @livewire('create-card-button')
  </div>
  <div class="flex items-center">
    @livewire('domain-selector')
  </div>
</header>
@livewire('create-card-form')
@livewire('card-detail')
@livewire('cards')
@include('badge')
@stop
