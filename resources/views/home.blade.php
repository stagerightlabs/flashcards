@extends('layout')

@section('content')
<h1>{{ $domain->name }}</h1>
@livewire('cards')
@livewire('create-card')
<div>
  <p>
    You are logged in!
  </p>
  <p>{{ $user->email }}</p>
  <p><a href="{{ route('logout') }}">Log out</a></p>
</div>
@stop
