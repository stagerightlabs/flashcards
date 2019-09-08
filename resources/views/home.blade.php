@extends('layout')

@section('content')
<h1>Home Page</h1>
<div>
  <p>Domain: {{ $domain->name }}</p>
  @livewire('counter')
</div>
<div>
  <p>
    You are logged in!
  </p>
  <p>{{ $user->email }}</p>
  <p><a href="{{ route('logout') }}">Log out</a></p>
</div>
@stop
