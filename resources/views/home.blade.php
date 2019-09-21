@extends('layout')

@section('content')
<header class="flex items-center text-5xl p-4">
  <h1 class="mr-4 text-gray-800">{{ $domain->name }}</h1>
  @livewire('create-card-button')
</header>
@livewire('create-card-form')
@livewire('card-detail')
@livewire('cards')
@include('badge')
@stop
