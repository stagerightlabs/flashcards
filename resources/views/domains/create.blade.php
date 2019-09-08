@extends('layout')

@section('title')
  Create Domain
@stop

@section('content')
<h2>Create A Domain</h2>

<form action="{{ route('domains.store') }}" method="POST">
  @csrf
  <input type="text" name="name">
  @error('name')
    <p>{{ $message }}</p>
  @enderror
  <button type="submit">Create</button>
</form>

@stop
