@extends('layout')

@section('content')
<h1 class="text-4xl">Welcome</h1>
<p>Please <a href="{{ route('auth.google') }}">log in</a>.
@stop
