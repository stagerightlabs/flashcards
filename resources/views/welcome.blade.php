@extends('layout')

@section('content')
<h1>Welcome</h1>
<p>Please <a href="{{ route('auth.google') }}">log in</a>.
@stop
